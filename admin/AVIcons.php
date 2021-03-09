<?php include "../include/components/head.php";
include "../steam/SteamWebAPI.php";
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
UpdateAllAVIcons();
include "../include/components/foot.php";
