<h1 class="h2 mt-3"><?php echo "<p class='alert alert-success pt-1 pb-2 px-3 m-0'>Approved By: " . q_fetchPlayerFormatted($signed_by) . ".</p>" ?></h1>
<div class="container-fluid p-0">
    <div class="row">
        <div class="col">
            <h6>Copy/Pasta to send directly to recruit:</h6>
            <div class="border p-4">
                Hello <?php echo $char_name ?><br>
                Your application to Downtown Cab Co. has been approved!<br>
                Our standard operating procedures can be found here: http://bit.ly/HighLife_DTCC_SOP <br>
                Please read this document thoroughly, as it describes the upcoming stages in the recruitment process.<br>
                We look forward to hiring you!<br>
                Regards<br>
                <?php echo q_fetchPlayerFormatted($signed_by); ?>
            </div>
        </div>
        <div class="col">
            <h6>Copy/paste for #database-todos:</h6>
            <div class="border p-4">
                Please give @<?php echo $discord_name ?> the Downtown Cab Co. Recruitment tag. Thanks!
            </div>
        </div>
    </div>
</div>