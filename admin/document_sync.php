<?php
// purpose of this page is to echo timestamp of last document received by type, then let GAS determine what needs to be sent, then add missing records to the database.
include "../include/sqlconnection.php";
//first collect $sheet as a POST
$sheet = $_POST["sheet"];
// then, collect $db_table
print_r($_POST["sheet"]);
// then, sync table with sheet


function SyncAll($sheet)
{
    /*
        we want the database to contain everything from the sheet, and if something's missing, add it in as a new app.
        foreach row of sheet,
        if
        char_name,
        discord_name,
        steam_link,
        phone_number,
        backstory,
        and reason are in the database
        (set db timestamp to sheet timestamp)
        set sheet appid to db appid
    */
    // $RowIDs = array();

    // foreach ($sheet as $key => $row) {
    //     $char_name = $row[1];
    //     $discord_name = $row[2];
    //     $steam_link = $row[3];
    //     $phone_number = $row[4];
    //     $backstory    = $row[5];
    //     $reason = $row[6];
    //     $sql = "SELECT app_id FROM applications_v0 WHERE char_name='$char_name'
    //     AND discord_name = '$discord_name' AND steam_link='$steam_link' AND phone_number='$phone_number' AND backstory='$backstory' AND reason='$reason'";
    //     $result = Query($sql);
    //     if ($result) {
    //         $app_id =  $result[1]->app_id;
    //         $RowIDs[] = [$key, $app_id];
    //     }
    // }
    // echo $RowIDs;
    echo json_encode($sheet);
}

function FirstTime_SyncAll($db_table, $sheet)
{
}
