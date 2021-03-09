<?php include "../include/components/head.php";

include "../include/elements.php";


$limit = 20;
$count = Query("SELECT count(DISTINCT steam_id) AS `count` FROM public_verified_shifts where callsign is not null")[0]->count;
$obj = CreatePaginateObj($count, $limit);
$curtime = time();
$sql = "SELECT DISTINCT a.steam_id, a.discord_name, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id) duration FROM public_verified_shifts a  where a.callsign is not null order by duration desc LIMIT $obj->start,$limit";
$tData = Query($sql);
$thead = ["Name", "Rank", "Time Clocked In", "Discord", ""];
$tbody = array();
if ($tData) {
    foreach ($tData as $row) {
        $tRow = array();
        $tRow[] = $row->callsign . " | " . $row->char_name;
        $tRow[] = Pill(getRank($row->rank));
        $tRow[] = toDurationDays($row->duration);
        $tRow[] = $row->discord_name;
        //$tRow[] = $row->last_seen;
        $tRow[] = "<a class='btn btn-secondary view-player' href='/view_player.php?steamid=" . $row->steam_id . "'>View Player</button>";
        $tbody[] = $tRow;
    }
}
?>
<div class="container">
    <h4>Time Spent On-Shift - Past All Time</h4>
    <?php
    Tablefy($thead, $tbody);
    Paginate($obj);
    ?>
    <a href="/shifts/shifts_index.php" class="btn btn-secondary">Go Back</a>
</div>
<?php
include "../include/components/foot.php";
