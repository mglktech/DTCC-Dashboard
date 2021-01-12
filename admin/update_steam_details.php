<?php include "../include/header.php";
include "../include/sqlconnection.php";
include "../steam/SteamWebAPI_Simple.php";
include "../include/elements.php";


function CheckSteamNames()
{
    $sql = "SELECT steam_id, steam_name, char_name FROM players WHERE rank>='0'";
    $players = Query($sql);
    $p_ch_sn_ary = array(); // player character steam_name array

    foreach ($players as $player) {
        $response_steam_name = GetSteamDetails($player->steam_id)->steam_name;
        if (isset($response_steam_name) && $response_steam_name != $player->steam_name) {
            $p = new stdClass();
            $p->char_name = $player->char_name;
            $p->old_steam_name = $player->steam_name;
            $p->new_steam_name = $response_steam_name;
            $p_ch_sn_ary[] = $p;
            UpdateDB($player->steam_id, $response_steam_name);
        }
    }
    return $p_ch_sn_ary;
}

function TableConstr()
{
    $head = ["Player Name", "Old Steam Name", "New Steam Name"];
    $content = CheckSteamNames();
    $body = array();
    foreach ($content as $c) {
        $row = array();
        $row[] = $c->char_name;
        $row[] = $c->old_steam_name;
        $row[] = $c->new_steam_name;
        $body[] = $row;
    }
    return Tablefy($head, $body);
}
function UpdateDB($steamID, $new_steam_name)
{
    $sql = "UPDATE players SET steam_name='$new_steam_name' WHERE steam_id = '$steamID'";
    Query($sql);
}

?>
Players who have changed their steam names:
these new steam names have been updated:
<?php TableConstr();

include "../include/footer.php"; ?>