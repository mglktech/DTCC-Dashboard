<div class="col d-flex flex-column justify-content-start align-items-center" style="padding: 0px;">
<h5>Senior Super Commands</h5>
</div>
</div>
<div class="row">
<div class="col d-flex justify-content-center align-items-center" style="padding: 0px;">
<button class="btn btn-dark btn-pad" data-toggle="modal" data-target="#ResetCodeModal" type="button">Reset Password</button>
<a class="btn btn-dark btn-pad" type="button" href="/admin/upd_player_details.php?steamid=<?= $player_id ?>" >Edit Details</a>
<?php if($pInfo->public_player->rank > -1)
{
    ?><button class="btn btn-dark btn-pad" data-toggle="modal" data-target="#FireModal" type="button" >Fire Employee</button>
<?php }
    if($pInfo->public_player->rank == -1){
        ?><button class="btn btn-dark btn-pad" data-toggle="modal" data-target="#RemoveRecruitModal" type="button" >Fire Recruit</button>
   <?php }
?>

</div>



<div class="modal fade" id="ResetCodeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="/admin/create_temp_code.php" method="post">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resetting password for: <?= FigureName($pInfo->public_player->callsign,$pInfo->public_player->char_name) ?></h5>
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
                    <input name="SteamID" value='<?=$player_id?>' hidden>
                    <input name="txtNewPass" id="txtCodeHidden" hidden>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="FireModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="/admin/fire_user.php" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">You are about to Fire <?php FigureName($pInfo->public_player->callsign,$pInfo->public_player->char_name) ?> from Downtown Cab Co.</h5>
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
                    <input name="DiscordName" value='<?= $pInfo->public_player->discord_name ?>' hidden>
                    <input name="char_name" value=<?= $pInfo->public_player->char_name ?> hidden>
                    <input name="SteamID" value='<?=$player_id ?>' hidden>
                    <input name="SignedBy" value='<?= $_SESSION["steam_id"]; ?>' hidden>
                    <button type="submit" class="btn btn-danger">Fire <?php echo explode(" ", $pInfo->public_player->char_name)[0]; ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="modal fade" id="RemoveRecruitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="/admin/fire_user.php" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">You are about to remove <?php $pInfo->public_player->char_name ?> from Recruitment</h5>
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
                                        <input class="custom-control-input" type="checkbox" id="banR" name="ban">
                                        <label class="custom-control-label" for="banR">
                                            <h6 class="font-weight-bold text-danger">Ban This Recruit</h6>
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
                    <input name="DiscordName" value='<?= $pInfo->public_player->discord_name ?>' hidden>
                    <input name="char_name" value=<?= $pInfo->public_player->char_name ?> hidden>
                    <input name="SteamID" value='<?=$player_id ?>' hidden>
                    <input name="SignedBy" value='<?= $_SESSION["steam_id"]; ?>' hidden>
                    <button type="submit" class="btn btn-danger">Fire <?php echo explode(" ", $pInfo->public_player->char_name)[0]; ?></button>
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