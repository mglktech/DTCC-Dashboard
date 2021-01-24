<div class="container border">
    <div class="row justify-content-center mt-2 mb-2 bg-success text-white">
        <h4>Approved</h4>
    </div>
    <div class="row">
        <div class="col mt-2">
            <?= CreateInputElemFull(SpanPrepend("Signed By: "), SpanMiddleDefault($app_info->signed_by), ""); ?>
            <?= CreateInputElemFull(SpanPrepend("Date: "), SpanMiddleDefault($app_info->signed_date), ""); ?>
        </div>
        <div class="col-auto mt-1 mb-1 align-self-center">
            <button class="btn btn-secondary fas fa-edit pt-2 pb-2" data-toggle="modal" data-target="#PastaModal"></button>
        </div>

    </div>
</div>

<div class="modal fade" id="PastaModal" tabindex="-1" role="dialog" aria-labelledby="PastaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="PastaModalLabel">Useful CopyPastas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <h6>Copy/paste for recruit:</h6>
                    <div class="border p-4">
                        Hello <?= $app_info->name ?><br>
                        Your application to Downtown Cab Co. has been approved!<br>
                        Our standard operating procedures can be found here: http://bit.ly/HighLife_DTCC_SOP <br>
                        Please read this document thoroughly, as it describes the upcoming stages in the recruitment process.<br>
                        We look forward to hiring you!<br>
                        Regards<br>
                        <?= $app_info->signed_by ?>
                    </div>
                </div>
                <div class="container mt-2">
                    <h6>Copy/paste for #database-todos:</h6>
                    <div class="border p-4">
                        Please give @<?= $app_info->discord ?> the Downtown Cab Co. Recruitment tag. Thanks!
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>