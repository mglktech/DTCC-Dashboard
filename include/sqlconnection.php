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

function QueryTrigger($sql)
{
    $audit_type = explode(" ", $sql)[0];
    $author = "PROGRAM";
    $time = time();
    if (isset($_SESSION["steam_id"])) {
        $author = $_SESSION["steam_id"];
    }

    if ($audit_type == "INSERT" || $audit_type == "DELETE" || $audit_type == "REPLACE") {
        $target_table = explode(" ", $sql)[2];
    }
    if ($audit_type == "UPDATE") {
        $target_table = explode(" ", $sql)[1];
    }
    if (isset($target_table)) {
        $fixed_sql = quotefix($sql);
        $audit_sql = "INSERT INTO audit_logs (audit_type,target_table,author,sql_code,timestamp) VALUES('$audit_type','$target_table','$author','$fixed_sql','$time')";
        $conn = OpenCon();
        $conn->query($audit_sql);
        $conn->close();
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
    QueryTrigger($sql);
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
    $sql = "INSERT INTO sql_errors (`timestamp`,`client`,`sqlcode`,`errormsg`)VALUES('$time','$client','$statement','$error')";
    $conn->query($sql);
    $sql = "SELECT id FROM `sql_errors` where `timestamp` = '$time' ORDER BY `id`";
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

function EXPORT_DATABASE($host, $user, $pass, $name,       $tables = false, $backup_name = false)
{
    $mysqli = new mysqli($host, $user, $pass, $name);
    $mysqli->select_db($name);
    $mysqli->query("SET NAMES 'utf8'");
    $queryTables = $mysqli->query('SHOW TABLES');
    while ($row = $queryTables->fetch_row()) {
        $target_tables[] = $row[0];
    }
    if ($tables !== false) {
        $target_tables = array_intersect($target_tables, $tables);
    }
    $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `" . $name . "`\r\n--\r\n\r\n\r\n";
    foreach ($target_tables as $table) {
        if (empty($table)) {
            continue;
        }
        $result    = $mysqli->query('SELECT * FROM `' . $table . '`');
        $fields_amount = $result->field_count;
        $rows_num = $mysqli->affected_rows;
        $res = $mysqli->query('SHOW CREATE TABLE ' . $table);
        $TableMLine = $res->fetch_row();
        $content .= "\n\n" . $TableMLine[1] . ";\n\n";
        $TableMLine[1] = str_ireplace('CREATE TABLE `', 'CREATE TABLE IF NOT EXISTS `', $TableMLine[1]);
        for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
            while ($row = $result->fetch_row()) { //when started (and every after 100 command cycle):
                if ($st_counter % 100 == 0 || $st_counter == 0) {
                    $content .= "\nINSERT INTO " . $table . " VALUES";
                }
                $content .= "\n(";
                for ($j = 0; $j < $fields_amount; $j++) {
                    $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                    if (isset($row[$j])) {
                        $content .= '"' . $row[$j] . '"';
                    } else {
                        $content .= '""';
                    }
                    if ($j < ($fields_amount - 1)) {
                        $content .= ',';
                    }
                }
                $content .= ")";
                //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                    $content .= ";";
                } else {
                    $content .= ",";
                }
                $st_counter = $st_counter + 1;
            }
        }
        $content .= "\n\n\n";
    }
    $content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
    $backup_name = $backup_name ? $backup_name : $name . '___(' . date('H-i-s') . '_' . date('d-m-Y') . ').sql';
    // ob_get_clean();
    // header('Content-Type: application/octet-stream');
    // header("Content-Transfer-Encoding: Binary");
    // header('Content-Length: ' . (function_exists('mb_strlen') ? mb_strlen($content, '8bit') : strlen($content)));
    // header("Content-disposition: attachment; filename=\"" . $backup_name . "\"");
    // echo $content;

    return [$backup_name,$content];
    exit;
}
