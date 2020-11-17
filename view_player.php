<?php include "include/header.php";
include "include/elements.php";

include "include/sqlconnection.php";

if (isset($_POST['applyStrike'])) {

    ApplyStrike($_POST['reason'], $_POST['severity']);
}

function ApplyStrike($reason, $severity)
{
    $dateNow = time();
    if ($severity == 1) {
        $addTime = 604800;
    }
    if ($severity == 2) {
        $addTime = 2629743;
    }
    if ($severity == 3) {
        $addTime = 15778458;
    }
    $endDate = $dateNow + $addTime;
    $steamid = $_GET['steamid'];
    $signed_by = $_SESSION['steam_id'];
    $sql = "INSERT INTO strikes (`steam_id`, `severity`, `strike_desc`, `signed_by`, `issue_date`, `end_date`) VALUES('$steamid','$severity','$reason','$signed_by','$dateNow','$endDate')";
    Query($sql);


    //echo $r . " SQL: " . $sql;
}

if (isset($_GET['steamid'])) {
    $steamid = $_GET['steamid'];
    $sql = "SELECT * from public_players where steam_id = '$steamid'";
    $player = Query($sql)[0];
    $char_name = $player->char_name;
    if ($player->callsign != null) {
        $char_name = $player->callsign . " | " . $player->char_name;
    }

    //$steamid = $_GET['steamid'] or $player[2];
    $steam_name = $player->steam_name;
    $discord_name = $player->discord_name;
    $phone_number = $player->phone_number;
    $rank = $player->rank;
    $rank_label = $player->rank_label;
    $status = $player->status;
    $zone = $player->timezone;

    $alive = IsAlive($player->char_name);
    $link = new stdClass();
    $link->label = "Whitelist";
    $link->href = "https://highliferoleplay.net/whitelisting/index.php";
    $hex = "steam:" . dechex($steamid);
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
    $sql = "SELECT * FROM `public_strikes` WHERE `steam_id` = '$steam_id'";
    return Query($sql);
}

function getMetas($type, $ver)
{
    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'";
    return Query($sql)[0];
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

?>


<div class="container-fluid border">
    <!-- APPLICATION FORM -->
    <div class="row">
        <div class="col rounded p-0 pb-3">
            <h1>Player</h1>
            <h5 class="font-italic mb-3 font-weight-normal">gimme the deets</h5>
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        CreateInputElemFull(SpanPrepend("Name: "), SpanMiddleDefault($char_name), SpanIsAlive($alive));
                        CreateInputElem("Rank:", $rank_label, "");
                        CreateInputElem("Status:", $status, "");
                        echo "<br>";
                        CreateInputElem("Steam Name:", $steam_name, "");
                        CreateInputElem("SteamID:", $steamid, "");
                        CreateInputBtnElem("Steam Hex:", $hex, $link);
                        CreateInputElem("Phone:", $phone_number, "");
                        CreateInputElem("Discord:", $discord_name, "");
                        CreateInputElem("Timezone:", $zone, "");
                        ?>
                        <?php if ($_SESSION['rank'] > $rank) { ?>
                            <button class="btn btn-lg bg-danger text-light" data-toggle="modal" data-target="#StrikeModal"><i class="fas fa-times"></i> Strike</button>

                        <?php }
                        if ($_SESSION['rank'] > 2 && $_SESSION['rank'] > $rank) { ?>
                            <button class="btn btn-lg bg-danger text-light" disabled><i class="fas fa-angry"></i> Fire</button>
                            <button class="btn btn-lg bg-danger text-light" disabled><i class="fas fa-gavel"></i> Ban</button>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-1">Application History</h5>
                        <div>
                            <?php $tableData = getApps($steamid);
                            $thead = ["Date", "Status", "Signed By", ""];
                            $tbody = array();
                            if ($tableData) {
                                foreach ($tableData as $row) {
                                    $tRow = array();
                                    $tRow[] = toDate($row->app_timestamp);
                                    $tRow[] = $row->status;
                                    $tRow[] = $row->callsign . " | " .  $row->signed_by;
                                    $tRow[] = "<a class='btn btn-outline-secondary' href='/view_app.php?appid=" . $row->app_id . "'>View</a>";
                                    $tbody[] = $tRow;
                                }
                            }

                            Tablefy($thead, $tbody);
                            ?>
                        </div>
                        <h5 class="mt-3 mb-1">Completed Tests:</h5>
                        <div>
                            <?php $tableData = getTests($steamid);
                            $thead = ["Date", "Type", "Status", "Signed By", ""];
                            $tbody = array();
                            if ($tableData) {
                                foreach ($tableData as $row) {
                                    $tRow = array();
                                    $tRow[] =  toDateS($row->submit_date);
                                    $tRow[] = $row->type;
                                    $tRow[] = PassFail(getMetas($row->type, $row->version), $row->score_percent);
                                    $tRow[] = $row->callsign . " | " .  $row->signed_by;
                                    $tRow[] = "<a class='btn btn-outline-secondary' href='../tests/view_test.php?test_id=" . $row->id . "'>View</a>";
                                    $tbody[] = $tRow;
                                }
                            }

                            Tablefy($thead, $tbody);

                            ?>
                        </div>
                        <h5 class="mt-3 mb-1">Strikes:</h5>
                        <div>
                            <?php $tableData = getStrikes($steamid);
                            $thead = ["Start Date", "Severity", "End Date", "Signed By", ""];
                            $tbody = array();
                            if ($tableData) {
                                foreach ($tableData as $row) {
                                    $tRow = array();
                                    $tRow[] = toDateS($row->issue_date);
                                    $tRow[] = $row->severity;
                                    $tRow[] = toDateS($row->end_date);
                                    $tRow[] = $row->signed_callsign . " | " .  $row->signed_by;
                                    $tRow[] = "<a class='btn btn-outline-secondary' href='../tests/view_strike.php?strike_id=" . $row->id . "'>View</a>";
                                    $tbody[] = $tRow;
                                }
                            }
                            Tablefy($thead, $tbody);

                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        Shift Data<br>
    </div>
</div>
<!-- END OF VIEW_PLAYER -->

<div class="modal fade" id="StrikeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-danger">
                <div class="modal-header">
                    <h5 class="modal-title">Striking <?php echo $char_name ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>
                        Please describe your Strike
                    </h5>
                    <h6 class="">
                        <div class="row">
                            <div class="col-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Reason</span>
                                    </div>
                                    <textarea name="reason" class="form-control" aria-label="With textarea"></textarea>
                                </div>
                            </div>
                            <div class="col-3 align-items-center">
                                <h5>Severity:</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="severity" id="low" value="1" checked>
                                    <label class="form-check-label" for="low">
                                        Low (1 week)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="severity" id="medium" value="2">
                                    <label class="form-check-label" for="medium">
                                        Medium (1 month)
                                    </label>
                                </div>
                                <div class="form-check disabled">
                                    <input class="form-check-input" type="radio" name="severity" id="high" value="3">
                                    <label class="form-check-label" for="high">
                                        High (6 months)
                                    </label>
                                </div>
                            </div>

                        </div>


                    </h6>
                    <h6 class="mb-3">

                    </h6>
                </div>
                <div class="modal-footer">

                    <input name="applyStrike" value="1" hidden></input>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Strike Player</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>


                </div>
            </div>
        </div>
    </form>
</div>



<?php include "include/footer.php"; ?>