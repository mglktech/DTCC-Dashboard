<?php
include "include/header.php";

function PrepareSteamURL($steam_link)
{
    require_once "steam/SteamUser.php";


    $split = explode("/", $steam_link);
    if (count($split) >= 4) {
        echo "<br>Digesting user: " . $split[4];
        $user = new SteamUser($split[4]);
        if (isset($user->steamID64)) {
            $steamid = $user->steamID64;
            //echo "<br> SteamID: " . $steamid;
        } else {
            $steamid = NULL;
        }
    } else {
        $steamid = NULL;
    }
    return $steamid;

    // auto-checks for vanity url
    //print_r($user);

}

function FetchSteamIDs()
{
    $sql = "SELECT `discord_name`,`steam_link` FROM `old_applicants`";
    $steamlinksObj = Query($sql);
    $steamids = array();
    foreach ($steamlinksObj as $row) {
        $steamid = PrepareSteamURL($row->steam_link);
        if ($steamid) {
            $updateClass = new stdClass();
            echo "<br>FOUND steamid for" . $row->discord_name . " : " . $row->steam_link . " : " . $steamid;
            $updateClass->steam_link = $row->steam_link;
            $updateClass->steam_id = $steamid;
            $steamids[] = $updateClass;
        } else {
            echo "<br>Cound not find SteamID for " . $row->discord_name;
        }
    }
    echo "<br>Now updating database...";
    foreach ($steamids as $obj) {
        Update($obj->steam_link, $obj->steam_id);
    }
}



function Update($steamlink, $steamid)
{
    $sql = "UPDATE old_applicants SET `steam_id`='$steamid' WHERE `steam_link` = '$steamlink'";
    echo "<br>" . $sql;
    Query($sql);
}

echo "<br>Fetching SteamIDs...";
FetchSteamIDs();
include "include/footer.php";
