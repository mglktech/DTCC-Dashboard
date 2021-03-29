<?php
include "../include/components/head.php";


function create_clockin_data_obj($row)
{
    $clockin_data = new StdClass();
    $clockin_data->in_row = $row->inRow;
    $clockin_data->out_row = $row->outRow;
    $clockin_data->rejected_rows = array();
    if ($row->RejectedInRows) {
        foreach ($row->RejectedInRows as $r) {
            $clockin_data->rejected_rows[] = $r;
        }
    }

    return $clockin_data;
}

function POST_shiftdata($data) // $data = $verified_shifts
{
    //print_r($data);

    foreach ($data as $row) {
        $clockin_data = create_clockin_data_obj($row);
        if ($row->outcome == "Accept") {
            $server = $row->server;
            $steam_id = $row->steam_id;
            $time_in = QueryFirst("SELECT `timestamp` FROM `clockin_data` WHERE `id` = '$row->inRow'")->timestamp;
            $time_out = QueryFirst("SELECT `timestamp` FROM `clockin_data` WHERE `id` = '$row->outRow'")->timestamp;
            $duration = $row->duration;
            $signed_by = $row->signed_by;
            $timestamp = time();
            //$clockin_data = create_clockin_data_obj($row);
            $clockin_data_encoded = json_encode($clockin_data);
            $sql = "INSERT INTO _verified_shifts (`server`,`steam_id`,`time_in`,`time_out`,`duration`,`signed_by`,`timestamp`, `clockin_data`) 
            VALUES ('$server','$steam_id','$time_in','$time_out','$duration','$signed_by','$timestamp', '$clockin_data_encoded')";
            //echo "<br>" . $sql;
            Query($sql);
            _sign_record($clockin_data, $timestamp);
        } else {
            _sign_record($clockin_data, "-1");
        }
    }
    //echo "all done! <br>";
}

function _sign_record($clockin_data, $timestamp)
{
    $ver_shift_id = QueryFirst("SELECT `id` FROM `_verified_shifts` WHERE `timestamp` = '$timestamp'");
    if ($ver_shift_id) {
        $id = $ver_shift_id->id;
        Query("UPDATE `clockin_data` SET `verified_shift_id` = '$id' WHERE `id` = '$clockin_data->in_row'");
        Query("UPDATE `clockin_data` SET `verified_shift_id` = '$id' WHERE `id` = '$clockin_data->out_row'");
        foreach ($clockin_data->rejected_rows as $rejected_row) {
            Query("UPDATE `clockin_data` SET `verified_shift_id` = '$id' WHERE `id` = '$rejected_row'");
        }
    } else {
        Query("UPDATE `clockin_data` SET `verified_shift_id` = '-1' WHERE `id` = '$clockin_data->in_row'");
        Query("UPDATE `clockin_data` SET `verified_shift_id` = '-1' WHERE `id` = '$clockin_data->out_row'");
        foreach ($clockin_data->rejected_rows as $rejected_row) {
            Query("UPDATE `clockin_data` SET `verified_shift_id` = '-1' WHERE `id` = '$rejected_row'");
        }
    }
}

function sign_record($id, $sig, $outcome, $reason = NULL)
{
    $sql = "UPDATE shift_records SET `signed_by`='$sig',`outcome`='$outcome', `reason`='$reason' WHERE `id` = '$id'";
    //echo "<br>" . $sql;
    Query($sql);
}

// function _create_shifts($steam_id)
// {
//     // must be organised by server
//     $sql = "SELECT DISTINCT `server` AS `serv` FROM `clockin_data` WHERE `steam_id` = '$steam_id'";
//     $result = Query($sql);
//     $sv = array();
//     foreach ($result as $s) {
//         $sv[] = $s->serv; // pull result into one dimension
//     }

//     foreach ($sv as $svNo) {
//         $sql = "SELECT * FROM `clockin_data` WHERE (`steam_id` = '$steam_id' AND `server` = '$svNo' AND `verified_shift_id` IS NULL) ORDER BY `timestamp`";
//         $records = Query($sql);
//         // echo "<br> SN: " . $sn . " SERVER: " . $svNo . " RECORDS: ";
//         $shift = new stdClass();
//         $shift->InRows = array();
//         $shift->InTimes = array();
//         if ($records) {
//             foreach ($records as $r) {
//                 $id = $r->id;
//                 $timestamp = $r->timestamp;
//                 $shift->Server = $r->server;
//                 $shift->steam_id = $r->steam_id;
//                 $type = $r->type;
//                 if ($type == "in") {
//                     $shift->InRows[] = $id;
//                     $shift->InTimes[] = $timestamp;
//                 }
//                 if ($type == "out") {
//                     $shift->OutTime = $timestamp;
//                     $shift->OutRow = $id;
//                     // echo "<br>";
//                     // print_r($shift);
//                     array_push($shifts, create_shift($shift));
//                     //$shifts[] = $shift;
//                     //unset($shift->InRows);
//                     //unset($shift->InTimes);
//                     //array_splice($shift->InRows, 0);
//                     //array_splice($shift->InTimes, 0);
//                     $shift->InRows = array();
//                     $shift->InTimes = array();
//                 }
//             }
//         }
//     }
//     return $shifts;
// }




function create_shifts_sid($sid)
{
    // acquire SteamID of steam_name

    // must be organised by servrer
    $sql = "SELECT DISTINCT `server` AS serv FROM `clockin_data` WHERE `steam_id` = '$sid'";
    $result = Query($sql);
    $sv = array();
    foreach ($result as $s) {
        $sv[] = $s->serv; // pull result into one dimension
    }
    $shifts = array();
    foreach ($sv as $svNo) {
        $sql = "SELECT * FROM `clockin_data` WHERE (`steam_id` = '$sid' AND `server` = '$svNo' AND `verified_shift_id` IS NULL) ORDER BY `timestamp`";
        $records = Query($sql);
        // echo "<br> SN: " . $sn . " SERVER: " . $svNo . " RECORDS: ";

        $shift = new stdClass();
        $shift->InRows = array();
        $shift->InTimes = array();
        if ($records) {
            foreach ($records as $r) {
                $id = $r->id;
                $timestamp = $r->timestamp;
                $shift->Server = $r->server;
                $shift->steam_id = $r->steam_id;
                $io = $r->type;
                if ($io == "in") {
                    $shift->InRows[] = $id;
                    $shift->InTimes[] = $timestamp;
                }
                if ($io == "out") {
                    $shift->OutTime = $timestamp;
                    $shift->OutRow = $id;
                    // echo "<br>";
                    // print_r($shift);
                    array_push($shifts, create_shift($shift));
                    //$shifts[] = $shift;
                    //unset($shift->InRows);
                    //unset($shift->InTimes);
                    //array_splice($shift->InRows, 0);
                    //array_splice($shift->InTimes, 0);
                    $shift->InRows = array();
                    $shift->InTimes = array();
                }
            }
        }
    }


    // echo "<br> Organised Shifts: <br>";
    // foreach ($shifts as $sh) {
    //     print_r($sh);
    //     echo "<br>";
    // }
    return $shifts;

    //

}





function create_shift($_shift) // BULLSHIT code to unreference $shift->InRows and $shift->InTimes before they get cleared.
{
    $shift = new stdClass();
    $shift->Server = $_shift->Server;
    $shift->InRows = array();
    $shift->InTimes = array();
    $shift->InRows = $_shift->InRows;
    $shift->InTimes = $_shift->InTimes;
    $shift->OutRow = $_shift->OutRow;
    $shift->OutTime = $_shift->OutTime;

    return $shift;
}




if (isset($_GET['id'])) {

    $shifts = create_shifts_sid($_GET['id']);
    $steam_id = $_GET['id'];
}

if (isset($_POST["submit"])) {

    //echo "SUBMIT InRows = ";
    //print_r($_POST["InRows"]);
    //echo "<br> OutRows =";
    //print_r($_POST["OutRows"]);
    //echo "<br>";
    //print_r($_POST["chk"]);

    $verified_shifts = array();
    foreach ($_POST["OutRows"] as $index => $out) {
        $verified_shift = new stdClass();
        $verified_shift->steam_id = $_POST["steam_id"];
        $verified_shift->signed_by = $_SESSION['steam_id'];
        $verified_shift->server = $_POST["server"][$index];
        $verified_shift->inRow = $_POST["InRows"][$index];
        $verified_shift->outRow = $_POST["OutRows"][$index];
        $verified_shift->duration = $_POST["duration"][$index];
        $decode = json_decode($_POST["AllInRows"][$index]);
        $verified_shift->RejectedInRows = array_diff($decode, array($_POST["InRows"][$index]));
        //echo "<br>RejectedInRows = ";
        //print_r($verified_shift->RejectedInRows);
        if (in_array($index, $_POST["chk"])) {
            $verified_shift->outcome = "Accept";
            //echo "<br>InRow: " . $_POST["InRows"][$index] . " | OutRow: " . $_POST["OutRows"][$index] . " APPROVE";
        } else {

            $verified_shift->outcome = "Reject";
            //echo "<br>InRow: " . $_POST["InRows"][$index] . " | OutRow: " . $_POST["OutRows"][$index] . " REJECT";
        }
        $verified_shifts[] = $verified_shift;
    }
    POST_shiftdata($verified_shifts);
    echo "<div class='container text-center'><h5>Done!</h5><br><a class='btn btn-outline-secondary' href='table_unver_shifts.php'>Back</a></div>";
}


function display_selectbox($vals)
{
    echo "<input name='AllInRows[]' form='ThisForm' value='" . json_encode($vals->InRows) . "' hidden>";

    if (count($vals->InTimes) > 1) {
        $constr = "<select class='custom-select' name='InRows[]' form='ThisForm' onchange='updateDurations()'>";
        foreach ($vals->InRows as $index => $ins) {

            $constr .= "<option value='" . $vals->InRows[$index] . "'>" . toTime($vals->InTimes[$index]) . "</option>";
        }
    } else {
        $constr = "<input type='hidden' name='InRows[]' form='ThisForm' value='" . $vals->InRows[0] . "'>";
        $constr .= "<select class='custom-select' name='InRows[]' form='ThisForm' onchange='updateDurations()' disabled >";
        $constr .= "<option selected value='" . $vals->InRows[0] . "'>" . toTime($vals->InTimes[0]) . "</option>";
    }

    $constr .= "</select>";

    return $constr;
}

?>

<?php if (isset($shifts)) { ?>
<div class="container text-center">
    <h3>Unverified Shifts: <?php echo q_fetchPlayerFormatted($_GET['id']) ?></h3>
    <h6>Shifts over 3hrs will not be checked automatically. You must manually select the correct clock-in time before
        submitting.</h6>
    <h6>Shifts under 10 minutes should also be left unchecked.</h6>
    <table class="table table-striped blue-header">
        <thead>
            <tr>
                <th>Server</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Duration</th>
                <th>Verify?</th>
            </tr>
        </thead>
        <tbody id="TableBody">
            <?php

                foreach ($shifts as $index => $s) {
                    echo "<tr>";
                    echo "<td>" . $s->Server . "</td>";
                    echo "<td>" . toDateS($s->InTimes[0]) . "</td>";
                    echo "<td>" . display_selectbox($s) . "</td>";
                    echo "<td>" . toTime($s->OutTime) . "</td>";
                    echo "<td>-</td>"; // duration cell
                    echo "<td><input type='checkbox' class= 'chk' name='chk[]' form='ThisForm' value='" . $index . "' checked></td>";
                    echo "</tr>";
                    echo "<input class='inTimes'  value='" . json_encode($s->InTimes) . "' hidden>";
                    echo "<input class='outTime' value='" . $s->OutTime . "' hidden>";
                    echo "<input name='duration[]' class='duration' form='ThisForm' hidden>";
                    echo "<input name='server[]' form='ThisForm' value='" . $s->Server . "' hidden>";
                    echo "<input name='OutRows[]' form='ThisForm' value='" . $s->OutRow . "' hidden>";
                }
                ?>
        </tbody>
    </table>
    <form id="ThisForm" action="verify_shifts.php" method="post">
        <input name='steam_id' value="<?php echo $steam_id ?>" hidden>
        <button name="submit" type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
<?php } ?>

<script>
    document.addEventListener("DOMContentLoaded", updateDurations());

    function format(time) {
        // Hours, minutes and seconds
        var hrs = ~~(time / 3600);
        var mins = ~~((time % 3600) / 60);
        var secs = ~~time % 60;

        // Output like "1:01" or "4:03:59" or "123:03:59"
        var ret = "";
        if (hrs > 0) {
            ret += hrs + " hrs, ";
        }
        ret += mins + " mins, ";
        ret += secs + " secs";
        return ret;
    }

    function updateDurations() {
        var TableBody = document.getElementById("TableBody");
        for (var i = 0; i < TableBody.getElementsByTagName("tr").length; i++) {
            var tRow = TableBody.getElementsByTagName("tr")[i];
            var tComboCell = document.getElementsByClassName("custom-select")[i];
            var tDurationInput = document.getElementsByClassName("duration")[i];
            var tComboCellIndex = tComboCell.selectedIndex;
            var tDurationCell = tRow.getElementsByTagName("td")[4];
            var tCheckbox = document.getElementsByClassName("chk")[i];
            var TimeIn = JSON.parse(document.getElementsByClassName('inTimes')[i].value)[tComboCellIndex];
            var TimeOut = document.getElementsByClassName('outTime')[i].value;
            //console.log("INTIME: " + TimeIn);
            //console.log("OUTTIME: " + TimeOut);
            var duration = TimeOut - TimeIn;
            if (duration > (3600 * 3) || duration < (3600 / 6)) { // if over 3hrs or under 10mins, uncheck automatically
                tCheckbox.checked = false;
                //console.log("CHECKBOX");
            }
            //console.log("DURATION: " + format(duration));
            var dataDuration = format(duration);
            tDurationInput.value = duration;
            tDurationCell.innerHTML = dataDuration;

        }

    }

    function updateDurations_old() {
        var TableBody = document.getElementById("TableBody");
        for (var i = 0; i < TableBody.getElementsByTagName("tr").length; i++) {
            var tRow = TableBody.getElementsByTagName("tr")[i];
            var tComboCell = document.getElementsByClassName("custom-select")[i];
            var tDurationInput = document.getElementsByClassName("duration")[i];
            var tComboCellIndex = tComboCell.selectedIndex;
            var tDurationCell = tRow.getElementsByTagName("td")[4];
            var TimeIn = JSON.parse(document.getElementsByClassName('inTimes')[i].value)[tComboCellIndex];
            var TimeOut = document.getElementsByClassName('outTime')[i].value;
            //console.log("INTIME: " + TimeIn);
            //console.log("OUTTIME: " + TimeOut);
            var duration = TimeOut - TimeIn;
            var tmath = new Date(duration * 1000);
            var dataDuration = tmath.getUTCHours() + "hrs " + tmath.getUTCMinutes() + "m ";
            //console.log("DURATION: ")
            tDurationInput.value = duration;
            tDurationCell.innerHTML = dataDuration;
        }
    }
</script>

<?php "../include/components/foot.php"; ?>