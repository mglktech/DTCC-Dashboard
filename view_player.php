<?php include "include/header.php";
include "include/elements.php";





if (isset($_GET['steamid'])) {
    $steamid = $_GET['steamid'];
    $doc_id = $steamid;
    $sql = "SELECT * from public_players where steam_id = '$steamid'";
    $player = Query($sql)[0];

    $char_name = "";
    $steam_name = "";
    $discord_name = "";
    $phone_number = "";
    $rank = "";
    $rank_label = "";
    $status = "";
    $zone = "";
    $link = new stdClass();
    $link->label = "Whitelist";
    $link->href = "https://highliferoleplay.net/whitelisting/index.php";
    $hex = "steam:" . dechex($steamid);
    $doc_type = "player";
    $call_char_name = "";
    $alive = -2;

    if ($player) {
        $char_name = $player->char_name;
        if ($player->callsign != null) {
            $call_char_name = $player->callsign . " | " . $player->char_name;
        } else {
            $call_char_name = $player->char_name;
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
    }
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
                        CreateInputElemFull(SpanPrepend("Name: "), SpanMiddleDefault($call_char_name), SpanIsAlive($alive));
                        CreateInputElem("Rank:", $rank_label, "");
                        CreateInputElem("Status:", $status, "");
                        CreateInputElem("Steam Name:", $steam_name, "");
                        CreateInputElem("SteamID:", $steamid, "");
                        if ($_SESSION['rank'] >= 3 && $_SESSION['rank'] > $rank) {
                            CreateInputBtnElem("Steam Hex:", $hex, $link);
                        }

                        CreateInputElem("Phone:", $phone_number, "");
                        CreateInputElem("Discord:", $discord_name, "");
                        CreateInputElem("Timezone:", $zone, "");
                        ?>
                        <?php if ($_SESSION['rank'] >= 2 && $_SESSION['rank'] > $rank) {
                            include "include/inc_super.php";
                        }
                        if ($_SESSION['rank'] >= 3 && $_SESSION['rank'] > $rank) {
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
                                    $tRow[] = "<a class='btn btn-outline-secondary' href='/applications/view_app.php?doc_id=" . $row->app_id . "'>View</a>";
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
                                    $tRow[] = "<button class=' btn btn-outline-secondary' data-toggle='modal' data-target='#viewStrikesModal'>View</button>";
                                    $tbody[] = $tRow;
                                }
                            }
                            Tablefy($thead, $tbody);

                            ?>

                        </div>
                        <div>
                            <?php include "include/inc_notes.php"; ?>
                            <span class="font-weight-normal"><?php CreateNotesTable($doc_id, $doc_type); ?></span>
                            <button class="btn btn-secondary" data-toggle="modal" data-target="#NoteModal">Add Note</button>
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

<div class="modal fade" id="viewStrikesModal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $char_name; ?>'s Strikes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php include "include/inc_strikes.php"; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
            </div>
        </div>
    </div>

</div>


<?php include "include/footer.php"; ?>