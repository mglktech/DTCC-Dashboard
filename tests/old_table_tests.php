<?php include '../include/components/head.php';  ?>

<?php
include '../include/db_connection.php';

function getMetas($type, $ver)
{
    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'";
    $result = fetchRow($sql);
    $ret['pass_mark'] = $result[0];
    $ret['max_score'] = $result[1];
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
$applicants_theory = fetchAll($sql);
//print_r($ranks_array);
// output data of each row

$sql = "SELECT `steam_id`,`char_name`,`discord_name`,`phone_number`,`last_seen` FROM `players` WHERE `status` = 'Needs Practical' ORDER BY `last_seen`";
$applicants_practical = fetchAll($sql);

$sql = "SELECT * FROM `test_history` ORDER BY `submit_date` DESC";
$test_history = fetchAll($sql);
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
                        echo "<td>" . $row[1] . "</td>";
                        echo "<td>" . $row[2] . "</td>";
                        echo "<td>" . toDateS($row[4]) . "</td>";
                        echo "<td><a class='btn btn-primary' href='take_test.php?type=theory&steamid=" . $row[0] . "'>Take Test</button></td>";
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
                        echo "<td>" . $row[1] . "</td>";
                        echo "<td>" . $row[2] . "</td>";
                        echo "<td>" . $row[3] . "</td>";
                        echo "<td>" . toDateS($row[4]) . "</td>";
                        echo "<td><a class='btn btn-primary' href='take_test.php?type=practical&&steamid=" . $row[0] . "'>Take Test</button></td>";
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
                    $ret = getMetas($row[2], $row[3]);
                    /* $row = 
student_name
type
version
score_percent
scores
signed_by
callsign
*/

                    $super_line = $row[7] . " | " . $row[6];
                    echo "<tr>";
                    echo "<td>" . $row[1] . "</td>";
                    echo "<td>" . $row[2] . "</td>";
                    echo "<td>" . ($row[4] * 100) . "%</td>";
                    echo "<td>" . $super_line . "</td>";
                    echo "<td>" . PassFail($ret, $row[4]) . "</td>";
                    echo "<td><a class='btn btn-primary' href='view_test.php?test_id=" . $row[0] . "'>View Test</button></td>";
                    echo "</tr>";
                } ?>
            </table>
        <?php } else {
            echo "Table is empty";
        }
        ?>
    </div>
</div>
<?php include '../include/components/foot.php';

function CreateTable($tableType)
{
    include_once 'include/db_connection.php';

    if ($tableType == "all") {
    }

    return $table;
}

?>