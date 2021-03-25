<?php

$can_accept = CanAccept($app_info->SteamID);



function CanAccept($steamid)
{
    // response = [bool CanAccept, string Reason]
    if (!$steamid) {
        return [false, "steamid"];
    } else if (IsBanned($steamid)) {
        return [false, "banned"];
    } else if (IsAlreadyEmployed($steamid)) {
        return [false, "notfired"];
    } else {
        return [true, ""];
    }
}

function IsAlreadyEmployed($steam_id)
{
    $player = QueryFirst("SELECT * FROM `players` WHERE `steam_id` = '$steam_id'");
    if ($player != null) {
        if ($player->rank != null) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function IsBanned($steam_id)
{
    //include_once "db_connection.php";
    $sql = "SELECT `status` FROM `players` WHERE `steam_id` = '$steam_id'";
    $status = Query($sql);
    if (isset($status)) {
        if ($status[0]->status == "Banned") {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function CanEnable($result)
{
    if ($result[0] == true) {
        return "";
    } else {
        return "disabled";
    }
}

function AutoCheck($result, $box)
{
    if ($box == $result[1]) {
        return "checked";
    } else {
        return "";
    }
}


function CanReapply($result)
{
    if ($result[1] == "banned") {
        return [0, "disabled"];
    } else {
        return [2, ""];
    }
}



?>



<div class="row d-flex flex-column align-items-center mt-3">
    <div class="col-auto">
        <label> Please Sign Here: </label>
    </div>
    <div class="col-auto">
        <button class="btn btn-success m-1" type="button" data-toggle='modal' data-target='#modal-accept' <?= CanEnable($can_accept) ?>>Accept</button>
        <button class="btn btn-secondary m-1" type="button" data-toggle='modal' data-target='#modal-ignore'>Ignore</button>
        <button class="btn btn-danger m-1" type="button" data-toggle='modal' data-target='#modal-deny'>Deny</button>
    </div>
</div>

<div class="modal fade" role="dialog" tabindex="-1" id="modal-accept">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-success">
            <div class="modal-header">
                <h4 class="modal-title">Accepting Applicant</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <p>Are you Sure you want to accept this applicant?</p>
            </div>
            <form method="post" target="_self">
                <input name="SubmitApp" value="accept" hidden></input>
                <input name="app_id" value="<?= $doc_id ?>" hidden></input>
                <input name="char_name" value="<?= $app_info->name ?>" hidden></input>
                <input name="phone_number" value="<?= $app_info->phone ?>" hidden></input>
                <input name="discord_name" value="<?= $app_info->discord ?>" hidden></input>
                <input name="timezone" value="<?= $app_info->Zone ?>" hidden></input>
                <input name="backstory" value="<?= $app_info->backstory ?>" hidden></input>
                <input name="steam_link" value="<?= $app_info->steam_link ?>" hidden></input>
                <input name="steam_name" value="<?= $app_info->steam_name ?>" hidden></input>
                <input name="steam_id" value="<?= $app_info->SteamID ?>" hidden></input>
                <input name="av_full" value="<?= $app_info->av_full ?>" hidden></input>
                <div class="modal-footer d-flex justify-content-center"><button class="btn btn-success btn-lg" type="submit">Yes</button><button class="btn btn-danger btn-lg" type="button" data-dismiss="modal">No</button></div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" role="dialog" tabindex="-1" id="modal-deny">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-danger">
            <div class="modal-header">
                <h4 class="modal-title">Denying Applicant</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label>Please tell us why:</label>
                    <form method="post" target="_self">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-check custom-control custom-switch"><input class="form-check-input custom-control-input" type="checkbox" name="Dcsw-banned" id="Dcsw-banned" <?= AutoCheck($can_accept, "banned") ?> disabled><label class="form-check-label custom-control-label" for="csw-banned">Player is Banned from DTCC (auto)</label></div>
                                <div class="form-check custom-control custom-switch"><input class="form-check-input custom-control-input" type="checkbox" name="Dcsw-steamid" id="Dcsw-steamid" <?= AutoCheck($can_accept, "steamid") ?> disabled><label class="form-check-label custom-control-label" for="csw-steamid">Bad Steam Link (auto)</label></div>
                                <hr>
                                <div class="form-check custom-control custom-switch"><input class="form-check-input custom-control-input" type="checkbox" name="csw-char_name" id="csw-char_name"><label class="form-check-label custom-control-label" for="csw-char_name">Bad Character Name</label></div>
                                <div class="form-check custom-control custom-switch"><input class="form-check-input custom-control-input" type="checkbox" name="csw-phone" id="csw-phone"><label class="form-check-label custom-control-label" for="csw-phone">Bad Phone Number</label></div>
                                <div class="form-check custom-control custom-switch"><input class="form-check-input custom-control-input" type="checkbox" name="csw-discord" id="csw-discord"><label class="form-check-label custom-control-label" for="csw-discord">Bad Discord</label></div>
                                <div class="form-check custom-control custom-switch"><input class="form-check-input custom-control-input" type="checkbox" name="csw-backstory" id="csw-backstory"><label class="form-check-label custom-control-label" for="csw-backstory">Bad Backstory</label></div>
                                <div class="form-check custom-control custom-switch"><input class="form-check-input custom-control-input" type="checkbox" name="csw-reason" id="csw-reason"><label class="form-check-label custom-control-label" for="csw-reason">Bad Reason</label></div>
                            </div>
                            <div class="col">
                                <div class="d-flex flex-column"><span class="span-textarea">Additional Context:</span><textarea class="form-control" name="txt-addinfo" id="txt-addinfo" rows="5"></textarea></div>
                                <div class="input-group p-1">
                                    <div class="input-group-prepend"><span class="input-group-text">Reapply in:&nbsp;</span></div><input name="txt-days" id="txt-days" class="form-control" type="text" value="<?= CanReapply($can_accept)[0] ?>" <?= CanReapply($can_accept)[1] ?>>
                                    <div class="input-group-append"><span class="input-group-text">Days</span></div>
                                </div>
                            </div>
                        </div>
                        <input name="SubmitApp" value="deny" hidden></input>
                        <input name="app_id" value="<?= $doc_id ?>" hidden></input>
                        <input name="char_name" value="<?= $app_info->name ?>" hidden></input>
                        <input name="phone_number" value="<?= $app_info->phone ?>" hidden></input>
                        <input name="discord_name" value="<?= $app_info->discord ?>" hidden></input>
                        <input name="timezone" value="<?= $app_info->Zone ?>" hidden></input>
                        <input name="backstory" value="<?= $app_info->backstory ?>" hidden></input>
                        <input name="steam_link" value="<?= $app_info->steam_link ?>" hidden></input>
                        <input name="steam_name" value="<?= $app_info->steam_name ?>" hidden></input>
                        <input name="steam_id" value="<?= $app_info->SteamID ?>" hidden></input>
                        <input name="av_full" value="<?= $app_info->av_full ?>" hidden></input>
                        <input type="checkbox" name="csw-banned" id="csw-banned" <?= AutoCheck($can_accept, "banned") ?> hidden></input>
                        <input type="checkbox" name="csw-steamid" id="csw-steamid" <?= AutoCheck($can_accept, "steamid") ?> hidden></input>
                        <div class="modal-footer d-flex justify-content-center"><button class="btn btn-success btn-lg" type="submit">Submit</button><button class="btn btn-danger btn-lg" type="button" data-dismiss="modal">Cancel</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" role="dialog" tabindex="-1" id="modal-ignore">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ignoring Applicant</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <p>Are you Sure you want to ignore this application?</p>
            </div>
            <form method="post" target="_self">
                <input name="SubmitApp" value="ignore" hidden></input>
                <input name="app_id" value="<?= $doc_id ?>" hidden></input>
                <input name="char_name" value="<?= $app_info->name ?>" hidden></input>
                <input name="phone_number" value="<?= $app_info->phone ?>" hidden></input>
                <input name="discord_name" value="<?= $app_info->discord ?>" hidden></input>
                <input name="timezone" value="<?= $app_info->Zone ?>" hidden></input>
                <input name="backstory" value="<?= $app_info->backstory ?>" hidden></input>
                <input name="steam_link" value="<?= $app_info->steam_link ?>" hidden></input>
                <input name="steam_name" value="<?= $app_info->steam_name ?>" hidden></input>
                <input name="steam_id" value="<?= $app_info->SteamID ?>" hidden></input>
                <input name="av_full" value="<?= $app_info->av_full ?>" hidden></input>
                <div class="modal-footer d-flex justify-content-center"><button class="btn btn-success btn-lg" type="submit">Yes</button><button class="btn btn-danger btn-lg" type="button" data-dismiss="modal">No</button></div>
            </form>
        </div>
    </div>
</div>