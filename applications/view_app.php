<?php
include "../include/header.php";
include "../include/sqlconnection.php";


$doc_type = "application";

function AppStillUnsigned($doc_id)
{
    $sql = "SELECT * FROM applications_v0 WHERE app_id = '$doc_id' AND signed_by is null";
    $result = Query($sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}


function PrepareSteamURL($steam_link)
{
    require_once "../steam/SteamUser.php";


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

    $response = Query($sql);
    return $response;
}

if (isset($_GET["doc_id"])) {
    $doc_id = $_GET["doc_id"];
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
    $doc_id = $_POST['doc_id'];
    $isBanned = FALSE;
    $detected_steam_id = $_POST["detected_steam_id"];
    $detected_steam_name = $_POST['detected_steam_name'];
    $phone_number = $_POST["phone_number"];
    $char_name = $_POST["char_name"];
    $discord_name = $_POST["discord_name"];
    $signed_by = $_SESSION["steam_id"];
    if ($_POST["SubmitApp"] == "approve") {
        $status = "accept";
        $sql = "UPDATE applications_v0
        SET `steam_id`='$detected_steam_id',
        `detected_steam_name`='$detected_steam_name',
        `signed_by`='$signed_by',
        `status`='$status',
        `status_desc`='',
        `additional_info`='',
        `signed_timestamp`='$date'
        WHERE `app_id`='$doc_id'";
        if (AppStillUnsigned($doc_id)) { // prevent duplicate app signature from supers
            $response = UpdateDB($sql);
        }

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
        $additionalInfo = quotefix(chkPost("additionalInformation"));
        $status = "deny";
        $sql = "UPDATE applications_v0
        SET `steam_id`='$detected_steam_id',
        `detected_steam_name`='$detected_steam_name',
        `signed_by`='$signed_by',
        `status`='$status',
        `status_desc`='$status_desc',
        `additional_info`='$additionalInfo',
        `signed_timestamp`='$date'
        WHERE `app_id`='$doc_id'";
        if (AppStillUnsigned($doc_id)) { // prevent duplicate app signature from supers
            $response = UpdateDB($sql);
        }
        //echo "Database Response: " . $response . " SQL: " . $sql;
    }
    if ($detected_steam_id) {
        if ($isBanned) {
            $sql = "REPLACE INTO players (`steam_id`,`phone_number`,`steam_name`,`discord_name`,`char_name`,`status`,`last_seen`) VALUES('$detected_steam_id','$phone_number','$detected_steam_name','$discord_name','$char_name','BANNED','$date')";
        } else {
            if ($status == "accept") {
                $sql = "REPLACE INTO players (`steam_id`,`phone_number`,`steam_name`,`discord_name`,`char_name`,`status`,`last_seen`,`timezone`) VALUES('$detected_steam_id','$phone_number','$detected_steam_name','$discord_name','$char_name','Needs Theory','$date','$timezone')";
            } else {
                $sql = "REPLACE INTO players (`steam_id`,`phone_number`,`steam_name`,`discord_name`,`char_name`,`last_seen`) VALUES('$detected_steam_id','$phone_number','$detected_steam_name','$discord_name','$char_name',$date)";
            }
        }
        $response = UpdateDB($sql);
        if ($response == "failure") {
            //echo "ERROR: SQL Failure. SQL: " . $sql;
        }
    }
}



$appdata = Query("SELECT * FROM applications_v0 WHERE app_id = '$doc_id'")[0];
// print_r($appdata[0]);
$id = $appdata->app_id;

// $timestamp = $appdata[1]; 
$char_name = $appdata->char_name;
$phone_number = $appdata->phone_number;
$steam_name = $appdata->detected_steam_name;
$discord_name = $appdata->discord_name;
$steam_link = $appdata->steam_link;
$backstory = $appdata->char_backstory;
$reason = $appdata->char_reason;
$signed_by = $appdata->signed_by;
$status = $appdata->status;
$status_desc = $appdata->status_desc;
$additionalInfo = $appdata->additional_info;

if ($appdata->app_timestamp == 0) {
    $timestamp = toDateS(strToTime($appdata->timestamp));
}
if ($appdata->app_timestamp != 0) {
    $timestamp = toDate($appdata->app_timestamp);
}
$zone = $appdata->app_zoneOffset;
$player = PrepareSteamURL($steam_link);
if ($player) {
    $detected_steam_name = $player->steamID;
    $detected_steam_id = $player->steamID64;
    $steam_name = $player->steamID;
}
$alive = IsAlive($char_name);

// Notes



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
include "../include/elements.php";
include "../include/inc_notes.php";


?>
<div class="container-fluid">
    <!-- APPLICATION FORM -->
    <div class="row">
        <div class="col p-0 pb-0 mb-0 rounded">
            <h1>Downtown Cab Co. Application</h1>
            <h5 class="font-italic mb-0 font-weight-normal"><strong>Issued on:</strong> <?php echo $timestamp; ?> - Version: 0.1.0</h5>
            <br>
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        CreateInputElemFull(SpanPrepend("Employee Name: "), SpanMiddleDefault($char_name), SpanIsAlive($alive));
                        CreateInputElem("Phone Number:", $phone_number, "");
                        CreateInputElem("Discord:", $discord_name, "");
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?php
                        CreateInputElem("Steam Name:", $steam_name, "");
                        CreateInputElem("Timezone:", $zone, "");
                        CreateInputElemFull(SpanPrepend("Steam Link: "), SpanMiddleDefault($steam_link), SpanBtnLink("Go", $steam_link));
                        ?>
                    </div>

                    <div class="col-md-6">
                        <h5 class="h5-header-label mb-0 w-100 text-center">Character Backstory</h5>
                        <div class="border p-4 text-background-grey architects-font">
                            <span class="font-weight-normal"><?php echo $backstory; ?></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        </h5>
                        <h5 class="h5-header-label mb-0 w-100 text-center">Reason for Applying</h5>
                        <div class="border p-4 text-background-grey architects-font">
                            <span class="font-weight-normal"><?php echo $reason; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 px-0 pt-3 pb-3">
                    <span class="font-weight-normal"><?php CreateNotesTable($doc_id, $doc_type); ?></span>
                    <button class="btn btn-secondary" data-toggle="modal" data-target="#NoteModal">Add Note</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END OF APPLICATION -->

<?php
if (!$signed_by) {
    include("../include/inc_view_app_unsigned.php");
}
if ($signed_by) {
    include("../include/inc_view_app_signed.php");
}

?>


<?php include "../include/footer.php"; ?>