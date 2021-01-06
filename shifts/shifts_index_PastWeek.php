<?php include "../include/header.php";
include "../include/sqlconnection.php";
include "../include/elements.php";


$limit = 20;
$count = Query("SELECT count(DISTINCT steam_id) AS `count` FROM public_verified_shifts where callsign is not null")[0]->count;
$obj = CreatePaginateObj($count, $limit);
$curtime = time();
$oneWeek = 604800; //one week in seconds
$WeekAgo = $curtime - $oneWeek;
$sql = "SELECT DISTINCT a.steam_id, a.discord_name, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id and b.time_out > '$WeekAgo') duration FROM public_verified_shifts a  where a.callsign is not null order by duration desc LIMIT $obj->start,$limit";
$tData = Query($sql);
$thead = ["Name", "Rank", "Time Clocked In", "Discord", ""];
$tbody = array();
if ($tData) {
    foreach ($tData as $row) {
        $tRow = array();
        $tRow[] = $row->callsign . " | " . $row->char_name;
        $tRow[] = Pill("rank_" . $row->rank);
        $tRow[] = toDurationDays($row->duration);
        $tRow[] = $row->discord_name;
        $tRow[] = "<a class='btn btn-secondary view-player' href='/view_player.php?steamid=" . $row->steam_id . "'>View Player</button>";
        $tbody[] = $tRow;
    }
}
?>
<h4>Time Spent On-Shift - Past Week</h4>
<?php
Tablefy($thead, $tbody);
Paginate($obj);
?>
<a href="/shifts/shifts_index.php" class="btn btn-secondary">Go Back</a>
<?php
include "../include/footer.php";
