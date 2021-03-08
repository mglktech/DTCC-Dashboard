<?php include "../include/components/head.php";

include "../include/elements.php";



function FindRecordDetails($id)
{
    $sql = "SELECT steam_name FROM shift_records WHERE id = '$id'";
    if (isset(Query($sql)[0]->steam_name)) {
        return Query($sql)[0]->steam_name;
    } else {
        return null;
    }
}
function FindSteamID($steam_name)
{
    $sql = "SELECT steam_id FROM players WHERE steam_name = '$steam_name'";
    if (isset(Query($sql)[0]->steam_id)) {
        return Query($sql)[0]->steam_id;
    } else {
        return null;
    }
}

function UpdateRow($id, $steam_id)
{
    $sql = "UPDATE verified_shifts SET steam_id = '$steam_id' WHERE id = '$id'";
    Query($sql);
}


$sql = "SELECT id, inRow, outRow FROM verified_shifts WHERE steam_id = ''";
$res = Query($sql);
$head = ["inRow", "outRow", "Steam Name", "Steam ID"];
$body = array();
foreach ($res as $row) {
    $sName = FindRecordDetails($row->inRow);
    $steam_id = FindSteamID($sName);
    if ($steam_id) {
        UpdateRow($row->id, $steam_id);
    }
    $newRow = array();
    $newRow[] = $row->inRow;
    $newRow[] = $row->outRow;
    $newRow[] = $sName;
    $newRow[] = $steam_id;
    $body[] = $newRow;
}

Tablefy($head, $body); ?>
<a type="button" class="btn btn-secondary" href="../index.php">Go Back</a>
<?php
include "../include/components/foot.php";
