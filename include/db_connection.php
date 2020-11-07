<?php
function OpenCon()
{
    $dbhost = "localhost";
    $dbuser = "id15098854_dtcc";
    $dbpass = "ao|=vda19gvOlpBs";
    $db = "id15098854_dtccdb";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

    return $conn;
}

function CloseCon($conn)
{
    $conn->close();
}


function SqlRun($sql)
{
    $conn = OpenCon();
    $query = $conn->query($sql);
    if ($query === TRUE) {
        return "Success";
    } else {
        return "failure";
    }
}

function fetchAll($sql)
{
    $conn = OpenCon();
    $query = $conn->query($sql);
    if ($query) {
        return $query->fetch_all();
    } else {
        return false;
    }
    CloseCon($conn);
}


function fetchRow($sql)
{
    $conn = OpenCon();
    $query = $conn->query($sql);
    if ($query) {
        return $query->fetch_row();
    } else {
        return false;
    }
    CloseCon($conn);
}
function fetchPlayerFormatted($steam_id)
{
    $player = fetchPlayer($steam_id);
    return $player[0] . " | " . $player[1];
}

function IsAlive($name)
{

    $exp = explode(" ", $name);
    if (count($exp) > 1) {
        $ctx = stream_context_create(array('http' =>
        array(
            'timeout' => 1,  //1200 Seconds is 20 Minutes
        )));
        $link = "http://playhigh.life:30120/highlife/character/" . $exp[0] . "/" . $exp[1];
        $reply = @file_get_contents($link, false, $ctx);
        if ($reply ===  FALSE) {
            return -2;
        } else {
            $r = json_decode($reply);
        }
        if (isset($r->data->dead)) {
            if ($r->data->dead) {
                return 0;
            } else {
                return 1;
            }
        } else {
            return -1;
        }
    } else {
        return -3;
    }
}

function fetchPlayer($steam_id)
{
    $sql = "SELECT
    callsigns.label AS callsign,
    players.char_name,
    players.rank,
    players.steam_id,
    players.steam_name,
    players.discord_name
FROM
    players
LEFT JOIN callsigns ON players.steam_id = callsigns.assigned_steam_id
WHERE players.steam_id = '$steam_id'";
    $conn = OpenCon();
    $query = $conn->query($sql);
    if ($query) {
        return $query->fetch_row();
    } else {
        return false;
    }
    CloseCon($conn);
}
