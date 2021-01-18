<?php include "include/sqlconnection.php";

isset($_POST['doc_type']) ? $doc_type = $_POST['doc_type'] : $doc_type = null;

if ($doc_type = 'application') {
    $app = new stdClass();
    $app->steam_url = quotefix($_POST['steam_url']);
    $app->discord_name = quotefix($_POST['discord_name']);
    $app->char_name = quotefix($_POST['char_name']);
    $app->phone_number = quotefix($_POST['phone_number']);
    $app->backstory = quotefix($_POST['backstory']);
    $app->reason = quotefix($_POST['reason']);
    $app->timestamp = quotefix($_POST['app_timestamp']);
    $app->timeOffset = quotefix($_POST['app_zoneOffset']);
    $app->dob = quotefix($_POST['applicant_dob']);
    $result =  SubmitApplication($app);
    echo $result;
}

function FindApplication($app)
{
    $sql = "SELECT app_id FROM applications_v0 WHERE 
    (steam_url = '$app->steam_url' 
    AND discord_name = '$app->discord_name' 
    AND char_name = '$app->char_name'
    AND phone_number = '$app->phone_number'
    AND backstory = '$app->backstory'
    AND reason = '$app->reason'
    
    AND app_zoneOffset = '$app->timeOffset'
    AND applicant_dob = '$app->dob')";
    // AND app_timestamp = '$app->timestamp'
    return Query($sql);
}

function SubmitApplication($app)
{
    $Found = FindApplication($app);

    if ($Found) {
        return $Found[0]->app_id;
    } else {
        $sql = "INSERT IGNORE INTO applications_v0 (
            `char_name`, 
        `phone_number`, 
        `discord_name`, 
        `steam_link`, 
        `char_backstory`, 
        `char_reason`,
        `app_timestamp`,
        `app_zoneOffset`,
        `applicant_dob`) VALUES 
        ('$app->char_name','$app->phone_number','$app->discord_name','$app->steam_url','$app->backstory','$app->reason','$app->timestamp','$app->timeOffset','$app->dob')";
        Query($sql);

        return FindApplication($app)[0]->app_id;
    }
}
