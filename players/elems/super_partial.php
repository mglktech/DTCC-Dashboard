<div class="col d-flex flex-column justify-content-start align-items-center" style="padding: 0px;">
    <h5>Super Commands</h5>
</div>
</div>
<div class="row">
    <div class="col d-flex justify-content-center align-items-center" style="padding: 0px;">
        <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-tblWarnings'>View Warnings</button>
        <?php if (Rank("Supervisor", $pInfo->public_player->rank)) {
        ?><button class="btn btn-warning btn-pad" type="button" data-toggle='modal' data-target='#modal-WarnPlayer'>Issue Warning</button><?php
                                                                                                                                        }
                                                                                                                                            ?>

        <button class="btn btn-secondary btn-pad" type="button" data-toggle='modal' data-target='#modal-tblNotes'>Notes</button>
    </div>