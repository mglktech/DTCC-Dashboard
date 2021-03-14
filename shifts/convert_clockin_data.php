<?php include "../include/components/head.php";


/* clockin_data_obj {
    in_row = 2303;
    out_row = 2307;
    rejected_rows = [2304,2305,2306];    
    }

*/

function create_clockin_data_obj($row)
{
    $clockin_data = new StdClass();
    $clockin_data->in_row = $row->inRow;
    $clockin_data->out_row = $row->outRow;
    $clockin_data->rejected_rows = array();
    $str = $row->inRow . " selected";
    $rejected_rows = Query("SELECT `id` FROM `shift_records` WHERE `reason` = '$str'");
    if ($rejected_rows) {
        foreach ($rejected_rows as $r) {
            $clockin_data->rejected_rows[] = $r->id;
        }
    }

    return $clockin_data;
}

function create_verified_shift($row)
{
    $shift = new StdClass();
    $shift->id = $row->id;
    $shift->server = $row->server;
    $shift->steam_id = $row->steam_id;
    $shift->time_in = QueryFirst("SELECT `timestamp` FROM `clockin_data` WHERE `id` = '$row->inRow'")->timestamp;
    $shift->time_out = QueryFirst("SELECT `timestamp` FROM `clockin_data` WHERE `id` = '$row->outRow'")->timestamp;
    $shift->duration = $row->duration;
    $shift->signed_by = $row->signed_by;
    $shift->timestamp = $row->timestamp;
    $shift->clockin_data = create_clockin_data_obj($row);

    return $shift;
}
function convert_verified_shifts()
{
    $verified_shifts = Query("SELECT * FROM `verified_shifts` WHERE 1 ORDER BY `id` ASC");

    foreach ($verified_shifts as $row) {
        $exists = record_exists($row->id, "id", "_verified_shifts");
        if (!$exists) {
            $shift = create_verified_shift($row);
            $clockin_data_encoded = json_encode($shift->clockin_data);
            Query("INSERT INTO `_verified_shifts`(`id`,`server`,`steam_id`,`time_in`,`time_out`,`duration`,`signed_by`,`timestamp`,`clockin_data`) 
            VALUES ('$shift->id','$shift->server','$shift->steam_id','$shift->time_in','$shift->time_out','$shift->duration','$shift->signed_by','$shift->timestamp','$clockin_data_encoded')");
            echo "Added Shift " . $row->id . " <br>";
            sign_clockin_data($shift->id, $shift->clockin_data);
        } else {
            echo "Skipped Shift " . $row->id . "<br>";
        }
    }
}

function sign_clockin_data($shift_id, $clockin_data_obj)
{
    $sign = $clockin_data_obj->rejected_rows;
    $sign[] = $clockin_data_obj->in_row;
    $sign[] = $clockin_data_obj->out_row;
    foreach ($sign as $s) {
        Query("UPDATE `clockin_data` SET `verified_shift_id` = '$shift_id' WHERE `id` = '$s'");
    }
}

function convert_clockin_data()
{
    $shift_records = Query("SELECT * FROM `shift_records` WHERE 1 ORDER BY `id` ASC");
    // shift_records does not contain SteamID so we have to leave it out for now.
    foreach ($shift_records as $r) {
        $exists = record_exists($r->id, "id", "clockin_data");
        if (!$exists) {
            Query("INSERT INTO `clockin_data`(`id`,`timestamp`,`server`,`steam_name`,`type`) VALUES ('$r->id','$r->timestamp','$r->server','$r->steam_name','$r->io')");
            echo "Added " . $r->id . "<br>";
        } else {
            echo "Skipping " . $r->id . "<br>";
        }
    }
}

function record_exists($id, $id_name, $table_name)
{
    $r = QueryFirst("SELECT * FROM `$table_name` WHERE `$id_name` = '$id'");
    if ($r) {
        return true;
    } else {
        return false;
    }
}

convert_clockin_data();
//convert_verified_shifts();

?>

<?php include "../include/components/foot.php";
