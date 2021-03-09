<?php
function FigureName($cs, $name)
{
    if ($cs) {
        return $cs . " | " . $name;
    } else {
        return $name;
    }
}

function FigureRankLabel($label)
{
    if ($label) {
        return $label;
    } else {
        return "Not one of us";
    }
}

function FigureColour($warns)
{
    if ($warns == 0) {
        return "text-success";
    }
    if ($warns == 1) {
        return "text-warning";
    } else {
        return "text-danger";
    }
}


function FigureWarnings($id)
{
    $t = time();
    $sql = "SELECT COUNT(*) AS `count` FROM strikes WHERE steam_id = '$id' and end_date > '$t'";
    return Query($sql)[0]->count;
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
<section>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3 class="text-center"><?= FigureName($pInfo->public_player->callsign, $pInfo->public_player->char_name) ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4 style="text-align: center;"><?= FigureRankLabel($pInfo->public_player->rank_label) ?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex flex-column justify-content-center align-items-center"><img style="width: 184px;height: 184px;border-radius: 100%;border-style: solid;border-color: #333333;" src="<?= $pInfo->public_player->av_full ?>">
                <ul class="list-unstyled text-center" id="lstAchievements">
                    <li>Member Since: <?= toDateS($pInfo->public_player->employment_start) ?> </li>
                    <li><?= toDurationDays(getSumShifts($player_id)) ?> on record</li>
                    <li><label class="col-form-label text-center <?= FigureColour(FigureWarnings($player_id)) ?>"><?= FigureWarnings($player_id) ?> Active Warnings.</label></li>
                </ul>
            </div>
            <div class="col d-flex"><textarea class="d-flex flex-fill txt-backstory-profile" rows="10" readonly=""><?= $pInfo->public_player->backstory ?></textarea></div>
        </div>
        <div class="row">
            <div class="col">
                <ul class="list-unstyled text-center">
                    <!-- Company Tags -->
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex flex-row justify-content-center align-items-center">
                <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-AdditionalInfo'>More Info</button>
                <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-tblApps'>App History</button>
                <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-tblTests'>Tests</button>
                <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-tblShiftData'>Time Sheet</button>
            </div>
        </div>

        <div class="row">
            <?php if (Rank("Supervisor")) {
                include "super_partial.php";
            } ?>
        </div>
        <div class="row">
            <?php if (Rank("Senior Supervisor", $pInfo->public_player->rank)) {
                include "sen_partial.php";
            } ?>
        </div>
    </div>
</section>


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