<?php
include "../include/header.php";
include "../include/db_connection.php";

function create_shifts($sn)
{
    // must be organised by servrer
    $sql = "SELECT DISTINCT `server` FROM `shift_records` WHERE `steam_name` = '$sn'";
    $result = fetchAll($sql);
    $sv = array();
    foreach ($result as $s) {
        $sv[] = $s[0]; // pull result into one dimension
    }
    $shifts = array();
    foreach ($sv as $svNo) {
        $sql = "SELECT * FROM `shift_records` WHERE (`steam_name` = '$sn' AND `server` = '$svNo' AND `signature` IS NULL) ORDER BY `id`";
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


if (isset($_GET['id'])) {

    $shifts = create_shifts($_GET['id']);
}


function display_selectbox($vals)
{


    if (count($vals->InTimes) > 1) {
        $constr = "<select class='custom-select' name='InTime'>";
        foreach ($vals->InRows as $index => $ins) {

            $constr .= "<option value='" . $vals->InRows[$index] . "'>" . toTime($vals->InTimes[$index]) . "</option>";
        }
    } else {
        $constr = "<select class='custom-select' name='InTime' disabled>";
        $constr .= "<option selected value='" . $vals->InRows[0] . "'>" . toTime($vals->InTimes[0]) . "</option>";
    }
    $constr .= "</select>";
    return $constr;
}

?>



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
    <tbody>
        <?php

        foreach ($shifts as $index => $s) {
            echo "<tr>";
            echo "<td>" . $s->Server . "</td>";
            echo "<td>" . toDateS($s->InTimes[0]) . "</td>";
            echo "<td>" . display_selectbox($s) . "</td>";
            echo "<td>" . toTime($s->OutTime) . "</td>";
            echo "<td>-</td>";
            echo "<td><input type='checkbox' name='chk[]'></td>";
            echo "</tr>";
        }

        ?>
    </tbody>
</table>



<?php include "../include/footer.php"; ?>