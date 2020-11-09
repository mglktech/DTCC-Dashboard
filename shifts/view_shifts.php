
<?php
include "../include/header.php";
include "../include/db_connection.php";

function CreateShifts()
{   // shift object will consist of steam_name, inRows, inTimes, outRow, outTime
    // ContainsOut will be figured from whether outRow contains any data
    // RowsToReject will be filled on whether the InTime being added is more than 12 hours from the corresponding OutTime
    // to begin, build an array of all servers, and include relevant data
    $sql = "SELECT DISTINCT `server` FROM `shift_records`";
    $servers = fetchAll($sql);
    print_r($servers);
    // next, build an array of steam names
    $sql = "SELECT DISTINCT `steam_name` FROM `shift_records`";
    $steam_names = fetchAll($sql);
    //print_r($steam_names);
    $records_by_server = array();
    foreach ($servers as $s) { // foreach server number
        foreach ($steam_names as $sn) { // foreach player name
            $sql = "SELECT * FROM `shift_records` WHERE (`steam_name` = '$sn[0]' AND `server` = '$s[0]' AND `signature` IS NULL) ORDER BY `id`";
            $records = fetchAll($sql);

            $PrunedShifts = PruneShiftRecords($records);
            //print_r($PrunedShifts);
        }
    }
}
function PruneShiftRecords($records)
{
    $shifts = array();
    $shift = new stdClass();
    $shift->InRows = array();
    $shift->InTimes = array();
    foreach ($records as $r) {
        $id = $r[0];
        $timestamp = $r[1];
        //$server = $r[2];
        $steam_name = $r[3];
        $io = $r[4];
        echo "steamname: " . $steam_name . ", id: " . $id . " time: " . $timestamp . " io: " . $io . "<br>";
        // if ($io == "in") {
        //     $shift->InRows[] = $id;
        //     $shift->InTimes[] = $timestamp;
        // }
        // if ($io == "out") {
        //     $shift->OutTime = $timestamp;
        //     $shift->OutRow = $id;
        //     $pruned_shift = PruneShift($shift);
        //     $shifts[] = $pruned_shift;
        // }
    }
}

function PruneShift($shift)
{
    $ServerResetFrequency = 43200;
    $PrunedShift = new stdClass();
    $PrunedShift->InRows = array();
    $PrunedShift->InTimes = array();
    $RejectRows = array();

    foreach ($shift->InTimes as $ind => $in) {
        $compare = $shift->OutTime - $in;
        echo "COMPARE = " . $compare . "<br>";
        if ($compare < $ServerResetFrequency) // IF in is less than 12 hours from Out
        {
            $PrunedShift->InRows[] = $shift->InRows[$ind];
            $PrunedShift->InTimes[] = $in;
        } else {
            $RejectRows[] = $shift->InRows[$ind];
        }
    }
    // now deal with rejected rows
    foreach ($RejectRows as $r) {
        $sql = "UPDATE `shift_records` SET `signature`='Automatic',`outcome`='Reject',`reason`='>12hrsFromOut' WHERE `id` = '$r'";
        //sqlRun($sql);
        echo $sql . "<br>";
    }
    // now return pruned shifts
    return $PrunedShift;
}
CreateShifts();
?>
<?php include "../include/footer.php"; ?>