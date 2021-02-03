<?php


function SteamAPIKey()
{
    $sql = "SELECT * FROM secrets WHERE secret_name = 'SteamAPIKey'";
    return Query($sql)[0]->secret;
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

function ResolveSteamID($url)
{
    $SteamID = null;
    if (isset(explode("/", $url)[3])) {
        $marker = explode("/", $url)[3];
        if ($marker == "profiles") {
            $SteamID = explode("/", $url)[4];
        }
        if ($marker == "id") {
            $SteamURL = ResolveVanityUrl(explode("/", $url)[4]);
            if(isset($SteamURL["response"]["steamid"])) {
                $SteamID = $SteamURL["response"]["steamid"];
            }
            else {
                $SteamID = null;
            }
        }
    }
    return $SteamID;
}
