<?php include "../include/components/head.php";

include "../include/elements.php";
include "../steam/SteamWebApi.php";


isset($_GET['doc_id']) ? $doc_id = $_GET['doc_id'] : $doc_id = -1;
$app_info = PrepareContent($doc_id);

if (isset($_POST["SubmitApp"])) {
    $ap = new stdClass();
    $ap->date = time();
    $ap->app_id = quotefix($_POST["app_id"]);
    $ap->char_name = quotefix($_POST["char_name"]);
    $ap->phone_number = quotefix($_POST["phone_number"]);
    $ap->discord_name = quotefix($_POST["discord_name"]);
    $ap->timezone = quotefix($_POST["timezone"]);
    $ap->backstory = quotefix($_POST["backstory"]);
    $ap->steam_link = quotefix($_POST["steam_link"]);
    $ap->steam_name = quotefix($_POST["steam_name"]);
    $ap->steam_id = quotefix($_POST["steam_id"]);
    $ap->signed_by = $_SESSION["steam_id"];
    $ap->av_full = quotefix($_POST["av_full"]);
    if ($_POST["SubmitApp"] == "accept") {
        AppAccept($ap);
    }
    if ($_POST["SubmitApp"] == "deny") {
        AppDeny($ap);
    }
    if ($_POST["SubmitApp"] == "ignore") {
        AppIgnore($ap);
    }
    header("Refresh: 0");
}





function AppAccept($ap)
{

    $ap->status = "accept";
    $ap->status_desc = "";
    $ap->add_info = "";

    if (PlayerValidate($ap->steam_id) == null) // if player isn't currently employed by us
    {
        $ap->pStatus = "Needs Theory";
    } else {
        $ap->pStatus = PlayerValidate($ap->steam_id);
    }
    UpdateApplication($ap);
    UpdatePlayer($ap);
}
function UpdateApplication($ap)
{
    $sql = "UPDATE applications_v0
        SET `steam_id`='$ap->steam_id',
        `detected_steam_name`='$ap->steam_name',
        `signed_by`='$ap->signed_by',
        `status`='$ap->status',
        `status_desc`='$ap->status_desc',
        `additional_info`='$ap->add_info',
        `signed_timestamp`='$ap->date'
        WHERE `app_id`='$ap->app_id'";
    if (AppStillUnsigned($ap->app_id)) {
        Query($sql);
    }
}

function UpdatePlayer($ap)
{
    $validate = QueryFirst("SELECT * FROM `players` WHERE `steam_id` = '$ap->steam_id'");

    if ($validate) {
        in_array($validate[0]->status, ["Needs Theory", "Needs Practical", "Active"]) ? $ap->pStatus = $validate->status : $ap->pStatus = $ap->pStatus;
        if ($ap->status == "accept") {
            $sql = "UPDATE `players` SET
        `phone_number` = '$ap->phone_number',
        `steam_name` = '$ap->steam_name',
        `discord_name` = '$ap->discord_name',
        `char_name` = '$ap->char_name',
        `status` = '$ap->pStatus',
        `rank` = '-1',
        `last_seen` = '$ap->date',
        `timezone` = '$ap->timezone',
        `av_full` = '$ap->av_full',
        `backstory` = '$ap->backstory' WHERE `steam_id` = '$ap->steam_id'";
        } else {
            $sql = "UPDATE `players` SET
        `phone_number` = '$ap->phone_number',
        `steam_name` = '$ap->steam_name',
        `discord_name` = '$ap->discord_name',
        `char_name` = '$ap->char_name',
        `status` = '$ap->pStatus',
        `last_seen` = '$ap->date',
        `timezone` = '$ap->timezone',
        `av_full` = '$ap->av_full',
        `backstory` = '$ap->backstory' WHERE `steam_id` = '$ap->steam_id'";
        }
    } else {
        $sql = "REPLACE INTO players 
    (`steam_id`,`phone_number`,`steam_name`,`discord_name`,`char_name`,`timezone`,`av_full`,`backstory`) 
    VALUES(
        '$ap->steam_id',
        '$ap->phone_number',
        '$ap->steam_name',
        '$ap->discord_name',
        '$ap->char_name',
        '$ap->timezone',
        '$ap->av_full',
        '$ap->backstory')";
    }


    Query($sql);
}
function AppDeny($ap)
{
    $ap->status = "deny";
    $ap->status_desc = MakeDesc();
    $ap->add_info = quotefix(chkPost("txt-addinfo"));
    UpdateApplication($ap);

    if ($ap->steam_id) {
        if (chkPostBool("csw-banned")) {
            $ap->pStatus = "Banned";
        }
        UpdatePlayer($ap);
    }
}
function AppIgnore($ap)
{
    $ap->status = "ignore";
    $ap->status_desc = "";
    $ap->add_info = "";
    UpdateApplication($ap);
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


function MakeDesc()
{
    $isBanned = chkPostBool("csw-banned");
    $badCharName = chkPostBool("csw-char_name");
    $badPNum = chkPostBool("csw-phone");
    $badDiscord = chkPostBool("csw-discord");
    $badLink = chkPostBool("csw-steamid");
    $badBackstory = chkPostBool("csw-backstory");
    $badReason = chkPostBool("csw-reason");
    if ($isBanned) {
        $reapplySwitch = 0;
    } else {
        $reapplySwitch = 1;
    }

    $reapplyDays = chkPost("txt-days");
    $status_desc =  $isBanned . "/" . $badCharName . "/" . $badPNum . "/" . $badDiscord . "/" . $badLink . "/" . $badBackstory . "/" . $badReason . "/" . $reapplySwitch . "/" . $reapplyDays;
    return $status_desc;
}

function PlayerValidate($id)
{
    $sql = "SELECT `rank`, `status` FROM `players` WHERE `steam_id` = '$id'";
    $result = Query($sql);
    if (isset($result[0])) {
        if ($result[0]->rank >= 0) {
            return $result[0]->status;
        } else {
            return null;
        }
    } else {
        return null;
    }
}

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


function PrepareContent($doc_id)
{
    $app_info = new StdClass();
    $app = Query("SELECT * FROM applications_v0 WHERE app_id = '$doc_id'");
    if ($app) {
        $data = $app[0];
        $app_info->date_submitted = toDate($data->app_timestamp);
        $app_info->name = $data->char_name;
        $app_info->phone = $data->phone_number;
        $app_info->discord = $data->discord_name;
        $app_info->DOB = $data->applicant_dob;
        $app_info->SteamID = ResolveSteamID($data->steam_link);
        $app_info->steam_name = GetSteamDetails($app_info->SteamID)->steam_name;
        $app_info->av_full = GetSteamDetails($app_info->SteamID)->av_full;
        $app_info->steam_link = $data->steam_link;
        $app_info->Zone = $data->app_zoneOffset;
        $app_info->Info = "";
        $app_info->backstory = $data->char_backstory;
        $app_info->reason = $data->char_reason;
        $app_info->signed_by = q_fetchPlayerFormatted($data->signed_by);
        $app_info->signed_date = toDateS($data->signed_timestamp);
        $app_info->status = $data->status;
        $app_info->status_desc = explode("/", $data->status_desc);
        $app_info->additional_info = $data->additional_info;
    } else {

        $app_info->date_submitted = NULL;
        $app_info->name = NULL;
        $app_info->phone = NULL;
        $app_info->discord = NULL;
        $app_info->DOB = NULL;
        $app_info->steam_name = NULL;
        $app_info->steam_link = NULL;
        $app_info->SteamID = NULL;
        $app_info->Zone = NULL;
        $app_info->Info = NULL;
        $app_info->backstory = NULL;
        $app_info->reason = NULL;

        $app_info->signed_by = NULL;
        $app_info->signed_date = NULL;
        $app_info->status = NULL;
        $app_info->status_desc = NULL;
        $app_info->additional_info = NULL;
    }


    return $app_info;
}




?>
<section class="d-flex justify-content-center">
    <div class="container-fluid document-form">
        <?php include("elems/app_content.php");
        if ($app_info->signed_by) {
            if ($app_info->status == "accept") {
                include("elems/app_accepted.php");
            }
            if ($app_info->status == "deny") {
                include("elems/app_denied.php");
            }
            if ($app_info->status == "ignore") {
                include("elems/app_ignored.php");
            }
        } else {

            if (Rank("Supervisor")) {
                include("elems/app_sign.php");
                if (IsAlreadyEmployed($app_info->SteamID)) {

                    //include("../players/elems/player_infobtns.php");
                    echo "This player is already a member of Downtown Cab Co. <br> DO NOT sign this application until this member has been removed properly.";
                }
            } else {
                include("elems/app_sign_disabled.php");
            }
        }
        ?>
    </div>
</section>




<?php include "../include/components/foot.php"; ?>