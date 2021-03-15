<?php

$pInfo = CollectPlayerInfo($player_id);

function CollectPlayerInfo($steam_id)
{
    $pInfo = new stdClass();
    $pInfo->public_player = getPublicPlayer($steam_id);
    $pInfo->app_history = getApps($steam_id);
    //$pInfo->warnings = getStrikes($steam_id);
    $pInfo->public_verified_shifts = getShiftData($steam_id);
    $pInfo->test_history = getTests($steam_id);
    //$pInfo->notes = getNotes($steam_id);
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
    return Query($sql)[0]->sum;
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

function inc_Apps($apps)
{

    $thead = ["Date", "Status", "Signed By", ""];
    $tbody = array();
    if ($apps) {
        foreach ($apps as $row) {
            $tRow = array();
            $tRow[] = toDate($row->app_timestamp);
            $tRow[] = $row->status;
            $tRow[] = $row->callsign . " | " .  $row->signed_by;
            $tRow[] = "<a class='btn btn-secondary' href='/applications/view_app.php?doc_id=" . $row->app_id . "'>View</a>";
            $tbody[] = $tRow;
        }
    }
    Tablefy($thead, $tbody);
}

function inc_Tests($tData)
{
    $thead = ["Date", "Type", "Status", "Signed By", ""];
    $tbody = array();
    if ($tData) {
        foreach ($tData as $row) {
            $tRow = array();
            $tRow[] =  toDateS($row->submit_date);
            $tRow[] = $row->type;
            $tRow[] = PassFail(getMetas($row->type, $row->version), $row->score_percent);
            $tRow[] = $row->callsign . " | " .  $row->signed_by;
            $tRow[] = "<a class='btn btn-secondary' href='../tests/view_test.php?test_id=" . $row->id . "'>View</a>";
            $tbody[] = $tRow;
        }
    }

    Tablefy($thead, $tbody);
}

function inc_shifts($tData)
{
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
}
?>

<div class="row">
    <div class="col d-flex flex-row justify-content-center align-items-center">
        <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-AdditionalInfo'>More Info</button>
        <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-tblApps'>App History</button>
        <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-tblTests'>Tests</button>
        <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-tblShiftData'>Time Sheet</button>
    </div>
</div>

<div class="modal fade" role="dialog" tabindex="-1" id="modal-AdditionalInfo">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Additional Info</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled">
                    <li>Full Name | <?= $pInfo->public_player->char_name ?></li>
                    <li>Company Rank | <?= $pInfo->public_player->rank_label ?></li>
                    <li>Steam Name | <a href="<?= $pInfo->public_player->steam_link ?>" target="_blank"><?= $pInfo->public_player->steam_name ?><br></a></li>
                    <li>Steam ID | <?= $player_id ?></li>
                    <li>Phone | <?= $pInfo->public_player->phone_number ?></li>
                    <li>Discord | <?= $pInfo->public_player->discord_name ?></li>
                    <li>Time zone | <?= $pInfo->public_player->timezone ?></li>
                    <li>Member Since | <?= toDateS($pInfo->public_player->employment_start) ?></li>
                </ul>
            </div>
            <div class="modal-footer d-flex justify-content-center"><button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" role="dialog" tabindex="-1" id="modal-tblApps">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Applications</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <?= inc_Apps($pInfo->app_history) ?>
            </div>
            <div class="modal-footer d-flex justify-content-center"><button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" tabindex="-1" id="modal-tblTests">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Completed Tests</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <?= inc_Tests($pInfo->test_history) ?>
            </div>
            <div class="modal-footer d-flex justify-content-center"><button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" tabindex="-1" id="modal-tblShiftData">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Time Sheet (All Shifts)</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <?= inc_Shifts($pInfo->public_verified_shifts) ?>
            </div>
            <div class="modal-footer d-flex justify-content-center"><button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>