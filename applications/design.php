<?php include "../include/components/head.php";

include "../include/elements.php";
include "../steam/SteamWebAPI_Simple.php";
isset($_GET['doc_id']) ? $doc_id = $_GET['doc_id'] : $doc_id = -1;
$app_info = PrepareContent($doc_id);



function PrepareContent($doc_id)
{
    $app_info = new StdClass();
    $app = Query("SELECT * FROM applications_v0 WHERE app_id = '$doc_id'");
    if ($app) {
        $data = $app[0];
        $app_info->date_submitted = toDateS($data->app_timestamp);
        $app_info->name = $data->char_name;
        $app_info->phone = $data->phone_number;
        $app_info->discord = $data->discord_name;
        $app_info->DOB = $data->applicant_dob;
        $app_info->SteamID = ResolveSteamID($data->steam_link);
        $app_info->steam_name = GetSteamDetails($app_info->SteamID)->steam_name;
        $app_info->steam_link = $data->steam_link;
        $app_info->Zone = $data->app_zoneOffset;
        $app_info->Info = "";
        $app_info->backstory = $data->char_backstory;
        $app_info->reason = $data->char_reason;

        $app_info->signed_by = $data->signed_by;
        $app_info->signed_date = toDateS($data->signed_timestamp);
        $app_info->status = $data->status;
        $app_info->status_desc = explode("/", $data->status_desc);
        $app_info->additional_info = $data->additional_info;
    } else {

        $app_info->date_submitted = "";
        $app_info->name = "";
        $app_info->phone = "";
        $app_info->discord = "";
        $app_info->DOB = "";
        $app_info->steam_name = "";
        $app_info->steam_link = "";
        $app_info->SteamID = "";
        $app_info->Zone = "";
        $app_info->Info = "";
        $app_info->backstory = "";
        $app_info->reason = "";

        $app_info->signed_by = "";
        $app_info->signed_date = "";
        $app_info->status = "";
        $app_info->status_desc = [];
        $app_info->additional_info = "";
    }


    return $app_info;
}




?>
<section class="document-form">
    <div class="container-fluid">
        <?php include("elems/app_content.php");
        include("elems/app_sign.php");
        ?>
    </div>
</section>



<?php include "../include/components/foot.php"; ?>