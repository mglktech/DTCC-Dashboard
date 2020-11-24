<?php include "include/header.php";
include "include/sqlconnection.php";
include "include/elements.php";
$sql = "SELECT `time_applied`, `discord_name` FROM `old_new_players` where `char_name` is null order by char_name";
$tblHeaders = ["Name", "Discord"];
$result = Query($sql);
$tblBody = array();
foreach ($result as $row) {
    $tblRow = array();
    $tblRow[] = $row->old_name;
    $tblRow[] = "@" . $row->old_discord_name;
    $tblBody[] = $tblRow;
}

Tablefy($tblHeaders, $tblBody);
include_once "include/footer.php";
