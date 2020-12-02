<?php include "include/sqlconnection.php";




if (isset($_POST['steam_url'])) {
    $steam_url = quotefix($_POST['steam_url']);
    $discord_name = quotefix($_POST['discord_name']);
    $char_name = quotefix($_POST['char_name']);
    $phone_number = quotefix($_POST['phone_number']);
    $backstory = quotefix($_POST['backstory']);
    $reason = quotefix($_POST['reason']);
    $timestamp = quotefix($_POST['app_timestamp']);
    $timeOffset = quotefix($_POST['app_zoneOffset']);


    // echo $steam_url;
    // echo $discord_name;
    // echo $char_name;
    // echo $phone_number;
    // echo $backstory;
    // echo $reason;
    $sql = "INSERT INTO applications_v0 (
        `char_name`, 
    `phone_number`, 
    `discord_name`, 
    `steam_link`, 
    `char_backstory`, 
    `char_reason`,
    `app_timestamp`,
    `app_zoneOffset`,
    `applicant_dob`) VALUES ('$char_name','$phone_number','$discord_name','$steam_url','$backstory','$reason','$timestamp','$timeOffset','$dob')";
    $response = Query($sql);
    echo $response;
    echo " SQL: " . $sql;
}
