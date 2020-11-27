<?php
function IsBanned($steam_id)
{
    //include_once "db_connection.php";
    $sql = "SELECT `status` FROM `players` WHERE `steam_id` = '$steam_id'";
    $status = Query($sql)[0]->status;
    if (isset($status)) {
        if ($status == "Banned") {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function prepBanned($IsBanned)
{
    if ($IsBanned) {
        return "<p class='text-danger font-weight-bold'>This application cannot be accepted as this user is Banned from DTCC.</p>";
    } else {
        return "";
    }
}
if (!isset($_GET["manual_steamid"])) {
    // $player = PrepareSteamURL($steam_link);
    // $detected_steam_id = $player->steamID64;
    // $steam_name = $player->steamID;
    if (isset($detected_steam_id)) {
        $SteamWebAPIResponse = "<p class='text-success'>SteamWebAPI has retrieved this user's SteamID and most up-to-date Nickname automatically.</p>";
    }
    if (!isset($detected_steam_id)) {
        $detected_steam_id = "";
        $detected_steam_name = "";
        $SteamWebAPIResponse = "<p class='text-warning'>SteamWebAPI cannot retrieve this user's SteamID. (is their profile set to private?)</p>";
    }
}
if (isset($_GET["manual_steamid"])) {

    if ($_GET["manual_steamid"] == "") {
        $detected_steam_name = "";
        $detected_steam_id = "";
    } else {
        require_once "steam/SteamUser.php";
        $user = new SteamUser($_GET["manual_steamid"]);
        $detected_steam_name = $user->steamID;
        if ($detected_steam_name) {
            $detected_steam_id = $_GET["manual_steamid"];
            $SteamWebAPIResponse = "<p class='text-success'>The SteamID you have provided has been verified by SteamAPI.</p>";
        } else {
            $detected_steam_id = "";
            $SteamWebAPIResponse = "<p class='text-danger'>The SteamID you have provided cannot be verified by SteamAPI.</p>";
        }
    }
}
$isBanned = IsBanned($detected_steam_id);
$banned_status = prepBanned($isBanned);
?>

<div class="row mt-3">
    <div class="col rounded pb-3">
        <h3 class="mt-1">Additional Data</h3>
        <h6><?php echo $SteamWebAPIResponse; ?></h6>
        <h5 class="mb-3">
            <span class="font-weight-normal"><?php echo $banned_status; ?></span>
        </h5>

        <h5 class="mb-3">
            <form action="/view_app.php" method="get">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">SteamID64:</span>
                    </div>
                    <input name="appid" value="<?php echo $appid; ?>" hidden></input>
                    <input name="detected_steamid" value="<?php echo $detected_steamid; ?>" hidden></input>

                    <input name="manual_steamid" class="form-control" value="<?php
                                                                                if ($detected_steam_id) {
                                                                                    echo $detected_steam_id;
                                                                                } else {
                                                                                    echo "";
                                                                                } ?>" aria-label="" aria-describedby="basic-addon2" <?php if ($detected_steam_id) {
                                                                                                                                        echo "disabled";
                                                                                                                                    } ?>>
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit" <?php if ($detected_steam_id) echo "disabled"; ?>>Submit</button>
                    </div>
                </div>
            </form>
        </h5>

        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptmodal" <?php if (!$detected_steam_id | $isBanned) echo "disabled"; ?>>
            Approve Application
        </button>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denyModal">
            Reject Application
        </button>
        <a type="button" class="btn btn-secondary" data-dismiss="modal" href="applications.php">Go Back</a>
    </div>
</div>
</div>
<br>




<!-- Modal Backends -->
<div class="modal fade" id="acceptmodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-success">
            <div class="modal-header">
                <h5 class="modal-title text-center">Approve Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5>
                    Are you sure you want to accept this application?
                </h5>
                <h6 class="mb-3">
                    Detected Steam Name:
                    <span class="font-weight-normal"><?php echo $detected_steam_name; ?></span>
                </h6>
                <h6 class="mb-3">
                    Detected steam name will be saved to the database over application's steam name.
                </h6>
            </div>
            <div class="modal-footer">
                <form action="/view_app.php" method="post">
                    <input name="SubmitApp" value="approve" hidden></input>
                    <input name="appid" value="<?php echo $appid; ?>" hidden></input>
                    <input name="detected_steam_name" value="<?php echo $detected_steam_name; ?>" hidden></input>
                    <input name="detected_steam_id" value="<?php echo $detected_steam_id; ?>" hidden></input>
                    <input name="phone_number" value="<?php echo $phone_number; ?>" hidden></input>
                    <input name="char_name" value="<?php echo $char_name; ?>" hidden></input>
                    <input name="discord_name" value="<?php echo $discord_name; ?>" hidden></input>
                    <input name="timezone" value="<?php echo $zone; ?>" hidden></input>
                    <button type="submit" class="btn btn-success" <?php if ($_SESSION["rank"] < 2) echo "disabled" ?>>Approve</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-danger">
            <div class="modal-header">
                <h5 class="modal-title" id="denyModalLabel">
                    Deny Applicant Confirmation
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-center mb-3">
                                Are you sure you want to reject this applicant?
                            </h5>
                        </div>
                        <form class="row mx-0 w-100" action="/view_app.php" method="post">

                            <div class="col-md-6 mt-2">
                                <h5>Reasons for denial</h5>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" name="isBanned" id="banned" <?php if ($isBanned) echo "checked" ?> disabled />
                                    <label class="custom-control-label" for="banned">
                                        <h6 class="font-weight-normal">
                                            User is Banned from DTCC
                                        </h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" id="badCharacterName" name="badCharacterName" value="true" />
                                    <label class="custom-control-label" for="badCharacterName">
                                        <h6 class="font-weight-normal">Bad Character Name</h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" id="badPhoneNumber" name="badPhoneNumber" />
                                    <label class="custom-control-label" for="badPhoneNumber">
                                        <h6 class="font-weight-normal">Bad Phone Number</h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" id="badDiscordName" name="badDiscordName" />
                                    <label class="custom-control-label" for="badDiscordName">
                                        <h6 class="font-weight-normal">Bad Discord Username</h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" name="badProfileLink" id="badProfileLink" <?php if (!$detected_steam_id) echo "checked"; ?> disabled />
                                    <label class="custom-control-label" for="badProfileLink">
                                        <h6 class="font-weight-normal">Bad Profile Link</h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" id="badBackstory" name="badBackstory" />
                                    <label class="custom-control-label" for="badBackstory">
                                        <h6 class="font-weight-normal">Bad Backstory</h6>
                                    </label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" id="badReason" name="badReason" />
                                    <label class="custom-control-label" for="badReason">
                                        <h6 class="font-weight-normal">Bad Reason</h6>
                                    </label>
                                </div>

                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <textarea placeholder="Additional Information" class="form-control" name="additionalInformation" id="additionalInformation" rows="5"></textarea>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input class="custom-control-input" type="checkbox" name="reapplyDaysSwitch" id="reapplyDaysSwitch" data-toggle="collapse" data-target="#reapplyDaysGroup" aria-expanded="false" aria-controls="reapplyDaysSwitch" />
                                    <label class="custom-control-label" for="reapplyDaysSwitch">
                                        <h6 class="font-weight-normal" type="button">Reapply?</h6>
                                    </label>
                                </div>
                                <div class="collapse" id="reapplyDaysGroup">
                                    <div class="input-group my-2 w-75">
                                        <input type="text" class="form-control" name="reapplyDaysAmount" placeholder="How many days?" aria-label="Days" aria-describedby="basic-addon2" id="reapplyDaysAmount" value="0" />
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">Days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger" <?php if ($_SESSION["rank"] < 2) echo "disabled" ?>>
                                    Reject Applicant
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Go Back
                                </button>
                            </div>
                            <input name="SubmitApp" value="deny" hidden></input>
                            <?php if ($isBanned) {
                                echo "<input name='isBanned' value='" . $isBanned . "' hidden></input>";
                            }
                            ?>
                            <?php if (!$detected_steam_id) {
                                $detected_steam_id = "";
                                echo "<input name='badProfileLink' value='1' hidden></input>";
                            } ?>

                            <input name="appid" value="<?php echo $appid; ?>" hidden></input>
                            <input name="detected_steam_name" value="<?php echo $detected_steam_name; ?>" hidden></input>
                            <input name="detected_steam_id" value="<?php echo $detected_steam_id; ?>" hidden></input>
                            <input name="phone_number" value="<?php echo $phone_number; ?>" hidden></input>
                            <input name="char_name" value="<?php echo $char_name; ?>" hidden></input>
                            <input name="discord_name" value="<?php echo $discord_name; ?>" hidden></input>
                            <input name="timezone" value="<?php echo $zone; ?>" hidden></input>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>