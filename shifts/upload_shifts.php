<?php include "../include/header.php";
include "../include/db_connection.php"; ?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="btn_submit" value="Upload File" />
</form>

<?php

function TrimRecords($lines)
{
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
            //echo print_r($record);
            //echo $record->steam_name;
            //echo $Author . ": " . $text . " (at " . $Datetime . " )";
            //echo "<br>";
        }
    }
    return $records;
    //var_dump($lines);
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
    while (($row = fgetcsv($fh, 100)) !== FALSE) {
        $lines[] = $row;
    }
    $records = TrimRecords($lines);
    DumpRawShiftDataToDB($records);
}


?>
<table class="table table-striped blue-header">
    <thead>
        <tr>
            <th>DateTime</th>
            <th>Server</th>
            <th>steam_name</th>
            <th>IO</th>
        </tr>
    </thead>
    <?php if (isset($records)) {
        foreach ($records as $r) {
            echo "<tr>";
            echo "<td>" . toDateTime($r->timestamp) . "</td>";
            echo "<td>" . $r->server . "</td>";
            echo "<td>" . $r->steam_name . "</td>";
            echo "<td>" . $r->io . "</td>";
            echo "</tr>";
        }
    } ?>
    <?php include "../include/footer.php"; ?>