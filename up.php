<?php
include "include/sqlconnection.php";
function PrepareSteamURL($steam_link)
{
    require_once "steam/SteamUser.php";


    $split = explode("/", $steam_link);
    if (count($split) >= 4) {
        $user = new SteamUser($split[4]);
    } else {
        $user = NULL;
    }
    return $user;

    // auto-checks for vanity url
    //print_r($user);

}
$sql = "SELECT `steam_link` FROM `old_players`";
$steamlinksObj = Query($sql);
$steamids = array();
foreach ($steamlinksObj as $row) {
    $steamid = PrepareSteamURL($row->steam_link)->steamID64;
    Update($row->steam_link, $steamid);
}

function Update($steamlink, $steamid)
{
    $sql = "UPDATE `old_players` SET `steam_id`='$steamid' WHERE `steam_link` = '$steamlink'";
    Query($sql);
    echo "done<br>";
}
