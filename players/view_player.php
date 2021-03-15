<?php include "../include/components/head.php";
include "../include/elements.php";


// SQL ADDITIONS
// players table + backstory +av_full +steam_link
// public_players +av_full +backstory +steam_link
// 

isset($_GET["id"]) ? $player_id = $_GET["id"] : $player_id = null;


include "elems/player_funcs.php";

if (Rank("Supervisor")) {
    include "elems/modals.php";
}
include "elems/profile.php";

include "../include/components/foot.php";
