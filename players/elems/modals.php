<?php
function inc_Warnings($warnings)
{
    $head = ["Issue Date", "Title", "Desc", "link", "Struck by", "End Date"];
    $body = array();
    if ($warnings) {
        foreach ($warnings as $s) {
            $row = array();
            $row[] = toDateS($s->issue_date);
            $row[] = $s->strike_title;
            $row[] = $s->strike_desc;
            $row[] = $s->strike_evidence;
            $row[] = quotefix($s->signed_callsign . " | " . $s->signed_by);
            $row[] = toDateS($s->end_date);
            $body[] = $row;
        }
    }
    Tablefy($head, $body);
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
                <?= inc_Warnings($pInfo->warnings) ?>

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
                            <div class="input-group-prepend"><span class="input-group-text">Title</span></div><input class="form-control" type="text" placeholder="A Short Description..." name="strike_title">
                            <div class="input-group-append"></div>
                        </div><span>Please explain in full why you are warning this player:</span><textarea class="form-control" rows="4" placeholder="A Long Description..." name="strike_desc"></textarea><span>If you have any evidence, please include a link below:<br></span>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Link:</span></div><input class="form-control" type="text" placeholder="A Link to a screenshot/video" name="strike_link">
                            <div class="input-group-append"></div>
                        </div><span class="span-dialog">(if you have additional evidence, please include links in the first box)&nbsp;</span>
                        <input name="applyStrike" value="1" hidden></input>
                        <div class="modal-footer d-flex justify-content-center"><button class="btn btn-warning" type="submit">Submit Warning</button><button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button></div>
                    </form>
                </div>
            </div>
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
                        <?= inc_Notes($pInfo->notes) ?>
                        <input name="leaveNote" value="1" hidden></input>
                        <div class="input-group">
                            <div class="input-group-prepend"></div><input class="form-control" type="text" name="note_message">
                            <div class="input-group-append"><button class="btn btn-success" type="submit">Submit</button></div>
                        </div>
                </form>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-center"><button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button></div>
    </div>
</div>
</div>