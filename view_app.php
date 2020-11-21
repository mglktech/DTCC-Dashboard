<?php include "include/header.php"; ?>

<!-- must first include appid from html request -->
<?php


function PrepareSteamURL($steam_link)
{
    require_once "steam/SteamUser.php";


    $split = explode("/", $steam_link);
    if (count($split) >= 4) {
        $user = new SteamUser($split[4]);
    } else {
        $user = NULL;
    }
    return $user;

    // auto-checks for vanity url
    //print_r($user);

}

function chkPostBool($id)
{
    if (isset($_POST[$id])) {
        return 1;
    } else {
        return 0;
    }
}
function chkPost($id)
{
    if (isset($_POST[$id])) {
        return $_POST[$id];
    } else {
        return "";
    }
}

function UpdateDB($sql)
{
    include_once "include/db_connection.php";
    $response = SqlRun($sql);
    return $response;
}

if (isset($_GET["appid"])) {
    $appid = $_GET["appid"];
}

if (isset($_POST["SubmitApp"])) {
    $date = time();
    $tz = $_POST['timezone'];
    $timezone = null;
    if ($tz == "GMT (Europe/London)") {
        $timezone = "GMT";
    }
    if ($tz == "Eastern (US/Canada)") {
        $timezone = "EST";
    }
    $appid = $_POST['appid'];
    $isBanned = FALSE;
    $detected_steam_id = $_POST["detected_steam_id"];
    $detected_steam_name = $_POST['detected_steam_name'];
    $phone_number = $_POST["phone_number"];
    $char_name = $_POST["char_name"];
    $discord_name = $_POST["discord_name"];
    $signed_by = $_SESSION["steam_id"];
    if ($_POST["SubmitApp"] == "approve") {
        $status = "accept";
        $sql = "UPDATE `applications_v0`
         SET `steam_id`='$detected_steam_id',
         `detected_steam_name`='$detected_steam_name',
         `signed_by`='$signed_by',
         `status`='$status',
         `status_desc`='',
         `additional_info`='' 
         WHERE `app_id`='$appid'";
        $response = UpdateDB($sql);
       // echo "Database Response: " . $response . " SQL: " . $sql;
    }

    if ($_POST["SubmitApp"] == "deny") {

        $isBanned = chkPostBool("isBanned");
        $badCharName = chkPostBool("badCharacterName");
        $badPNum = chkPostBool("badPhoneNumber");
        $badDiscord = chkPostBool("badDiscordName");
        $badLink = chkPostBool("badProfileLink");
        $badBackstory = chkPostBool("badBackstory");
        $badReason = chkPostBool("badReason");
        $reapplySwitch = chkPostBool("reapplyDaysSwitch");
        $reapplyDays = chkPost("reapplyDaysAmount");
        $status_desc =  $isBanned . "/" . $badCharName . "/" . $badPNum . "/" . $badDiscord . "/" . $badLink . "/" . $badBackstory . "/" . $badReason . "/" . $reapplySwitch . "/" . $reapplyDays;
        $additionalInfo = chkPost("additionalInformation");
        $status = "deny";
        $sql = "UPDATE `applications_v0`
         SET `steam_id`='$detected_steam_id',
         `detected_steam_name`='$detected_steam_name',
         `signed_by`='$signed_by',
         `status`='$status',
         `status_desc`='$status_desc',
         `additional_info`='$additionalInfo' 
         WHERE `app_id`='$appid'";
        $response = UpdateDB($sql);
        //echo "Database Response: " . $response . " SQL: " . $sql;
    }
    if ($detected_steam_id) {
        if ($isBanned) {
            $sql = "REPLACE INTO `players`(`steam_id`,`phone_number`,`steam_name`,`discord_name`,`char_name`,`status`,`last_seen`) VALUES('$detected_steam_id','$phone_number','$detected_steam_name','$discord_name','$char_name','BANNED','$date')";
        } else {
            if ($status == "accept") {
                $sql = "REPLACE INTO `players`(`steam_id`,`phone_number`,`steam_name`,`discord_name`,`char_name`,`status`,`last_seen`,`timezone`) VALUES('$detected_steam_id','$phone_number','$detected_steam_name','$discord_name','$char_name','Needs Theory','$date','$timezone')";
            } else {
                $sql = "REPLACE INTO `players`(`steam_id`,`phone_number`,`steam_name`,`discord_name`,`char_name`,`last_seen`) VALUES('$detected_steam_id','$phone_number','$detected_steam_name','$discord_name','$char_name',$date)";
            }
        }
        $response = UpdateDB($sql);
        if ($response == "failure") {
            //echo "ERROR: SQL Failure. SQL: " . $sql;
        }
    }
}


include_once "include/db_connection.php";
$appdata = fetchRow("SELECT * FROM applications_v0 WHERE app_id = $appid");
// print_r($appdata[0]);
$id = $appdata[0];

// $timestamp = $appdata[1];
$char_name = $appdata[2];
$phone_number = $appdata[3];
$steam_name = $appdata[10];
$discord_name = $appdata[5];
$steam_link = $appdata[6];
$backstory = $appdata[7];
$reason = $appdata[8];
$signed_by = $appdata[11];
$status = $appdata[12];
$status_desc = $appdata[13];
$additionalInfo = $appdata[14];
if ($appdata[15] == 0) {
    $timestamp = $appdata[1];
}
if ($appdata[15] != 0) {
    $timestamp = date("d-M-Y", round($appdata[15] / 1000));
}
$zone = $appdata[16];
$player = PrepareSteamURL($steam_link);
if ($player) {
    $detected_steam_name = $player->steamID;
    $detected_steam_id = $player->steamID64;
    $steam_name = $player->steamID;
}
$alive = IsAlive($char_name);


/*
build accept button with validation modal
*/


/*
appid 32 good example of private profile
Cannot accept an application without valid steamid, SO
inform user that this is a problem
allow user to input a steamid for that applicant before continuing
otherwise, disable accept button
*/
include "include/elements.php";
?>
<div class="container-fluid">
    <!-- APPLICATION FORM -->
    <div class="row">
        <div class="col p-0 pb-3 mb-3 rounded">
            <h1>Downtown Cab Co. Application</h1>
            <h5>Version 0</h5>
            <br>
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        CreateInputElemFull(SpanPrepend("Name: "), SpanMiddleDefault($char_name), SpanIsAlive($alive));
                        CreateInputElem("Date:", $timestamp, "");
                        CreateInputElem("Steam Name:", $steam_name, "");
                        CreateInputElem("Phone:", $phone_number, "");
                        CreateInputElem("Discord:", $discord_name, "");
                        CreateInputElem("Timezone:", $zone, "");
                        CreateInputElemFull(SpanPrepend("Steam Link: "), SpanMiddleDefault($steam_link), SpanBtnLink("Go", $steam_link)); ?>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-1">Backstory:</h5>
                        <div class="border p-4">
                            <span class="font-weight-normal"><?php echo $backstory; ?></span>
                        </div>
                        </h5>
                        <h5 class="mt-3 mb-1">Why do you want to join Downtown Cab Co?</h5>
                        <div class="border p-4">
                            <span class="font-weight-normal"><?php echo $reason; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF APPLICATION -->

    <?php if (!$signed_by) {
        include("include/inc_view_app_unsigned.php");
    }
    if ($signed_by) {
        include("include/inc_view_app_signed.php");
    }
    ?>

</div>

<?php include_once "include/footer.php"; ?>