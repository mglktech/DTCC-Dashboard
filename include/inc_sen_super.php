<?php

?>
<div class="container border">
    <h6>Senior Supervisor Commands:</h6>

    <button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#ResetCodeModal" <?php if ($rank < 2) echo "disabled" ?>><i class="fas fa-key"></i> Password Reset</button>
    <button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#FireModal" <?php if ($rank >= $_SESSION["rank"]) echo "disabled" ?>><i class="fas fa-gavel"></i> Fire Employee</button>
    <button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#BanModal" <?php if ($rank >= $_SESSION["rank"]) echo "disabled" ?>><i class="fas fa-ban"></i> Ban Employee</button>
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
    <form action="admin/create_temp_code.php" method="post">
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
                            <div class="col-md-6 mt-2">
                                <h5>Reasons for Dismissal</h5>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" name="inactivity" id="inactivity" />
                                    <label class="custom-control-label" for="inactivity">
                                        <h6 class="font-weight-normal">
                                            Inactivity
                                        </h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" id="toomanystrikes" name="toomanystrikes" />
                                    <label class="custom-control-label" for="toomanystrikes">
                                        <h6 class="font-weight-normal">Too Many Strikes</h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" id="resigned" name="resigned" />
                                    <label class="custom-control-label" for="resigned">
                                        <h6 class="font-weight-normal">Employee Resigned</h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" id="badDiscordName" name="badDiscordName" />
                                    <label class="custom-control-label" for="badDiscordName">
                                        <h6 class="font-weight-normal"> Gross Misconduct</h6>
                                        </h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" name="otherchk" id="otherchk" data-toggle="collapse" data-target="#othergroup" aria-expanded="false" aria-controls="otherreason" />
                                    <label class="custom-control-label" for="otherchk">
                                        <h6 class="font-weight-normal">Other (Please specify)</h6>
                                    </label>
                                </div>
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
                                <span><i>Employee will be told they can reapply at a later date.</i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="FireMe" value='1' hidden>
                    <input name="txtSteamName" value='<?php echo $steam_name; ?>' hidden>
                    <input name="discord_name" value='<?php echo $discord_name; ?>' hidden>
                    <button type="submit" class="btn btn-danger">Fire <?php echo explode(" ", $char_name)[0]; ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- BAN MODAL -->
<div class="modal fade" id="BanModal" tabindex="-1" role="dialog" aria-hidden="true">
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