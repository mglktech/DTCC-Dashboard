<?php
function OpenCon()
{
    $dbhost = "b2jkvqohsniplb2k4l6d-mysql.services.clever-cloud.com";
    $dbuser = "udpojygnzb3gtx9n";
    $dbpass = "Oed8M8GCOLjtjskOedP2";
    $db = "b2jkvqohsniplb2k4l6d";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

    return $conn;
}

//Clever Cloud
// MYSQL_ADDON_HOST=b2jkvqohsniplb2k4l6d-mysql.services.clever-cloud.com
// MYSQL_ADDON_DB=b2jkvqohsniplb2k4l6d
// MYSQL_ADDON_USER=udpojygnzb3gtx9n
// MYSQL_ADDON_PORT=3306
// MYSQL_ADDON_PASSWORD=Oed8M8GCOLjtjskOedP2 
// MYSQL_ADDON_URI=mysql://udpojygnzb3gtx9n:Oed8M8GCOLjtjskOedP2@b2jkvqohsniplb2k4l6d-mysql.services.clever-cloud.com:3306/b2jkvqohsniplb2k4l6d

// DTCC Dashboard Bot
// Client_ID = 797117741798260758
// Client Secret = 4TirbgddOb6V81-X7ZTv2yRt138oXEJx
// Public Key = 318e327dbfecbc9f4d9efb478a72f2c4925e0a8be7a7cef01d64df47f3489029
function pass_hash()
{
    $hash = "fXAtARFfU3vAQZni";
    return $hash;
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

    // $exp = explode(" ", $name);
    // if (count($exp) > 1) {
    //     $ctx = stream_context_create(array('http' =>
    //     array(
    //         'timeout' => 1,  //1200 Seconds is 20 Minutes
    //     )));
    //     $link = "http://playhigh.life:30120/highlife/character/" . $exp[0] . "/" . $exp[1];
    //     $reply = @file_get_contents($link, false, $ctx);
    //     if ($reply ===  FALSE) {
    //         return -2;
    //     } else {
    //         $r = json_decode($reply);
    //     }
    //     if (isset($r->data->dead)) {
    //         if ($r->data->dead) {
    //             return 0;
    //         } else {
    //             return 1;
    //         }
    //     } else {
    //         return -1;
    //     }
    // } else {
    //     return -3;
    // }
    return -2;
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
