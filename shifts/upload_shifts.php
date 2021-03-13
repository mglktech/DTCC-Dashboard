<?php include "../include/components/head.php";


function read_file($file)
{
    //$fh = fopen($_FILES['file']['tmp_name'], 'r');
    $r_file = new StdClass();
    $r_file->head = fgetcsv($file, 1000); // fh
    $r_file->data = array();
    while (($row = fgetcsv($file, 1000)) != FALSE) { // fh
        $r_file->data[] = $row;
        //echo "<br> Row = ";
        //print_r($row);
    }
    //$records = TrimRecords($data);
    //DumpRawShiftDataToDB($records);
    return $r_file;
}

function create_clockin_data($data_line)
{
    $clockin_data = new StdClass();
    $clockin_data->server = substr($data_line[1], 26, 1);
    $clockin_data->timestamp = strtotime($data_line[2]);
    $message = explode(": ", $data_line[3]);
    $clockin_data->steam_name = trim(explode("(steam:", $message[1])[0]);
    $clockin_data->steam_id = hexdec(substr(explode("(steam:", $message[1])[1], 0, 15));
    $clockin_data->type = trim(explode("clocked ", $message[1])[1], "```");
    return $clockin_data;
}

function add_to_db($line)
{
    $resp = "";
    if (!record_exists($line)) {
        Query("INSERT INTO `clockin_data` (`server`,`timestamp`,`steam_name`,`steam_id`,`type`) VALUES ('$line->server','$line->timestamp','$line->steam_name','$line->steam_id','$line->type')");
        $resp = "Record for " . $line->steam_name . " successfully added to database.";
    } else {
        $resp = "Duplicate record for " . $line->steam_name . " detected. Skipping...";
    }
    return $resp;
}

function record_exists($line)
{
    $existing = QueryFirst("SELECT * FROM `clockin_data` WHERE `steam_id` = '$line->steam_id' AND `timestamp` = '$line->timestamp'");
    if ($existing) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['btn_submit'])) {

    $file = fopen($_FILES['file']['tmp_name'], 'r');
    $csv = read_file($file);
    foreach ($csv->data as $line) {
        $json = create_clockin_data($line);
        //echo json_encode($json) . "<br>";
        $resp = add_to_db($json);
        echo $resp . "<br>";
    }
}



?>
<div class="container">
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept=".csv">
        <input type="submit" name="btn_submit" value="Upload File" />
    </form>
</div>
<?php
include "../include/components/foot.php";
