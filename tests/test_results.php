<?php
include "../include/components/head.php";

// first, nullify any vars that are not already set
isset($_POST["steamid"]) ? $steam_id = $_POST["steamid"] : $steam_id = null;
isset($_SESSION["steam_id"]) ? $super_id = $_SESSION["steam_id"] : $super_id = null;
isset($_POST["witness_id"]) ? $witness_id = $_POST["witness_id"] : $witness_id = null;

isset($_POST["test_type"]) ? $test_type = $_POST["test_type"] : $test_type = null;
isset($_POST["test_version"]) ? $test_version = $_POST["test_version"] : $test_version = null;
isset($_POST["A"]) ? $scores = $_POST["A"] : $scores = null;
isset($_POST["comments"]) ? $comments = quotefix($_POST["comments"]) : $comments = null;


function getMetas($type, $ver)
{
    return QueryFirst("SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'");
}

function CalcResult($test_type, $test_version, $scores)
{
    $result = new StdClass();
    $result->score_string = null;
    $result->total_score = null;
    $result->literal = null;
    $passPill = "<h5 class='bg-success border rounded-pill border-success' style='width: 60px;'>PASS</h5>";
    $failPill = "<h5 class='bg-danger border rounded-pill border-danger' style='width: 60px;'>FAIL</h5>";
    $metas = getMetas($test_type, $test_version);
    foreach ($scores as $answer) {
        $result->score_string .= $answer . "/";
        $result->total_score += $answer;
    }
    $result->percentage = round(($result->total_score / $metas->max_score), 2);
    if ($result->total_score >= $metas->pass_mark) {
        $result->literal = "PASS";
        $result->pill = $passPill;
    }
    if ($result->total_score < $metas->pass_mark) {
        $result->literal = "FAIL";
        $result->pill = $failPill;
    }
    return $result;
}

function SQLInsertTest($steam_id, $type, $version, $score_total, $score_percent, $scores, $signed_by, $comments, $witness_id, $result)
{ // called regardless of test state, inserts test results into DB
    $submit_date = time();
    $sql = "INSERT INTO `tests`(`steam_id`,`type`,`version`,`score_total`,`score_percent`,`scores`,`signed_by`,`comments`,`submit_date`,`witness_id`,`result`) 
    VALUES ('$steam_id','$type','$version','$score_total','$score_percent','$scores','$signed_by','$comments','$submit_date','$witness_id','$result')";
    Query($sql);
    return QueryFirst("SELECT * FROM `tests` WHERE `submit_date` = $submit_date")->id;
}

function SQLUpdatePlayer($steam_id, $test_type)
{ // only called if $result->literal == "PASS"
    $last_seen = time();
    if ($test_type == "theory") {
        Query("UPDATE `players` SET `status` = 'Needs Practical' WHERE `steam_id` = '$steam_id'");
    }
    if ($test_type == "practical") {
        Query("UPDATE `players` SET `status` = 'Active',`last_seen` = '$last_seen',`rank`='0' WHERE `steam_id` = '$steam_id'");
    }
}

$result = CalcResult($test_type, $test_version, $scores);
$player = QueryFirst("SELECT * FROM `public_players` WHERE `steam_id` = '$steam_id'");

$test_id = SQLInsertTest($steam_id, $test_type, $test_version, $result->total_score, $result->percentage, $result->score_string, $super_id, $comments, $witness_id, $result->literal)



?>


<div class="container" style="text-align: center;">
    <div class="row">
        <div class="col">
            <h1>Test Results</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h4>How did they do?</h4>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex flex-column justify-content-center align-items-end">
            <h5>Student Name:</h5>
        </div>
        <div class="col d-flex flex-column justify-content-center align-items-start">
            <h5><?= $player->char_name ?></h5>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex flex-column justify-content-center align-items-end">
            <h5>Test Type:</h5>
        </div>
        <div class="col d-flex flex-column justify-content-center align-items-start">
            <h5><?= $test_type ?></h5>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex flex-column justify-content-center align-items-end">
            <h5>Score:</h5>
        </div>
        <div class="col d-flex flex-column justify-content-center align-items-start">
            <h5><?= ($result->percentage * 100) ?>%</h5>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex flex-column justify-content-center align-items-end">
            <h5>Result:</h5>
        </div>
        <div class="col d-flex flex-column justify-content-center align-items-start">
            <?= $result->pill ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php if ($result->literal == "PASS") {
                SQLUpdatePlayer($steam_id, $test_type);

                if ($test_type == "practical") {
            ?>
                    <a class="btn btn-warning btn-pad" type="button" href="../admin/assign_callsign.php?id=<?= $steam_id ?>">Assign Callsign</a>
                <?php } else {
                ?> <a class="btn btn-secondary btn-pad" type="button" href="view_test.php?test_id=<?= $test_id ?>">More Info</a>
                    <a class="btn btn-dark btn-pad" type="button" href="table_tests_archive.php">Finish</a> <?php
                                                                                                        }
                                                                                                    } ?>

        </div>
    </div>
</div>

<?php include "../include/components/foot.php";
