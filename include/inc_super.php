<?php
if (isset($_POST['applyStrike'])) {

    ApplyStrike($_POST['reason'], $_POST['severity']);
}

function ApplyStrike($reason, $severity)
{
    $dateNow = time();
    if ($severity == 1) {
        $addTime = 604800;
    }
    if ($severity == 2) {
        $addTime = 2629743;
    }
    if ($severity == 3) {
        $addTime = 15778458;
    }
    $endDate = $dateNow + $addTime;
    $steamid = $_GET['steamid'];
    $signed_by = $_SESSION['steam_id'];
    $sql = "INSERT INTO strikes (`steam_id`, `severity`, `strike_desc`, `signed_by`, `issue_date`, `end_date`) VALUES('$steamid','$severity','$reason','$signed_by','$dateNow','$endDate')";
    Query($sql);


    //echo $r . " SQL: " . $sql;
}
?>
<div class="container border">
    <h6>Supervisor Commands:</h6>
    <button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#StrikeModal" <?php if ($rank < 0) echo "disabled" ?>><i class="fas fa-times"></i> Strike Employee</button>
</div>
<div class="modal fade" id="StrikeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-danger">
                <div class="modal-header">
                    <h5 class="modal-title">Striking <?php echo $char_name ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>
                        Please describe your Strike
                    </h5>
                    <h6 class="">
                        <div class="row">
                            <div class="col-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Reason</span>
                                    </div>
                                    <textarea name="reason" class="form-control" aria-label="With textarea"></textarea>
                                </div>
                            </div>
                            <div class="col-3 align-items-center">
                                <h5>Severity:</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="severity" id="low" value="1" checked>
                                    <label class="form-check-label" for="low">
                                        Low (1 week)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="severity" id="medium" value="2">
                                    <label class="form-check-label" for="medium">
                                        Medium (1 month)
                                    </label>
                                </div>
                                <div class="form-check disabled">
                                    <input class="form-check-input" type="radio" name="severity" id="high" value="3">
                                    <label class="form-check-label" for="high">
                                        High (6 months)
                                    </label>
                                </div>
                            </div>

                        </div>


                    </h6>
                    <h6 class="mb-3">

                    </h6>
                </div>
                <div class="modal-footer">

                    <input name="applyStrike" value="1" hidden></input>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Strike Player</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>


                </div>
            </div>
        </div>
    </form>
</div>