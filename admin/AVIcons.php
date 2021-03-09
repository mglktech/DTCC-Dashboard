<?php include "../include/components/head.php";

function sel_all_steamids()
{
    $sql = Query("SELECT `steam_id` FROM `players` WHERE 1");
    $steamids = [];
    foreach ($sql as $s) {
        $steamids[] = $s->steam_id;
    }
    return $steamids;
}

function UpdateAvIcons($SteamID)
{
    if (strlen($SteamID) == 17) {
        $webapi = GetSteamDetails($SteamID);
        $av_icon = $webapi->av_icon;
        $av_medium = $webapi->av_medium;
        $av_full = $webapi->av_full;
        $sql = "UPDATE `players` SET `av_icon`='$av_icon', `av_medium` = '$av_medium', `av_full` = '$av_full' WHERE `steam_id` = '$SteamID'";
        Query($sql);
    }
}

function UpdateAllAVIcons()
{
    $steamids = sel_all_steamids();
    foreach ($steamids as $steamid) {
        UpdateAVIcons($steamid);
    }
}


function SteamAPIKey()
{
    $sql = "SELECT * FROM `secrets` WHERE `secret_name` = 'SteamAPIKey'";
    return QueryFirst($sql)->secret;
}



function GetPlayerSummaries($SteamIDs)
{
    $apikey = SteamAPIKey();
    $base = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=" . $apikey . "&steamids=" . $SteamIDs;
    return  json_decode(file_get_contents($base), true);
}

function ResolveVanityUrl($VanityKey)
{
    $apikey = SteamAPIKey();
    $base = "https://api.steampowered.com/ISteamUser/ResolveVanityURL/v1/?key=" . $apikey . "&vanityurl=" . $VanityKey;
    return  json_decode(file_get_contents($base), true);
}



function GetSteamNames($steamids) // test this
{
    $chunks = array_chunk($steamids, 10);
    $steam_names = array();
    foreach ($chunks as $c) {
        $row = "";
        foreach ($c as $steamid) {
            $row += $steamid . ",";
        }
        $summaries = GetPlayerSummaries($row)["response"]["players"];
        foreach ($summaries as $summary) {
            $steam_names[] = $summary["personaname"];
        }
    }
    return $steam_names;
}

function GetSteamDetails($SteamID)
{
    // need to collect personaname, avatar, avatarmedium, avatarfull and pair with steamid
    $player = new stdClass();
    $player->steam_name = null;
    $player->steam_id = null;
    $player->av_icon = null;
    $player->av_medium = null;
    $player->av_full = null;
    if ($SteamID) {
        $summaries = getPlayerSummaries($SteamID);
        $player->steam_name = $summaries["response"]["players"][0]["personaname"];
        $player->steam_id = $summaries["response"]["players"][0]["steamid"];
        $player->av_icon = $summaries["response"]["players"][0]["avatar"];
        $player->av_medium = $summaries["response"]["players"][0]["avatarmedium"];
        $player->av_full = $summaries["response"]["players"][0]["avatarfull"];
    }
    return $player;
}

UpdateAllAVIcons();
include "../include/components/foot.php";
