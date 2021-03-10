<div class="row">
    <div class="col">
        <div class="container-result"><span class="d-flex justify-content-center align-items-center span-approved">Approved!</span>
            <div class="row">
                <div class="col d-flex justify-content-end align-items-center">
                    <div>
                        <div class="input-group d-flex justify-content-end justify-content-sm-center igroup-read">
                            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Signed:&nbsp;</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->signed_by ?>" readonly="" style="max-width: 200px;">
                            <div class="input-group-append"></div>
                        </div>
                        <div class="input-group d-flex justify-content-end justify-content-sm-center igroup-read">
                            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Date:&nbsp;</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->signed_date ?>" readonly="" style="max-width: 200px;">
                            <div class="input-group-append"></div>
                        </div>
                    </div>
                </div>
                <div class="col d-flex justify-content-start align-items-center"><button class="btn btn-dark btn-lg fas fa-edit" data-toggle="modal" data-target="#PastaModal"></button></div>
            </div>
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