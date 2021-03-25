<?php
include "../include/components/head.php";
isset($_GET["id"]) ? $id = $_GET["id"] : $id = null;

if (isset($_POST["callsign"])) {
    AssignCallsign($id, $_POST["callsign"]);
}

function AssignCallsign($id, $label)
{
    Query("UPDATE `callsigns` SET `assigned_steam_id`='$id' where `label` = '$label'");
}

function IdHasCallsign($id)
{
    if ($id) {
        return QueryFirst("SELECT * FROM `callsigns` WHERE `assigned_steam_id` = '$id'");
    } else {
        return null;
    }
}

function CollectCallsigns($rank, $region)
{
    $sql = "SELECT * FROM `callsigns` WHERE `min_rank` = '$rank' AND `region` = '$region' AND assigned_steam_id is null limit 10";

    return Query($sql);
}



$player = QueryFirst("SELECT * FROM `public_players` WHERE `steam_id` = '$id'");
if ($player->timezone == "GMT (Europe/London)") { // temporary patch because timezones are not being put to players table properly
    $player->timezone = "GMT";
}
if ($player->timezone == "Eastern (US/Canada)") {
    $player->timezone = "EST";
}
$available_callsigns = CollectCallsigns($player->rank, $player->timezone);
$existing_callsign = IdHasCallsign($id);
$latest_test = QueryFirst("SELECT * FROM `tests` WHERE `steam_id` = '$id' ORDER BY `id` DESC");
?>
<div class="container">
    <div class="row">
        <div class="col d-flex flex-column justify-content-center align-items-center">
            <h1>Assign Callsign</h1>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex flex-column align-items-end">
            <h5>You are assigning a callsign for:</h5>
        </div>
        <div class="col d-flex flex-column justify-content-center align-items-start align-content-center">
            <h5><?= $player->char_name ?></h5>
        </div>
    </div>
    <?php if ($existing_callsign) {
        include "elems/callsign_already_assigned.php";
    } else {
        include "elems/callsign_part.php";
    } ?>

</div>


<?php include "../include/components/foot.php";
