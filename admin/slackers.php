<?php include "../include/components/head.php";

include "../include/elements.php";

function employment_start($steam_id)
{
    $sql = "SELECT submit_date FROM tests WHERE `type` = 'practical' and steam_id = '$steam_id'";
    return isset(Query($sql)[0]->submit_date) ? Query($sql)[0]->submit_date : "null";
}

$curtime = time();
$oneMonth = 2629743; //one month in seconds
$MonthAgo = $curtime - $oneMonth;
$sql = "SELECT DISTINCT a.steam_id, a.discord_name, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM _public_verified_shifts b WHERE b.steam_id=a.steam_id and b.time_out > '$MonthAgo') duration FROM _public_verified_shifts a  where a.callsign is not null  order by duration asc";
$tData = Query($sql);
$thead = ["Name", "Rank", "Time Clocked In", "Employment Start", "Discord", ""];
$tbody = array();
if ($tData) {
    foreach ($tData as $row) {
        $employ_start = employment_start($row->steam_id);
        if ($employ_start < $MonthAgo) {
            if ($row->duration < 7200) {

                $tRow = array();
                $tRow[] = $row->callsign . " | " . $row->char_name;
                $tRow[] = Pill(getRank($row->rank));
                $tRow[] = toDurationDays($row->duration);
                $tRow[] = toDateS($employ_start);
                $tRow[] = $row->discord_name;
                $tRow[] = "<a class='btn btn-secondary view-player' href='/players/view_player.php?id=" . $row->steam_id . "'>View Player</button>";
                $tbody[] = $tRow;
            }
        }
    }
}

?>
<div class="container">
    <h2>Frivolous Drivers</h2>
    <h5>DTCC Staff who have been underperforming.</h5>
    <h6>(Less than two hours clocked in over the past month)</h6>
    <?php
    Tablefy($thead, $tbody);
    //Paginate($obj);
    ?>
</div>
<!-- <a href="/shifts/shifts_index.php" class="btn btn-secondary">Go Back</a> -->
<?php
include "../include/components/foot.php";
