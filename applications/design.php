<?php include "../include/header.php";
include "../include/elements.php";


$app_class = new StdClass();
$app_info = PrepareContent($app_class);

function PrepareContent($app_info)
{
    $app_info->date_submitted = "";
    $app_info->version = "0.1.0";
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
    $app_info->signed_by = "T1-02 | Matt Connors";
    $app_info->signed_date = "01/01/2021";

    return $app_info;
}


include("elems/app_content.php");
include("elems/app_accepted.php");
?>




<?= include "../include/footer.php"; ?>