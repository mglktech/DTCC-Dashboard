<?php include '../include/components/head.php';
include "../include/elements.php";



if (isset($_POST["test_type"])) {
    if ($_POST["test_type"] == "theory") {
        $rvals = POST_Theory();
    }
    if ($_POST["test_type"] == "practical") {
        $rvals = POST_Practical();
    }
}
if (isset($_GET["test_id"])) {
    $doc_id = $_GET["test_id"];
    $doc_type = "test";
    $rvals = Get_Test($_GET["test_id"]);
}

function update_callsign($id,$callsign)
{
    
}

function getPlayer()
{ // code updated
}

function Get_Test($test_id)
{

    $sql = "SELECT * FROM `tests` WHERE `id` = '$test_id'";
    $result = Query($sql)[0];
    //print_r($result);
    $student_steamid = $result->steam_id;
    $comments = $result->comments;
    $total_score = $result->score_total;
    $scores = explode("/", $result->scores);
    //echo print_r($result[7]);
    $test_type = $result->type;
    $test_ver = $result->version;
    $signed_by = $result->signed_by;
    $metas = getMetas($test_type, $test_ver);
    $score_percent = $result->score_percent;
    $char = q_fetchPlayer($student_steamid);
    $char_name = $char->char_name;
    $callsign = $char->callsign;
    $discord_name = $char->discord_name;
    $postret['char_name'] = $char_name;
    $postret['ver'] = $test_ver;
    $postret['max_score'] = $metas->max_score;
    $postret['total_score'] = $total_score;
    $postret['pass_mark'] = $metas->pass_mark;
    $postret['percentage'] = $score_percent;
    $postret['Answers'] = $scores;
    $postret['test_type'] = $test_type;
    $postret['discord_name'] = $discord_name;
    $postret['comments'] = $comments;
    if ($test_type != 'theory') {
        $postret['hex'] = "steam:" . dechex($student_steamid);
    }

    $postret['callsign'] = $callsign;
    $postret['signed_by'] = q_fetchPlayerFormatted($signed_by);

    return $postret;
}

function getQuestions($tbl, $ver)
{
    $sql = "SELECT question, max_points FROM test_questions WHERE type = '$tbl' and version = '$ver' ORDER BY q_number";


    if ($sql) {
        return Query($sql);
    } else {
        return null;
    }
}
function getMetas($type, $ver)
{
    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'";
    $result = Query($sql)[0];
    return $result;
}

function POST_Theory()
{
    $date = time();
    $steamid = $_POST["steamid"];
    $char_name = $_POST["char_name"];
    $comments = quotefix($_POST['comments']);
    $signed_by = $_SESSION["steam_id"];
    $total_score = 0;
    $score_string = "";
    foreach ($_POST["A"] as $answer) {
        //echo $answer . "<br>";
        $score_string .= $answer . "/";
        $total_score += $answer;
    }
    $metas = getMetas('theory', '1');
    $pass_mark = $metas->pass_mark;
    $max_score = $metas->max_score;
    $percentage = round(($total_score / $max_score), 2);

    if ($total_score >= $pass_mark) {

        //echo "congrats, you passed!";
        $sql = "UPDATE players
        SET `status`='Needs Practical', `last_seen`='$date'
        WHERE `steam_id`='$steamid'";
        $response = Query($sql);
        //echo "Player Database Response: " . $response;
    }
    if ($total_score < $pass_mark) {
        //echo "You Failed.";
        $sql = "UPDATE players
        SET `last_seen`='$date'
        WHERE `steam_id`='$steamid'";
    }

    $sql = "INSERT INTO tests (`steam_id`, `type`, `version`, `score_total`, `score_percent`, `signed_by`,`scores`,`submit_date`,`comments`) VALUES ('$steamid','theory','1','$total_score','$percentage','$signed_by','$score_string','$date','$comments')";
    $response = Query($sql);
    //echo $response . " SQL: " . $sql;
    $postret['char_name'] = $char_name;
    $postret['max_score'] = $max_score;
    $postret['total_score'] = $total_score;
    $postret['pass_mark'] = $pass_mark;
    $postret['percentage'] = $percentage;
    $postret['Answers'] = $_POST['A'];
    $postret['test_type'] = $_POST['test_type'];
    $postret['callsign'] = "Not Assigned";
    $postret['signed_by'] = q_fetchPlayerFormatted($signed_by);
    $postret['comments'] = $comments;
    $postret['ver'] = 1;

    return $postret;
}

function POST_Practical()
{
    //assign callsign
    //$callsign = $_POST['callsign'];
    //echo $callsign;
    $date = time();
    $steamid = $_POST["steamid"];
    $comments = quotefix($_POST['comments']);
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
    $result = Query($sql)[0];
    $pass_mark = $result->pass_mark;
    $max_score = $result->max_score;
    $percentage = round(($total_score / $max_score), 2);


    if ($total_score >= $pass_mark) {

        //echo "congrats, you passed!";
        $sql = "UPDATE players
        SET `status`='Active', `last_seen`='$date' , `rank`='0'
        WHERE `steam_id`='$steamid'";
        $response = Query($sql);
        //echo "Player Database Response: " . $response;
        $sql = "UPDATE callsigns
        SET `assigned_steam_id` = '$steamid'
        WHERE `label` = '$callsign'";
        $response = Query($sql);
        if ($response = 'failure') {
            $sql = "SELECT label FROM `callsigns` WHERE `assigned_steam_id` = '$steamid'";
            $response = Query($sql)[0];
            $callsign = $response->label;
        }
    }

    if ($total_score < $pass_mark) {
        $sql = "UPDATE players
        SET `last_seen`='$date'
        WHERE `steam_id`='$steamid'";
        //echo "You Failed.";
    }
    $sql = "INSERT INTO tests (`steam_id`, `type`, `version`, `score_total`, `score_percent`, `signed_by`,`scores`,`submit_date`,`comments`) VALUES ('$steamid','practical','0','$total_score','$percentage','$signed_by','$score_string','$date','$comments')";
    $response = Query($sql);
    //echo "Tests Database Response: " . $response;
    $char = q_fetchPlayer($steamid);
    $postret['char_name'] = $char_name;
    $postret['max_score'] = $max_score;
    $postret['total_score'] = $total_score;
    $postret['pass_mark'] = $pass_mark;
    $postret['percentage'] = $percentage;
    $postret['Answers'] = $_POST['A'];
    $postret['test_type'] = $_POST['test_type'];
    $postret['hex'] = "steam:" . dechex($steamid);
    $postret['callsign'] = $callsign;
    $postret['signed_by'] = q_fetchPlayerFormatted($signed_by);
    $postret['discord_name'] = $char[5];
    $postret['comments'] = $comments;
    $postret['ver'] = 0;
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

function pickBGCol($num, $max)
{
    $perc = $num / $max;
    if ($perc <= 0.33) {
        return "bg-danger";
    }
    if ($perc > 0.33 && $perc < 1) {
        return "bg-warning";
    }
    if ($perc == 1) {
        return "bg-success";
    }
}

function pickTWeight($num, $max)
{
    $perc = $num / $max;
    if ($perc <= 0.33) {
        return "font-weight-bold";
    }
    if ($perc > 0.33 && $perc < 1) {
        return "font-weight-normal";
    }
    if ($perc == 1) {
        return "font-weight-light";
    }
}

// function CreateQuestionElement($id, $question, $score)
// {

//     $vis_id = $id + 1;

//     echo "<div class='container border mt-1 mb-1 " . pickBGCol($score) . "'>";
//     echo "<div class='row mb-3'>";
//     echo "<div class='col'>";
//     echo "<h6>" . $vis_id . ". " . $question[0] . "</h6>";
//     echo "<h6>Score: " . $score . "</h6>";
//     echo "</div></div></div>";
// }

$char_name = $rvals['char_name'];
?>

<div class="container">
    <div class="row">
        <h1>Test Results</h1>
        <h5 class="w-100 font-italic mb-3 font-weight-normal">did this butthole pass?</h5>

        <div class="col-md-12 px-0">
            <?php
            if (isset($rvals['hex']) && passed($rvals['total_score'], $rvals['pass_mark'])) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <?php CreateInputElem("Callsign", $rvals["callsign"], ""); ?>
                    </div>
                    <div class="col-md-6">
                        <?php if ($_SESSION["rank"] >= 3) {
                            $link = new stdClass();
                            $link->label = "Whitelist";
                            $link->href = "https://highliferoleplay.net/whitelisting/index.php";
                            CreateInputBtnElem("Steam Hex:", $rvals['hex'], $link);
                        }
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
                    <?php $ver = "v".$rvals["ver"];
                    CreateInputElem("Test Type", $rvals["test_type"], $ver); ?>
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
                    $questions = getQuestions($rvals['test_type'], $rvals['ver']);
                    foreach ($questions as $key => $q) {
                        $score = $rvals['Answers'][$key];
                        echo "<tr>";
                        echo "<td class='font-weight-bold'>" . ($key + 1) . "</td>";
                        echo "<td class='" . pickTWeight($score, $q->max_points) . "'>" . $q->question . "</td>";
                        echo "<td class='" . pickBGCol($score, $q->max_points) . "'>" . $score . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($rvals['hex']) && passed($rvals['total_score'], $rvals['pass_mark'])) {
            include "../include/inc_view_test_fullpass.php";
        } ?>

        <div class="container-fluid px-0">
            <h5 class="h5-header-label mb-0 w-100 text-center">Comments</h5>
            <div class="border p-4">
                <span class="font-weight-normal"><?php echo $rvals['comments']; ?></span>
            </div>
            <div>
                <?php if(isset($doc_id)) {
                 include "../include/inc_notes.php"; ?>
                <span class="font-weight-normal"><?php CreateNotesTable($doc_id, $doc_type); ?></span>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#NoteModal">Add Note</button>
            </div>
            <?php }?>
        </div>

        <a class="btn btn-secondary mb-5 btn-large mt-5" href="table_tests_archive.php">Done</a>
    </div>
</div>

<?php include '../include/components/foot.php';
