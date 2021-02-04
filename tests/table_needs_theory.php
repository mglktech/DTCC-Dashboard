<?php include '../include/components/head.php';
include "../include/elements.php";


$sql = "SELECT * FROM `players` WHERE `status` = 'Needs Theory' ORDER BY `last_seen`";
$result = Query($sql);

$tblHeaders = ["Player Name", "Discord Name", "Last Seen", ""];

$tblBody = array();
foreach ($result as $row) {
    $tblRow = array();
    $tblRow[] = $row->char_name;
    $tblRow[] = $row->discord_name;
    $tblRow[] = toDateS($row->last_seen);
    $tblRow[] = "<a class='btn btn-primary' href='take_test.php?type=theory&steamid=" . $row->steam_id . "'>Take Test</button>";
    $tblBody[] = $tblRow;
}
?>
<div class="container">
<h2>Needs Theory</h2>
<h5 class="font-italic mb-3 font-weight-normal">Ooh pick me! Pick me!</h5>
<?php
Tablefy($tblHeaders, $tblBody);
?>
</div>
<?php 
include '../include/components/foot.php';
