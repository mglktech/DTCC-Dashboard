<?php include "../include/components/head.php";

include "../include/elements.php";

$timeNow = time();
$oneMonth = 2629743; // one month in seconds (exact to the year, 30.44 days)
$MonthAgo = $timeNow - $oneMonth;

$sql = "SELECT * FROM public_players WHERE last_seen < '$MonthAgo' && rank >'-2'";
$resp = Query($sql);
$head = ["Name", "Rank", "Last Seen", ""];
$body = array();
if ($resp) {
    foreach ($resp as $row) {
        $r = array();
        $r[] = $row->callsign . " | " . $row->char_name;
        $r[] = $row->rank_label;
        $r[] = toDateS($row->last_seen);
        $r[] = "<a class='btn btn-secondary view-player' href='/players/view_player.php?id=" . $row->steam_id . "'>View Player</button>";
        $body[] = $r;
    }
}

?>
<div class="container">
<h3> Inactive Staff (More than a Month ago)</h3>
<?php Tablefy($head, $body); ?>
</div>
<?php 
include "../include/components/foot.php";
