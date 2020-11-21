<?php
include "include/header.php";
include "include/sqlconnection.php";


if (isset($_POST['leaveNote'])) {
    $time = time();
    $doc_type = "application";
    $doc_id = $_POST['app_id'];
    $steam_id = $_POST['signed_by'];
    $message = quotefix($_POST['message']);
    $sql = "INSERT INTO `private_notes`(`doc_id`, `doc_type`, `steam_id`, `timestamp`, `message`) VALUES ('$doc_id','$doc_type','$steam_id','$time','$message')";
    Query($sql);
}

function prepareNotes($doc_id)
{
    $sql = "SELECT * FROM `notes` WHERE `doc_id` = '$doc_id' ORDER BY `timestamp` DESC";
    return Query($sql);
}

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

    $response = Query($sql);
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
         `additional_info`='',
         `signed_timestamp`='$date'
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
        $additionalInfo = quotefix(chkPost("additionalInformation"));
        $status = "deny";
        $sql = "UPDATE `applications_v0`
         SET `steam_id`='$detected_steam_id',
         `detected_steam_name`='$detected_steam_name',
         `signed_by`='$signed_by',
         `status`='$status',
         `status_desc`='$status_desc',
         `additional_info`='$additionalInfo',
         `signed_timestamp`='$date'
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



$appdata = Query("SELECT * FROM applications_v0 WHERE app_id = $appid")[0];
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

function CreateNotesTable($appid)
{
    $str_heap = array();
    $tbl_head = ["Notes"];
    $data = prepareNotes($appid);
    if ($data) {


        foreach ($data as $row) {
            $str_stack = array();
            $str = $row->char_name . ": " . $row->message . " -" . ToDateS($row->timestamp);
            $str_stack[] = $str;
            $str_heap[] = $str_stack; // one dimensional array...
        }
    } else {
        $str_heap = [["No Notes..."]];
    }

    return Tablefy($tbl_head, $str_heap);
}

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

                        <div class="">

                            <span class="font-weight-normal"><?php CreateNotesTable($appid); ?></span>
                            <button class="btn bg-secondary text-light" data-toggle="modal" data-target="#NoteModal">Add Note</button>
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
<div class="modal fade" id="NoteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adding note for <?php echo $char_name ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>
                        Leave a Note for other supervisors to see!
                    </h6>
                    <h6 class="">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group">
                                    <textarea name="message" class="form-control" aria-label="With textarea"></textarea>
                                </div>
                            </div>


                        </div>


                    </h6>
                    <h6 class="mb-3">

                    </h6>
                </div>
                <div class="modal-footer">

                    <input name="leaveNote" value="1" hidden></input>
                    <input name="signed_by" value="<?php echo $_SESSION["steam_id"]; ?>" hidden></input>
                    <input name="app_id" value="<?php echo $appid; ?>" hidden></input>
                    <button type="submit" class="btn btn-success">Leave Note</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>


                </div>
            </div>
        </div>
    </form>
</div>

<?php include_once "include/footer.php"; ?>