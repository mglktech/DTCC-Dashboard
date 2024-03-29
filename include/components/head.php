<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . "/include/sqlconnection.php";


function page_enabled($page_name)
{
    // defaults to true if not found in metas table
    // returns false if found and enabled is 0
    $enabled_flag = QueryFirst("SELECT * FROM `site_page_metas` WHERE `page_name` = '$page_name'");
    if ($enabled_flag) {
        if (!$enabled_flag->enabled) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

function Initialize()
{
    $public_pages = ["login.php"];

    if (!in_array(basename($_SERVER['PHP_SELF']), $public_pages)) { // IF page is not a public page

        if (session_status() == PHP_SESSION_NONE) { // if no session exists
            session_start();
        }

        if (!isset($_SESSION["token"])) { // if token is not set
            $_SESSION["redirect"] = $_SERVER['REQUEST_URI'];
            //echo "not logged in";
            ReturnToLogin();
        }
        if (!page_enabled($_SERVER['PHP_SELF'])) {
            ReturnPageDisabled();
        }
        CollectLoginData($_SESSION["token"]);
        include "session_head.php";
    } else {
        include "public_head.php";
    }
}

function ReturnPageDisabled()
{
    header("Location: ../include/disable_stub.php");
}

function ReturnToLogin()
{
    header("Location: ../login.php");
}

function CollectLoginData($token)
{
    $data = Query("SELECT * FROM sessions WHERE session_token = '$token'");
    $t = time();
    if ($data) {
        $d = $data[0];
        $_SESSION["user"] = $d->session_user;
        $_SESSION["rank"] = $d->session_rank;
        $_SESSION["steam_id"] = $d->session_steamid;
        Query("UPDATE sessions SET session_last_active = '$t' WHERE `session_id` = '$d->session_id'");
    } else {
        ReturnToLogin();
    }
}

function toDateS($timestr)
{

    if (isTimestamp($timestr)) {
        if ($timestr != 0) {
            return date("d-M-Y", $timestr);
        } else {
            return "-";
        }
    } else {
        return "-";
    }
}

function toDate($timestr)
{

    if (isTimestamp($timestr)) {
        if ($timestr != 0) {
            return date("d-M-Y", round($timestr / 1000));
        } else {
            return "-";
        }
    } else {
        return "-";
    }
}

function toDateTime($timestr)
{

    if (isTimestamp($timestr)) {
        return date("d-M-Y - h:i:s A T", $timestr);
    } else {
        return "-";
    }
}

function toTime($timestr)
{
    if (isTimestamp($timestr)) {
        return date("h:i A", $timestr);
    } else {
        return "-";
    }
}

function toDurationDays($timestr)
{

    return gmdate('z\d\a\y\s, G\h\r\s \a\n\d i\m\i\n\s', $timestr);
}

function toDurationHours($timestr)
{

    return gmdate('G\h\r\s i\m\i\n\s', $timestr);
}

function isTimestamp($string)
{
    try {
        new DateTime('@' . $string);
    } catch (Exception $e) {
        return false;
    }
    return true;
}
function Path($subdir)
{
    $path = $_SERVER["DOCUMENT_ROOT"];
    $path .= $subdir;
    return $path;
}

// Finally...
Initialize();
