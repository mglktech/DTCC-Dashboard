<?php include '../include/header.php';

include '../include/elements.php';
include_once '../include/db_connection.php';
$sql = "SELECT `question_title` FROM `practical_test_data_v0` WHERE 1";
$questions = fetchAll($sql);


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



if (isset($_POST['steamid'])) {
    $steamid = $_POST["steamid"];
    $char_name = $_POST["char_name"];
    $signed_by = $_SESSION["steam_id"];
    $total_score = 0;
    foreach ($_POST["A"] as $answer) {
        //echo $answer . "<br>";
        $total_score += $answer;
    }



    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='practical' AND `version`='0'";
    $result = fetchRow($sql);
    $pass_mark = $result[0];
    $max_score = $result[1];
    $percentage = round(($total_score / $max_score), 2);

    function CreatePassFail($total_score, $pass_mark)
    {
        if ($total_score >= $pass_mark) {

            return "<input class='form-control bg-success text-light font-weight-bold' value='PASS' disabled>";
        } else {
            return "<input class='form-control bg-danger text-light font-weight-bold' value='FAIL' disabled>";
        }
    }


    if ($total_score >= $pass_mark) {

        //echo "congrats, you passed!";
        $sql = "UPDATE players
        SET `status`='Active'
        WHERE `steam_id`='$steamid'";
        $response = SqlRun($sql);
        //echo "Player Database Response: " . $response;
    }

    if ($total_score < $pass_mark) {
        //echo "You Failed.";
    }
    $sql = "INSERT INTO tests (`steam_id`, `type`, `version`, `score_total`, `score_percent`, `signed_by`) VALUES ('$steamid','practical','0','$total_score','$percentage','$signed_by')";
    $response = SqlRun($sql);
    //echo "Tests Database Response: " . $response;
}
?>
<div class="col-md-6 text-center">

</div>
<div class="container text-center">
    <h2 class="font-weight-bold">Test Results</h2>
    <div class="row">
        <div class="col-md-12">
            <?php CreateInputElem("Student Name:", $char_name, ""); ?>
            <div class="row">
                <div class="col-md-6">
                    <?php CreateInputElem("Test Type", 'Practical', "v0"); ?>
                </div>
                <div class="col-md-6">
                    <?php CreateRichInputElem("Test Result:", CreatePassFail($total_score, $pass_mark), ($percentage * 100) .  "%"); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php CreateInputElem("Total Score:", $total_score, "/ " . $max_score); ?>
                </div>
                <div class="col-md-6">
                    <?php CreateInputElem("Pass Mark:", $pass_mark . "/ " . $max_score, (round($pass_mark / $max_score, 2) * 100) . "%"); ?>
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
                        echo "<td class='" . pickTWeight($score) . "'>" . $q[0] . "</td>";
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