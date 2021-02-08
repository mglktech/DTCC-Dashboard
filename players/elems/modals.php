
<?php 
function inc_Warnings($warnings)
{
    $head = ["Issue Date", "Severity", "reason", "Struck by", "End Date"];
    $body = array();
    if ($warnings) {
        foreach ($warnings as $s) {
            $row = array();
            $row[] = toDateS($s->issue_date);
            $row[] = $s->severity;
            $row[] = quotefix($s->strike_desc);
            $row[] = quotefix($s->signed_callsign . " | " . $s->signed_by);
            $row[] = toDateS($s->end_date);
            $body[] = $row;
        }
    }
    Tablefy($head, $body);
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

function inc_notes($tData) 
{
    $thead = ["Notes"];
    $tbody = array();
        if ($tData) {
            foreach ($tData as $row) {
                $tRow = array();
                $tRow[] = $row->char_name . ": " . $row->message . " - " . ToDateS($row->timestamp);
                $tbody[] = $tRow;
            }
        }
        Tablefy($thead, $tbody);

}

?>

<div class="modal fade" role="dialog" tabindex="-1" id="modal-tblWarnings">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Warnings</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <?=inc_Warnings($pInfo->warnings)?>
                    
                </div>
                <div class="modal-footer d-flex justify-content-center"><button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button></div>
            </div>
        </div>
</div>

<div class="modal fade" role="dialog" tabindex="-1" id="modal-WarnPlayer">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Written Warning</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="container d-flex flex-column">
                        <form id="warning_deets" target="_self" method="post"><span>Please give your warning a short description:</span>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Title</span></div><input class="form-control" type="text" value="3x running red lights, speeding.">
                                <div class="input-group-append"></div>
                            </div><span>Please explain in full why you are warning this player:</span><textarea class="form-control" rows="4">two verbal warnings issued, but player continued to break traffic laws despite being warned!</textarea><span>If you have any evidence, please include a link below:<br></span>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Link:</span></div><input class="form-control" type="text">
                                <div class="input-group-append"></div>
                            </div><span class="span-dialog">(if you have additional evidence, please include links in the first box)&nbsp;</span>
                            <div class="modal-footer d-flex justify-content-center"><button class="btn btn-warning" type="submit">Submit Warning</button><button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button></div>
                        </form>
                    </div>
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
                    <?= inc_Apps($pInfo->app_history)?>
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
                    <?=inc_Tests($pInfo->test_history)?>
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
                    <?=inc_Shifts($pInfo->public_verified_shifts)?>
                </div>
                <div class="modal-footer d-flex justify-content-center"><button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button></div>
            </div>
        </div>
</div>

<div class="modal fade" role="dialog" tabindex="-1" id="modal-tblNotes">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Supervisor Notes</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                <form method="post" target="_self">
                    <div class="container">
                        <?=inc_Notes($pInfo->notes)?>
                        
                            <div class="input-group">
                                <div class="input-group-prepend"></div><input class="form-control" type="text">
                                <div class="input-group-append"><button class="btn btn-success" type="submit">Submit</button></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center"><button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button></div>
            </div>
        </div>
</div>