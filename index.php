<?php include "include/header.php";
include "include/sqlconnection.php";
include "include/elements.php";

if ($_SESSION['steam_id'] == '76561197995263974' || $_SESSION['steam_id'] == '76561198050836459') {

    include "include/inc_index_senior.php";
}

function getLastUpdated()
{
    $sql = "SELECT MAX(`timestamp`) AS max FROM `public_verified_shifts`";
    $result = Query($sql)[0]->max;
    return date("F j, Y, g:i a", $result);
}

function sqlCollectTotals()
{
    return Query("SELECT DISTINCT a.steam_id, a.callsign, a.char_name, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id) duration FROM public_verified_shifts a  where a.callsign is not null order by duration desc");
}

// function CollectTotals()
// {
//     $time = time();
//     $players_table = Query("SELECT DISTINCT steam_id, callsign, char_name FROM public_verified_shifts");
//     $month = 2629743; // select times within 1 month of now
//     $diff = $time - $month;
//     $players = array();

//     foreach ($players_table as $p) {
//         $player = new stdClass();
//         $player->steam_id = $p->steam_id;
//         $player->callsign = $p->callsign;
//         $player->char_name = $p->char_name;
//         $player->duration = Query("SELECT SUM(`duration`) AS duration FROM public_verified_shifts WHERE steam_id='$p->steam_id'")[0]->duration;
//         $players[] = $player;
//     }
//     return $players;
// }
?>
<div class="container">
    <div class="row">
        <div class="col text-center">
            <h2>Shifts Leaderboard</h2>
            <h5>Last Updated: <?php echo getLastUpdated(); ?></h5>
            <?php $tData = sqlCollectTotals();
            $thead = ["", "Name", "Time Clocked In"];
            $tbody = array();
            if ($tData) {
                foreach ($tData as $index => $row) {
                    $tRow = array();
                    $tRow[] = $index + 1;
                    $tRow[] = $row->callsign . " | " . $row->char_name;
                    $tRow[] = toDurationDays($row->duration);
                    $tbody[] = $tRow;
                }
            }
            Tablefy($thead, $tbody);
            ?>
        </div>
    </div>
</div>
<?php
include "include/footer.php";
