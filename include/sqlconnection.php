<?php include "db_connection.php";

function set_temp_code($steam_name, $code)
{
    //Users are given a Temporary code to log in on forgot password/first use
    // checks against "code" column of players table
    //$hash = pass_hash();
    $code_hash = password_hash($code, PASSWORD_BCRYPT);
    $sql = "UPDATE players 
    SET code='$code_hash'
    WHERE steam_name = '$steam_name'";
    Query($sql);
}
function check_temp_code($steam_name, $code)
{
    $sql = "SELECT code FROM players WHERE steam_name='$steam_name'";
    $response = Query($sql);
    if ($response) {
        return password_verify($code, $response[0]->code);
    } else {
        return false;
    }
}

function set_password($steam_name, $code)
{
    //$hash = pass_hash();
    $code_hash = password_hash($code, PASSWORD_BCRYPT);
    $sql = "UPDATE players 
    SET code=NULL,
    pw_hash='$code_hash'
    WHERE steam_name = '$steam_name'";
    Query($sql);
    return $sql;
}

function check_password($steam_name, $code)
{
    $sql = "SELECT pw_hash FROM players WHERE steam_name='$steam_name'";
    $response = Query($sql);
    if ($response) {
        return password_verify($code, $response[0]->pw_hash);
    } else {
        return false;
    }
}

function Query($sql)
{
    $response = array();
    $conn = OpenCon();
    //$stmt = $conn->prepare($sql);
    $result = $conn->query($sql);
    if ($result) {

        if (!is_bool($result)) {
            while ($obj = $result->fetch_object()) {
                $response[] = $obj;
            }
            $result->close();
        }
    }
    if (!$result) {
        //printf("Error message: %s\n", $conn->error);
        LogError($sql, $conn->error);
    }

    if (count($response) > 0) {
        return $response;
    }
    $conn->close();
}
function quotefix($str)
{
    return str_replace("'", "''", "$str");
}

function LogError($statement, $error)
{
    $time = time();
    $conn = OpenCon();
    $client = $_SESSION['steam_id'];
    $statement = quotefix($statement);
    $error = quotefix($error);
    $sql = "INSERT INTO `sql_errors` (`timestamp`,`client`,`sqlcode`,`errormsg`)VALUES('$time','$client','$statement','$error')";
    $conn->query($sql);
    $sql = "SELECT `id` FROM `sql_errors` where `timestamp` = '$time' ORDER BY `id`";
    $resp = Query($sql);
    printf("SQL Error detected. Error ID: %s\n", $resp[0]->id);
}

function q_fetchPlayer($steam_id)
{
    $sql = "SELECT
    callsigns.label AS callsign,
    players.char_name AS char_name,
    players.rank AS rank,
    players.steam_id AS steam_id,
    players.steam_name AS steam_name,
    players.phone_number AS phone_number,
    players.discord_name AS discord_name,
    players.av_icon AS av_icon
FROM
    players
LEFT JOIN callsigns ON players.steam_id = callsigns.assigned_steam_id
WHERE players.steam_id = '$steam_id'";
    return Query($sql)[0];
}

function q_fetchPlayerFormatted($steam_id)
{
    $player = q_fetchPlayer($steam_id);
    return $player->callsign . " | " . $player->char_name;
}
