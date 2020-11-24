<?php include "include/header.php";
include "include/elements.php";

include "include/sqlconnection.php";



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

function getSumShifts($steam_id)
{
    $sql = "SELECT SUM(`duration`) as `sum` FROM `public_verified_shifts` WHERE `steam_id`='$steam_id'";
    return Query($sql)[0]->sum;
}

function getShiftData($steam_id)
{
    $sql = "SELECT * FROM `public_verified_shifts` WHERE `steam_id` = '$steam_id'";
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


<div class="container-fluid">
    <!-- APPLICATION FORM -->
    <div class="row">
        <div class="col rounded p-0 pb-3">
            <h1>Player Profile</h1>
            <h5 class="font-italic mb-3 font-weight-normal">Much Info. Very Detail.</h5>
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        CreateInputElemFull(SpanPrepend("Name: "), SpanMiddleDefault($char_name), SpanIsAlive($alive));
                        CreateInputElem("Rank:", $rank_label, "");
                        CreateInputElem("Status:", $status, "");
                        CreateInputElem("Steam Name:", $steam_name, "");
                        CreateInputElem("SteamID:", $steamid, "");
                        CreateInputBtnElem("Steam Hex:", $hex, $link);
                        CreateInputElem("Phone:", $phone_number, "");
                        CreateInputElem("Discord:", $discord_name, "");
                        CreateInputElem("Timezone:", $zone, "");
                        ?>
                        <?php if ($_SESSION['rank'] > $rank) {
                            include "include/inc_super.php";
                        }
                        if ($_SESSION['rank'] > 2) {
                            include "include/inc_sen_super.php";
                        } ?>
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
    <div class="row pb-5">
        <h3 class="d-block w-100">Shift Data</h3><br>
        <h5 class="font-italic mb-3 font-weight-normal">Total time on shift: <strong><?php echo toDurationDays(getSumShifts($steamid)); ?></strong></h5>
        <?php $tData = getShiftData($steamid);
        $thead = ["Date", "Time In", "Time Out", "Duration"];
        $tbody = array();
        if ($tData) {
            foreach ($tData as $row) {
                $tRow = array();
                $tRow[] = toDateS($row->time_in); // Date
                $tRow[] = toTime($row->time_in);
                $tRow[] = toTime($row->time_out);
                $tRow[] = toDurationHours($row->duration);
                $tbody[] = $tRow;
            }
        }
        Tablefy($thead, $tbody);
        ?>

    </div>
</div>
<!-- END OF VIEW_PLAYER -->



<?php include "include/footer.php"; ?>