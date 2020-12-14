<?php include '../include/header.php';
/*
would be better to concat both results page into one file, declaring test type in top.
*/
include "../include/elements.php";

include_once '../include/sqlconnection.php';
$sql = "SELECT question FROM test_questions WHERE type = 'theory' and version = '1' ORDER BY q_number";
$questions = Query($sql);

if (isset($_POST['steamid'])) {
    $rvals = POST_Theory();
}


function getMetas($type, $ver)
{
    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'";
    $result = Query($sql)[0];
    return $result;
}

function POST_Theory()
{
    $steamid = $_POST["steamid"];
    $char_name = $_POST["char_name"];
    $signed_by = $_SESSION["steam_id"];
    $total_score = 0;
    $score_string = "";
    foreach ($_POST["A"] as $answer) {
        //echo $answer . "<br>";
        $score_string .= $answer . "/";
        $total_score += $answer;
    }
    $metas = getMetas('theory', 1);
    $pass_mark = $metas->pass_mark;
    $max_score = $metas->max_score;
    $percentage = round(($total_score / $max_score), 2);

    if ($total_score >= $pass_mark) {

        //echo "congrats, you passed!";
        $sql = "UPDATE players
         SET `status`='Needs Practical'
         WHERE `steam_id`='$steamid'";
        Query($sql);
        //echo "Player Database Response: " . $response;
    }
    if ($total_score < $pass_mark) {
        //echo "You Failed.";
    }

    $sql = "INSERT INTO tests (`steam_id`, `type`, `version`, `score_total`, `score_percent`, `signed_by`,`scores`) VALUES ('$steamid','theory','1','$total_score','$percentage','$signed_by','$score_string')";
    Query($sql);

    $postret['char_name'] = $char_name;
    $postret['max_score'] = $max_score;
    $postret['total_score'] = $total_score;
    $postret['pass_mark'] = $pass_mark;
    $postret['percentage'] = $percentage;

    return $postret;
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
<div class="container text-center">
    <h2 class="font-weight-bold">Test Results</h2>
    <div class="row">
        <div class="col-md-12">
            <?php CreateInputElem("Student Name:", $rvals['char_name'], ""); ?>
            <div class="row">
                <div class="col-md-6">
                    <?php CreateInputElem("Test Type", 'Theory', "v1"); ?>
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
                    foreach ($questions as $key => $q) {
                        $score = $_POST["A"][$key];
                        echo "<tr>";
                        echo "<td class='font-weight-bold'>" . ($key + 1) . "</td>";
                        echo "<td class='" . pickTWeight($score) . "'>" . $q->question . "</td>";
                        echo "<td class='" . pickBGCol($score) . "'>" . $score . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
    <a class="btn btn-secondary" href="table_tests.php">Done</a>
</div>



<?php include '../include/footer.php'; ?>