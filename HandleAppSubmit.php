<?php 

if (isset($_POST['steam_url'])) {
    $steam_url = quotefix($_POST['steam_url']);
    $discord_name = quotefix($_POST['discord_name']);
    $char_name = quotefix($_POST['char_name']);
    $phone_number = quotefix($_POST['phone_number']);
    $backstory = quotefix($_POST['backstory']);
    $reason = quotefix($_POST['reason']);
    $timestamp = quotefix($_POST['app_timestamp']);
    $timeOffset = quotefix($_POST['app_zoneOffset']);
    $dob = quotefix($_POST['applicant_dob']);
    // echo $steam_url;
    // echo $discord_name;
    // echo $char_name;
    // echo $phone_number;
    // echo $backstory;
    // echo $reason;
    // AutoReject previous unsigned applications
    $sql = "SELECT * FROM unread_apps WHERE steam_url = '$steam_url'";
    $response = Query($sql)[0];
    if ($response) {
        // previous application found, AutoReject it.
        $doc_id = $response->app_id;
        $date = time();
        $author = "Automatic";
        $reasons = "0/0/0/0/0/0/0/0/0";
        $add_info = "Multiple apps sent";
        $sql = "UPDATE applications_v0
        SET signed_by='$author',
        `status` = 'deny',
        status_desc='$reasons',
        additional_info='$add_info',
        signed_timestamp='$date'
        WHERE app_id = '$doc_id'";
        Query($sql);
    }
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
