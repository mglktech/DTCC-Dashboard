<?php include '../include/header.php';  ?>

<?php
include '../include/sqlconnection.php';

function getMetas($type, $ver)
{
    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'";
    $result = Query($sql)[0];
    $ret['pass_mark'] = $result->pass_mark;
    $ret['max_score'] = $result->max_score;
    return $ret;
}

function PassFail($ret, $score_percent)
{
    $pass_percent = (round($ret['pass_mark'] / $ret['max_score'], 2));
    //echo $score_percent . "/" . $pass_percent . "<br>";
    if ($score_percent >= $pass_percent) {
        return "PASS";
    } else {
        return "FAIL";
    }
}

$sql = "SELECT `steam_id`,`char_name`,`discord_name`,`phone_number`,`last_seen` FROM `players` WHERE `status` = 'Needs Theory' ORDER BY `last_seen`";
$applicants_theory = Query($sql);
//print_r($ranks_array);
// output data of each row

$sql = "SELECT `steam_id`,`char_name`,`discord_name`,`phone_number`,`last_seen` FROM `players` WHERE `status` = 'Needs Practical' ORDER BY `last_seen`";
$applicants_practical = Query($sql);

$sql = "SELECT * FROM `test_history` ORDER BY `submit_date` DESC";
$test_history = Query($sql);
/* test_history = 
student_name
type
version
score_percent
scores
signed_by
callsign
*/

?>
<h1>Tests</h1>
<h5 class="font-italic mb-3 font-weight-normal">look at all these idiots...</h5>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="theory-tab" data-toggle="tab" href="#theory" role="tab" aria-controls="home" aria-selected="true">
            <h5>Needs Theory</h5>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="practical-tab" data-toggle="tab" href="#practical" role="tab" aria-controls="profile" aria-selected="false">
            <h5>Needs Practical</h5>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="profile" aria-selected="false">
            <h5>Tests Archive</h5>
        </a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane show active" id="theory" role="tabpanel" aria-labelledby="theory-tab">

        <div class='container-flex'>
            <table class="table table-striped blue-header">
                <thead>
                    <tr>
                        <th>Player Name</th>
                        <th>Discord Name</th>
                        <th>Last Seen</th>
                        <th></th>
                    </tr>
                </thead>
                <?php
                if ($applicants_theory) {
                    foreach ($applicants_theory as $row) {
                        echo "<tr>";
                        echo "<td>" . $row->char_name . "</td>";
                        echo "<td>" . $row->discord_name . "</td>";
                        echo "<td>" . toDateS($row->last_seen) . "</td>";
                        echo "<td><a class='btn btn-primary' href='take_test.php?type=theory&steamid=" . $row->steam_id . "'>Take Test</button></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>

    </div>
    <div class="tab-pane fade" id="practical" role="tabpanel" aria-labelledby="practical-tab">
        <div class='container-flex'>
            <table class="table table-striped blue-header">
                <thead>
                    <tr>
                        <th>Player Name</th>
                        <th>Discord Name</th>
                        <th>Phone Number</th>
                        <th>Last Seen</th>
                        <th></th>
                    </tr>
                </thead>
                <?php
                if ($applicants_practical) {
                    foreach ($applicants_practical as $row) {
                        echo "<tr>";
                        echo "<td>" . $row->char_name . "</td>";
                        echo "<td>" . $row->discord_name . "</td>";
                        echo "<td>" . $row->phone_number . "</td>";
                        echo "<td>" . toDateS($row->last_seen) . "</td>";
                        echo "<td><a class='btn btn-primary' href='take_test.php?type=practical&&steamid=" . $row->steam_id . "'>Take Test</button></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
        <?php
        if ($test_history) { ?>
            <table class="table table-striped blue-header">
                <tr>
                    <th>Player Name</th>
                    <th>Type</th>
                    <th>Score</th>
                    <th>Signed By</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                <?php

                foreach ($test_history as $row) {
                    $ret = getMetas($row->type, $row->version);
                    /* $row = 
student_name
type
version
score_percent
scores
signed_by
callsign
*/

                    $super_line = $row->callsign . " | " . $row->signed_by;
                    echo "<tr>";
                    echo "<td>" . $row->student_name . "</td>";
                    echo "<td>" . $row->type . "</td>";
                    echo "<td>" . ($row->score_percent * 100) . "%</td>";
                    echo "<td>" . $super_line . "</td>";
                    echo "<td>" . PassFail($ret, $row->score_percent) . "</td>";
                    echo "<td><a class='btn btn-primary' href='view_test.php?test_id=" . $row->id . "'>View Test</button></td>";
                    echo "</tr>";
                } ?>
            </table>
        <?php } else {
            echo "Table is empty";
        }
        ?>
    </div>
</div>
<?php include '../include/footer.php';

function CreateTable($tableType)
{
    include_once 'include/db_connection.php';

    if ($tableType == "all") {
    }

    return $table;
}

?>