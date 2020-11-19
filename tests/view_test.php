<?php include '../include/header.php';
include "../include/elements.php";

include_once '../include/db_connection.php';

if (isset($_POST["test_type"])) {
    if ($_POST["test_type"] == "theory") {
        $rvals = POST_Theory();
    }
    if ($_POST["test_type"] == "practical") {
        $rvals = POST_Practical();
    }
}
if (isset($_GET["test_id"])) {
    $rvals = Get_Test($_GET["test_id"]);
}

function getPlayer()
{
    

}

function Get_Test($test_id)
{

    $sql = "SELECT * FROM `tests` WHERE `id` = '$test_id'";
    $result = fetchRow($sql);
    $student_steamid = $result[2];
    $comments = $result[9];
    $total_score = $result[5];
    $scores = explode("/", $result[7]);
    //echo print_r($result[7]);
    $test_type = $result[3];
    $test_ver = $result[4];
    $signed_by = $result[8];
    $metas = getMetas($test_type, $test_ver);
    $score_percent = $result[6];
    $char = fetchPlayer($student_steamid);
    $char_name = $char[1];
    $callsign = $char[0];
    $discord_name = $char[5];
    $postret['char_name'] = $char_name;
    $postret['max_score'] = $metas['max_score'];
    $postret['total_score'] = $total_score;
    $postret['pass_mark'] = $metas['pass_mark'];
    $postret['percentage'] = $score_percent;
    $postret['Answers'] = $scores;
    $postret['test_type'] = $test_type;
    $postret['discord_name'] = $discord_name;
    $postret['comments'] = $comments;
    if ($test_type != 'theory') {
        $postret['hex'] = "steam:" . dechex($student_steamid);
    }

    $postret['callsign'] = $callsign;
    $postret['signed_by'] = fetchPlayerFormatted($signed_by);

    return $postret;
}

function getQuestions($tbl)
{

    if ($tbl == "theory") {
        $sql = "SELECT `question` FROM `theory_test_data_v0`";
    }
    if ($tbl == "practical") {
        $sql = "SELECT `question_title` FROM `practical_test_data_v0`";
    }
    if ($sql) {
        return fetchAll($sql);
    } else {
        return null;
    }
}
function getMetas($type, $ver)
{
    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'";
    $result = fetchRow($sql);
    $ret['pass_mark'] = $result[0];
    $ret['max_score'] = $result[1];
    return $ret;
}

function POST_Theory()
{
    $date = time();
    $steamid = $_POST["steamid"];
    $char_name = $_POST["char_name"];
    $comments = $_POST['comments'];
    $signed_by = $_SESSION["steam_id"];
    $total_score = 0;
    $score_string = "";
    foreach ($_POST["A"] as $answer) {
        //echo $answer . "<br>";
        $score_string .= $answer . "/";
        $total_score += $answer;
    }
    $metas = getMetas('theory', '0');
    $pass_mark = $metas['pass_mark'];
    $max_score = $metas['max_score'];
    $percentage = round(($total_score / $max_score), 2);

    if ($total_score >= $pass_mark) {

        //echo "congrats, you passed!";
        $sql = "UPDATE `players`
         SET `status`='Needs Practical', `last_seen`='$date'
         WHERE `steam_id`='$steamid'";
        $response = SqlRun($sql);
        //echo "Player Database Response: " . $response;
    }
    if ($total_score < $pass_mark) {
        //echo "You Failed.";
        $sql = "UPDATE `players`
        SET `last_seen`='$date'
        WHERE `steam_id`='$steamid'";
    }

    $sql = "INSERT INTO `tests`(`steam_id`, `type`, `version`, `score_total`, `score_percent`, `signed_by`,`scores`,`submit_date`,`comments`) VALUES ('$steamid','theory','0','$total_score','$percentage','$signed_by','$score_string','$date','$comments')";
    $response = SqlRun($sql);
    //echo $response . " SQL: " . $sql;
    $postret['char_name'] = $char_name;
    $postret['max_score'] = $max_score;
    $postret['total_score'] = $total_score;
    $postret['pass_mark'] = $pass_mark;
    $postret['percentage'] = $percentage;
    $postret['Answers'] = $_POST['A'];
    $postret['test_type'] = $_POST['test_type'];
    $postret['callsign'] = "Not Assigned";
    $postret['signed_by'] = fetchPlayerFormatted($signed_by);
    $postret['comments'] = $comments;

    return $postret;
}

function POST_Practical()
{
    //assign callsign
    $callsign = $_POST['callsign'];
    //echo $callsign;
    $date = time();
    $steamid = $_POST["steamid"];
    $comments = $_POST['comments'];
    $char_name = $_POST["char_name"];
    $signed_by = $_SESSION["steam_id"];
    $total_score = 0;
    $score_string = "";
    foreach ($_POST["A"] as $answer) {
        //echo $answer . "<br>";
        $score_string .= $answer . "/";
        $total_score += $answer;
    }



    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='practical' AND `version`='0'";
    $result = fetchRow($sql);
    $pass_mark = $result[0];
    $max_score = $result[1];
    $percentage = round(($total_score / $max_score), 2);


    if ($total_score >= $pass_mark) {

        //echo "congrats, you passed!";
        $sql = "UPDATE `players`
         SET `status`='Active', `last_seen`='$date' , `rank`='0'
         WHERE `steam_id`='$steamid'";
        $response = SqlRun($sql);
        //echo "Player Database Response: " . $response;
        $sql = "UPDATE `callsigns`
        SET `assigned_steam_id` = '$steamid'
        WHERE `label` = '$callsign'";
        $response = SqlRun($sql);
        if ($response = 'failure') {
            $sql = "SELECT `label` FROM `callsigns` WHERE `assigned_steam_id` = '$steamid'";
            $response = fetchRow($sql);
            $callsign = $response[0];
        }
    }

    if ($total_score < $pass_mark) {
        $sql = "UPDATE `players`
        SET `last_seen`='$date'
        WHERE `steam_id`='$steamid'";
        //echo "You Failed.";
    }
    $sql = "INSERT INTO `tests`(`steam_id`, `type`, `version`, `score_total`, `score_percent`, `signed_by`,`scores`,`submit_date`,`comments`) VALUES ('$steamid','practical','0','$total_score','$percentage','$signed_by','$score_string','$date','$comments')";
    $response = SqlRun($sql);
    //echo "Tests Database Response: " . $response;
    $char = fetchPlayer($steamid);
    $postret['char_name'] = $char_name;
    $postret['max_score'] = $max_score;
    $postret['total_score'] = $total_score;
    $postret['pass_mark'] = $pass_mark;
    $postret['percentage'] = $percentage;
    $postret['Answers'] = $_POST['A'];
    $postret['test_type'] = $_POST['test_type'];
    $postret['hex'] = "steam:" . dechex($steamid);
    $postret['callsign'] = $callsign;
    $postret['signed_by'] = fetchPlayerFormatted($signed_by);
    $postret['discord_name'] = $char[5];
    $postret['comments'] = $comments;
    return $postret;
}

function passed($total_score, $pass_mark)
{
    if ($total_score >= $pass_mark) {
        return true;
    } else {
        return false;
    }
}

function CreatePassFail($total_score, $pass_mark)
{
    if ($total_score >= $pass_mark) {

        return "<input class='form-control bg-success text-light font-weight-bold' value='PASS' disabled>";
    } else {
        return "<input class='form-control bg-danger text-light font-weight-bold' value='FAIL' disabled>";
    }
}

function pickBGCol($num)
{
    if ($num <= 1) {
        return "bg-danger";
    }
    if ($num > 1 && $num < 5) {
        return "bg-warning";
    }
    if ($num == 5) {
        return "bg-success";
    }
}

function pickTWeight($num)
{
    if ($num <= 1) {
        return "font-weight-bold";
    }
    if ($num > 1 && $num < 5) {
        return "font-weight-normal";
    }
    if ($num == 5) {
        return "font-weight-light";
    }
}

function CreateQuestionElement($id, $question, $score)
{

    $vis_id = $id + 1;

    echo "<div class='container border mt-1 mb-1 " . pickBGCol($score) . "'>";
    echo "<div class='row mb-3'>";
    echo "<div class='col'>";
    echo "<h6>" . $vis_id . ". " . $question[0] . "</h6>";
    echo "<h6>Score: " . $score . "</h6>";
    echo "</div></div></div>";
}


?>
<div class="col-md-6 text-center">

</div>
<div class="container-fluid">
    <div class="row">
        <h1>Test Results</h1>
        <h5 class="w-100 font-italic mb-3 font-weight-normal">did this butthole pass?</h5>
        <div class="row w-100">
            <div class="col-md-12">
                <?php
                if (isset($rvals['hex']) && passed($rvals['total_score'], $rvals['pass_mark'])) { ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php CreateInputElem("Callsign", $rvals["callsign"], ""); ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            $link = new stdClass();
                            $link->label = "Whitelist";
                            $link->href = "https://highliferoleplay.net/whitelisting/index.php";
                            CreateInputBtnElem("Steam Hex:", $rvals['hex'], $link);
                            ?>

                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-6">
                        <?php CreateInputElem("Student Name:", $rvals['char_name'], ""); ?>
                    </div>
                    <div class="col-md-6">
                        <?php CreateInputElem("Signed By: ", $rvals["signed_by"], ""); ?>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?php CreateInputElem("Test Type", $rvals["test_type"], "v0"); ?>
                    </div>
                    <div class="col-md-6">
                        <?php CreateRichInputElem("Test Result:", CreatePassFail($rvals['total_score'], $rvals['pass_mark']), ($rvals['percentage'] * 100) .  "%"); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php CreateInputElem("Total Score:", $rvals['total_score'], "/ " . $rvals['max_score']); ?>
                    </div>
                    <div class="col-md-6">
                        <?php CreateInputElem("Pass Mark:", $rvals['pass_mark'] . "/ " . $rvals['max_score'], (round($rvals['pass_mark'] / $rvals['max_score'], 2) * 100) . "%"); ?>
                    </div>
                </div>
                <table class="table table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Questions</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $questions = getQuestions($rvals['test_type']);
                        foreach ($questions as $key => $q) {
                            $score = $rvals['Answers'][$key];
                            echo "<tr>";
                            echo "<td class='font-weight-bold'>" . ($key + 1) . "</td>";
                            echo "<td class='" . pickTWeight($score) . "'>" . $q[0] . "</td>";
                            echo "<td class='" . pickBGCol($score) . "'>" . $score . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php if (isset($rvals['hex']) && passed($rvals['total_score'], $rvals['pass_mark'])) {
                include "../include/inc_view_test_fullpass.php";
            } ?>


        </div>
        <div class="container-fluid">
            <h5 class="mt-3 mb-1">Comments</h5>
            <div class="border p-4">
                <span class="font-weight-normal"><?php echo $rvals['comments']; ?></span>
            </div>
        </div>
        <a class="btn btn-secondary mb-5 btn-large mt-5" href="table_tests.php">Done</a>
    </div>
</div>