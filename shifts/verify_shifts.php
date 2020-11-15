<?php
include "../include/header.php";
include "../include/db_connection.php";

function POST_shiftdata($data)
{
    print_r($data);
    $timestamp = time();
    foreach ($data as $row) {
        if ($row->outcome == "Accept") {

            $sql = "INSERT INTO `verified_shifts` (`server`,`steam_id`,`inRow`,`outRow`,`duration`,`signed_by`,`timestamp`) VALUES ('$row->server','$row->steam_id','$row->inRow','$row->outRow','$row->duration','$row->signed_by','$timestamp')";
            echo "<br>" . $sql;
            sqlRun($sql);
        }
        sign_record($row->inRow, $row->signed_by, $row->outcome);
        sign_record($row->outRow, $row->signed_by, $row->outcome);

        foreach ($row->RejectedInRows as $rej) {
            sign_record($rej, $row->signed_by, "Reject", $row->inRow . " selected");
        }
    }
}

function sign_record($id, $sig, $outcome, $reason = NULL)
{
    $sql = "UPDATE `shift_records` SET `signed_by`='$sig',`outcome`='$outcome', `reason`='$reason' WHERE `id` = '$id'";
    echo "<br>" . $sql;
    sqlRun($sql);
}

function create_shifts($sn)
{
    // acquire SteamID of steam_name

    // must be organised by servrer
    $sql = "SELECT DISTINCT `server` FROM `shift_records` WHERE `steam_name` = '$sn'";
    $result = fetchAll($sql);
    $sv = array();
    foreach ($result as $s) {
        $sv[] = $s[0]; // pull result into one dimension
    }
    $shifts = array();
    foreach ($sv as $svNo) {
        $sql = "SELECT * FROM `shift_records` WHERE (`steam_name` = '$sn' AND `server` = '$svNo' AND `signed_by` IS NULL) ORDER BY `timestamp`";
        $records = fetchAll($sql);
        // echo "<br> SN: " . $sn . " SERVER: " . $svNo . " RECORDS: ";

        $shift = new stdClass();
        $shift->InRows = array();
        $shift->InTimes = array();
        foreach ($records as $r) {
            $id = $r[0];
            $timestamp = $r[1];
            $shift->Server = $r[2];
            $shift->steam_name = $r[3];
            $io = $r[4];
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

function get_steamid($sn)
{
    $sql = "SELECT `steam_id` FROM `players` WHERE `steam_name` = '$sn'";
    return fetchRow($sql)[0];
}


if (isset($_GET['id'])) {

    $shifts = create_shifts($_GET['id']);
    $steam_id = get_steamid($_GET['id']);
}

if (isset($_POST["submit"])) {

    //echo "SUBMIT InRows = ";
    //print_r($_POST["InRows"]);
    //echo "<br> OutRows =";
    //print_r($_POST["OutRows"]);
    //echo "<br>";
    //print_r($_POST["chk"]);
    $score_string = "";
    foreach ($_POST["chk"] as $answer) {
        //echo $answer . "<br>";

        $score_string .= $answer . "/";

        //$total_score += $answer;
    }
    //echo $score_string;
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
        echo "<br>RejectedInRows = ";
        print_r($verified_shift->RejectedInRows);
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

    <h3>Unverified Shifts: <?php echo $_GET['id'] ?></h3>
    <table class="table table-striped blue-header">
        <thead>
            <tr>
                <th>Server</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Duration</th>
                <th></th>
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
                echo "<td><input type='checkbox' name='chk[]' form='ThisForm' value='" . $index . "' checked></td>";
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
<?php } ?>
<script>
    document.addEventListener("DOMContentLoaded", updateDurations());

    function updateDurations() {
        var TableBody = document.getElementById("TableBody");
        for (var i = 0; i < TableBody.getElementsByTagName("tr").length; i++) {
            var tRow = TableBody.getElementsByTagName("tr")[i];
            var tComboCell = document.getElementsByClassName("custom-select")[i];
            var tDurationInput = document.getElementsByClassName("duration")[i];
            var tComboCellIndex = tComboCell.selectedIndex;
            var tDurationCell = tRow.getElementsByTagName("td")[4];
            var TimeIn = JSON.parse(document.getElementsByClassName('inTimes')[i].value)[tComboCellIndex];
            var TimeOut = document.getElementsByClassName('outTime')[i].value;
            console.log("INTIME: " + TimeIn);
            console.log("OUTTIME: " + TimeOut);
            var duration = TimeOut - TimeIn;
            var tmath = new Date(duration * 1000);
            var dataDuration = tmath.getUTCHours() + "hrs " + tmath.getUTCMinutes() + "m ";
            tDurationInput.value = duration;
            tDurationCell.innerHTML = dataDuration;
        }
    }
</script>

<?php include "../include/footer.php"; ?>