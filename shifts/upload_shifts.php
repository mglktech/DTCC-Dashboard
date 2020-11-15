<?php include "../include/header.php";
/*
This page reads a CSV compilation of live-clockin-data and dumps it into shift_records SQL table, ignoring existing values.
Upon POST of a CSV file, it runs the following functions in order:

TrimRecords
DumpRawShiftDataToDB
CreateShifts

CreateShifts has added functionality to automatically reject "in" records that are more than 12 hours from the next "out".
It will be up to Senior Supervisors to validate each shift on an individual basis. if we feel that the pruned shift could not be valid, we will reject it by hand.
*/
include "../include/db_connection.php"; ?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="btn_submit" value="Upload File" />
</form>

<?php

function TrimRecords($lines)
{
    //var_dump($lines);
    $records = array();
    foreach ($lines as $line) {
        $author = $line[1];
        $datetime = strtotime($line[2]);
        $text = $line[3];
        if ($datetime > 1604102400) { // Epoch set to 31/10/2020 at 00:00:00
            $record = new stdClass();
            $record->timestamp = $datetime;
            $record->server = substr($author, 26, 1);
            $trim1 = explode(":", $text)[3];
            $record->steam_name = trim(explode("clocked", $trim1)[0]);
            $record->io = explode("**", $text)[1];
            $records[] = $record;
            // echo "<br>";
            // echo print_r($record);
            // echo $record->steam_name;
            // echo $author . ": " . $text . " (at " . $datetime . " )";
        }
    }
    return $records;
}



function DumpRawShiftDataToDB($records)
{
    $responses = array();
    foreach ($records as $key => $record) {
        $id = $key;
        $ts = $record->timestamp;
        $sv = $record->server;
        $sn = $record->steam_name;
        $io = $record->io;
        $sql = "INSERT IGNORE INTO `shift_records` (`id`,`timestamp`,`server`,`steam_name`,`io`) VALUES('$id','$ts','$sv','$sn','$io')";
        $responses[] = sqlRun($sql);
    }
    return $responses;
}



if (isset($_POST['btn_submit'])) {
    $fh = fopen($_FILES['file']['tmp_name'], 'r');

    $lines = array();
    $HeaderRow = fgetcsv($fh, 1000); // Grab header row and do nothing with it
    while (($row = fgetcsv($fh, 1000)) != FALSE) {
        $lines[] = $row;
        //echo "<br> Row = ";
        //print_r($row);
    }
    $records = TrimRecords($lines);
    DumpRawShiftDataToDB($records);
    CreateShifts();
}

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
            $sql = "SELECT * FROM `shift_records` WHERE (`steam_name` = '$sn[0]' AND `server` = '$s[0]' AND `signed_by` IS NULL) ORDER BY `id`";
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
        if ($io == "in") {
            $shift->InRows[] = $id;
            $shift->InTimes[] = $timestamp;
        }
        if ($io == "out") {
            $shift->OutTime = $timestamp;
            $shift->OutRow = $id;
            $pruned_shift = PruneShift($shift);
            $shifts[] = $pruned_shift;
            unset($shift->InRows);
            unset($shift->InTimes);
        }
    }
}
function PruneShift($shift)
{
    $ServerResetFrequency = 43200;
    $PrunedShift = new stdClass();
    $PrunedShift->InRows = array();
    $PrunedShift->InTimes = array();
    $RejectRows = array();
    if (isset($shift->InTimes)) {
        if (count($shift->InTimes) > 0) {
            foreach ($shift->InTimes as $ind => $in) {
                $compare = $shift->OutTime - $in;
                echo "COMPARE = " . $compare . "<br>";
                if ($compare < $ServerResetFrequency) // IF in is less than 12 hours from Out
                {
                    echo $shift->InRows[$ind] . " PASS <br>";
                    $PrunedShift->InRows[] = $shift->InRows[$ind];
                    $PrunedShift->InTimes[] = $in;
                } else {
                    echo $shift->InRows[$ind] . " FAIL <br>";
                    $RejectRows[] = $shift->InRows[$ind];
                }
            }
        } else { // if OutRow found with no InRows
            $sql = "UPDATE `shift_records` SET `signed_by`='Automatic',`outcome`='Reject',`reason`='Highlife_Server_Error' WHERE `id` = '$shift->OutRow'";
            sqlRun($sql);
        }
    } else {
        $sql = "UPDATE `shift_records` SET `signed_by`='Automatic',`outcome`='Reject',`reason`='Highlife_Server_Error' WHERE `id` = '$shift->OutRow'";
        sqlRun($sql);
    }
    // now deal with rejected rows
    foreach ($RejectRows as $r) {
        $sql = "UPDATE `shift_records` SET `signed_by`='Automatic',`outcome`='Reject',`reason`='>12hrsFromOut' WHERE `id` = '$r'";
        sqlRun($sql);
        //echo $sql . "<br>";
    }
    // now return pruned shifts
    return $PrunedShift;
}

?>
<!-- <table class="table table-striped blue-header">
    <thead>
        <tr>
            <th>DateTime</th>
            <th>Server</th>
            <th>steam_name</th>
            <th>IO</th>
        </tr>
    </thead>
    <tbody> -->
<?php
// if (isset($records)) {
//     foreach ($records as $r) {
//         echo "<tr>";
//         echo "<td>" . toDateTime($r->timestamp) . "</td>";
//         echo "<td>" . $r->server . "</td>";
//         echo "<td>" . $r->steam_name . "</td>";
//         echo "<td>" . $r->io . "</td>";
//         echo "</tr>";
//     }
// } 
?>
<!-- </tbody>
</table> -->
<?php include "../include/footer.php"; ?>