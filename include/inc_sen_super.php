<?php

?>
<div class="container border">
    <h6>Senior Supervisor Commands:</h6>

    <button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#ResetCodeModal" <?php if ($rank < 2) echo "disabled" ?>><i class="fas fa-key"></i> Password Reset</button>
    <?php if ($rank > -1) { ?>
        <button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#FireModal" <?php if ($rank >= $_SESSION["rank"]) echo "disabled" ?>><i class="fas fa-gavel"></i> Fire Employee</button>
    <?php } else { ?>
        <button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#RemoveRecruitModal" <?php if ($rank >= $_SESSION["rank"]) echo "disabled" ?>><i class="fas fa-gavel"></i> Remove Recruit</button>
    <?php } ?>

</div>

<!-- RESET CODE MODAL -->
<div class="modal fade" id="ResetCodeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="admin/create_temp_code.php" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resetting password for: <?php echo $char_name; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='input-group'>
                        <div class='input-group-prepend'>
                            <input type="button" name="Gen" class="btn btn-secondary" value="Click To Generate Code" onclick="generateCode()"></input>
                        </div>
                        <input id="txtCode" class="form-control" disabled></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="ChangePW" value='1' hidden>
                    <input name="txtSteamName" value='<?php echo $steam_name; ?>' hidden>
                    <input name="discord_name" value='<?php echo $discord_name; ?>' hidden>
                    <input name="txtNewPass" id="txtCodeHidden" hidden>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- FIRE MODAL -->
<div class="modal fade" id="FireModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="admin/fire_user.php" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">You are about to Fire <?php echo $char_name; ?> from Downtown Cab Co.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 mt-2 mb-3">
                                <h5>Reasons for Dismissal</h5>

                                <input type="radio" name="reason" id="inactivity" value="inactivity">
                                <label for="inactivity">
                                    <h6 class="font-weight-normal">Inactivity </h6>
                                </label><br>


                                <input type="radio" id="toomanystrikes" name="reason" value="too_many_strikes">
                                <label for="toomanystrikes">
                                    <h6 class="font-weight-normal">Too Many Strikes</h6>
                                </label><br>


                                <input type="radio" id="resigned" name="reason" value="resigned">
                                <label for="resigned">
                                    <h6 class="font-weight-normal">Employee Resigned</h6>
                                </label><br>


                                <input type="radio" id="misconduct" name="reason" value="misconduct">
                                <label for="misconduct">
                                    <h6 class="font-weight-normal"> Gross Misconduct</h6>
                                </label><br>


                                <input type="radio" name="reason" id="otherchk" value="other" data-toggle="collapse" data-target="#othergroup" aria-expanded="false" aria-controls="otherreason">
                                <label for="otherchk">
                                    <h6 class="font-weight-normal">Other (Please specify)</h6>
                                </label><br>

                                <div class="collapse" id="othergroup">
                                    <div class="input-group my-2 w-75">
                                        <input type="text" class="form-control" name="otherreason" placeholder="What's your reason?" id="otherreason" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <textarea placeholder="Additional Information" class="form-control" name="additionalInformation" id="additionalInformation" rows="5"></textarea>
                                </div>
                                <h6>
                                    <div class="custom-control custom-switch">
                                        <input class="custom-control-input" type="checkbox" id="ban" name="ban">
                                        <label class="custom-control-label" for="ban">
                                            <h6 class="font-weight-bold text-danger">Ban This Employee</h6>
                                        </label>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="FireMe" value='1' hidden>
                    <input name="DiscordName" value='<?php echo $discord_name ?>' hidden>
                    <input name="char_name" value=<?php echo $char_name; ?> hidden>
                    <input name="SteamID" value='<?php echo $steamid; ?>' hidden>
                    <input name="SignedBy" value='<?php echo $_SESSION["steam_id"]; ?>' hidden>
                    <button type="submit" class="btn btn-danger">Fire <?php echo explode(" ", $char_name)[0]; ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="RemoveRecruitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="admin/fire_user.php" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">You are about to remove <?php echo $char_name; ?> from Downtown Cab Co. recruitment.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 mt-2 mb-3">
                                <h5>Reasons for Dismissal</h5>

                                <input type="radio" name="reason" id="inactivity" value="inactivity_recruit">
                                <label for="inactivity">
                                    <h6 class="font-weight-normal">Inactivity</h6>
                                </label><br>

                                <input type="radio" id="failure" name="reason" value="failure_recruit">
                                <label for="failure">
                                    <h6 class="font-weight-normal">Failed too many tests</h6>
                                </label><br>

                                <input type="radio" name="reason" id="otherchk" value="other" data-toggle="collapse" data-target="#othergroup" aria-expanded="false" aria-controls="otherreason">
                                <label for="otherchk">
                                    <h6 class="font-weight-normal">Other (Please specify)</h6>
                                </label><br>

                                <div class="collapse" id="othergroup">
                                    <div class="input-group my-2 w-75">
                                        <input type="text" class="form-control" name="otherreason" placeholder="What's your reason?" id="otherreason" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <textarea placeholder="Additional Information" class="form-control" name="additionalInformation" id="additionalInformation" rows="5"></textarea>
                                </div>
                                <h6>
                                    <div class="custom-control custom-switch">
                                        <input class="custom-control-input" type="checkbox" id="ban" name="ban">
                                        <label class="custom-control-label" for="ban">
                                            <h6 class="font-weight-bold text-danger">Ban This Employee</h6>
                                        </label>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="FireMe" value='1' hidden>
                    <input name="recruit" value='1' hidden>
                    <input name="DiscordName" value='<?php echo $discord_name ?>' hidden>
                    <input name="char_name" value=<?php echo $char_name; ?> hidden>
                    <input name="SteamID" value='<?php echo $steamid; ?>' hidden>
                    <input name="SignedBy" value='<?php echo $_SESSION["steam_id"]; ?>' hidden>
                    <button type="submit" class="btn btn-danger">Fire <?php echo explode(" ", $char_name)[0]; ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>


<script>
    function generateCode() {
        var length = 6,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        // return retVal;
        document.getElementById("txtCode").value = retVal;
        document.getElementById("txtCodeHidden").value = retVal;
    }
    generateCode();
</script>