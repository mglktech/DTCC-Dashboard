<?php
$pInfo = CollectPlayerInfo($player_id);




function CollectPlayerInfo($steam_id)
{
    $pInfo = new stdClass();
    $pInfo->public_player = getPublicPlayer($steam_id);
    $pInfo->app_history = getApps($steam_id);
    $pInfo->warnings = getStrikes($steam_id);
    $pInfo->public_verified_shifts = getShiftData($steam_id);
    $pInfo->test_history = getTests($steam_id);
    $pInfo->notes = getNotes($steam_id);
    return $pInfo;
}




function getPublicPlayer($steam_id)
{
    $pInfo = Query("SELECT * from `public_players` where `steam_id` = '$steam_id'");
    if ($pInfo) {
        return $pInfo[0];
    } else {
        return null;
    }
}

function getSumShifts($steam_id)
{
    $sql = "SELECT SUM(`duration`) as `sum` FROM `_public_verified_shifts` WHERE `steam_id`='$steam_id'";
    return QueryFirst($sql)->sum;
}

function getShiftData($steam_id)
{
    $sql = "SELECT * FROM `_public_verified_shifts` WHERE `steam_id` = '$steam_id'";
    return Query($sql);
}

function getTests($steam_id)
{
    $sql = "SELECT * FROM `test_history` WHERE `steam_id` = '$steam_id'";
    return Query($sql);
}

function getApps($steam_id)
{

    $sql = "SELECT * FROM `app_history` WHERE `steam_id` = '$steam_id'";
    return Query($sql);
}
function getStrikes($steam_id)
{
    $sql = "SELECT * FROM `public_strikes` WHERE `steam_id` = '$steam_id' ORDER BY `id` DESC";
    return Query($sql);
}

function getMetas($type, $ver)
{
    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'";
    return Query($sql)[0];
}

function getNotes($steam_id)
{
    $sql = "SELECT * FROM `notes` WHERE `doc_id` = '$steam_id' AND `doc_type` = 'player' ORDER BY `timestamp` DESC";
    return Query($sql);
}

if (isset($_POST['leaveNote'])) {
    isset($_SESSION["steam_id"]) ? $steam_id = $_SESSION["steam_id"] : $steam_id = null;
    $time = time();
    $doc_type = "player";
    $doc_id = $player_id;
    $message = quotefix($_POST['note_message']);
    $sql = "INSERT INTO `private_notes` (`doc_id`, `doc_type`, `steam_id`, `timestamp`, `message`) VALUES ('$doc_id','$doc_type','$steam_id','$time','$message')";
    Query($sql);
    header("Refresh: 0");
}
if (isset($_POST["applyStrike"])) {
    isset($_SESSION["steam_id"]) ? $signed = $_SESSION["steam_id"] : $signed = null;
    $title_clean = quotefix($_POST["strike_title"]);
    $desc_clean = quotefix($_POST["strike_desc"]);
    $link_clean = quotefix($_POST["strike_link"]);
    $dateNow = time();
    $addTime = 2629743; // One Month
    $endDate = $dateNow + $addTime;
    $sql = "INSERT INTO `strikes` (`steam_id`, `strike_title`, `strike_desc`,`strike_evidence`, `signed_by`, `issue_date`, `end_date`) VALUES('$player_id','$title_clean','$desc_clean','$link_clean','$signed','$dateNow','$endDate')";
    Query($sql);
    header("Refresh: 0");
}

function ApplyStrike($id, $title, $desc, $link)
{

    //echo $r . " SQL: " . $sql;
}



function PassFail($ret, $score_percent)
{
    $pass_percent = (round($ret->pass_mark / $ret->max_score, 2));
    //echo $score_percent . "/" . $pass_percent . "<br>";
    if ($score_percent >= $pass_percent) {
        return "PASS";
    } else {
        return "FAIL";
    }
}
