<?php

function sql($command, $table, $params, $outparams){
    $command = "SELECT";
    $table = "players";
    $params = [steam_id : "9023759027572", rank:"<3"];
    $outparams ["char_name","steam_id"];

    if($command == "SELETC"){
        //create select statmet
        $sql = $command . " " . $outparams . " WHERE " . $params;
    }

}


function p_OpenCon()
{
    $dbhost = "localhost";
    $dbuser = "id15098854_dtcc";
    $dbpass = "ao|=vda19gvOlpBs";
    $db = "id15098854_dtccdb";
    try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$db", $dbuser, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    return $conn;
}

function CloseCon($conn)
{
    $conn->close();
}


function SqlRun($sql)
{
    $conn = OpenCon();
    $query = $conn->query($sql);
    if ($query === TRUE) {
        return "Success";
    } else {
        return "failure";
    }
}

function fetchAll($sql)
{
    $conn = OpenCon();
    $query = $conn->query($sql);
    if ($query) {
        return $query->fetch_all();
    } else {
        return false;
    }
    CloseCon($conn);
}


function fetchRow($sql)
{
    $conn = OpenCon();
    $query = $conn->query($sql);
    if ($query) {
        return $query->fetch_row();
    } else {
        return false;
    }
    CloseCon($conn);
}
function fetchPlayerFormatted($steam_id)
{
    $player = fetchPlayer($steam_id);
    return $player[1] . " | " . $player[0];
}

function fetchPlayer($steam_id)
{
    $sql = "SELECT `char_name`,`callsign`,`rank` FROM players WHERE steam_id = '$steam_id'";
    $conn = OpenCon();
    $query = $conn->query($sql);
    if ($query) {
        return $query->fetch_row();
    } else {
        return false;
    }
    CloseCon($conn);
}
