<?php
// purpose of this page is to echo timestamp of last document received by type, then let GAS determine what needs to be sent, then add missing records to the database.
include "../include/sqlconnection.php";
//first collect $sheet as a POST
$sheet = $_POST["sheet"];
// then, collect $db_table
$db_table = PopulateTable();
// then, sync table with sheet
SyncAll($db_table, $sheet);

function PopulateTable($type = "applications_v0")
{
    $sql = "SELECT * FROM $type";
    return Query($sql);
}
function SyncAll($db_table, $sheet)
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
    echo json_decode($sheet);
}

function FirstTime_SyncAll($db_table, $sheet)
{
}
