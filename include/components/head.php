<?php

$public_pages = ["login.php"];
if (!in_array(basename($_SERVER['PHP_SELF']), $public_pages)) {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["token"])) {
        $_SESSION["redirect"] = $_SERVER['REQUEST_URI'];
        //echo "not logged in";
        ReturnToLogin();
    }
    else
    {
        CollectLoginData($_SESSION["token"]);
    }
}

function ReturnToLogin()
{
    header("Location: ../login.php");
}

function CollectLoginData($token)
{
    include_once("include/sqlconnection.php");
    $data = Query("SELECT * FROM sessions WHERE session_token = '$token'");
    if($data)
    {
        $d = $data[0];
        $_SESSION["user"] = $d->session_user;
        $_SESSION["rank"] = $d->session_rank;
        $_SESSION["steam_id"] = $d->session_steamid;
    }
    else {
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


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Downtown Cab Co. Dashboard</title>
    <link rel="stylesheet" href="/include/components/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Charmonman:400,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Indie+Flower">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="/include/components/assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="/include/components/assets/css/styles.css">
    <script src="https://kit.fontawesome.com/f98d92a3e6.js" crossorigin="anonymous"></script>


</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean navbar-dark" style="background: linear-gradient(rgb(34,76,123) 0%, rgba(255,255,255,0));">
        <div class="container"><a class="navbar-brand text-center" href="#" style="font-family: 'Indie Flower', cursive;color: rgb(217,217,217);"><img class="d-md-flex" src="/include/components/assets/img/logo.png" style="margin-right: 8px;height: 49px;">Dashboard</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1" style="border-style: none;"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="/index.php" style="color: rgb(217,217,217);"><i class="icon ion-ios-home-outline" style="margin-right: 4px;"></i>Home</a></li>
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: rgb(217,217,217);"><i class="icon ion-clipboard" style="margin-right: 4px;"></i>Applications</a>
                        <div class="dropdown-menu"><a class="dropdown-item" href="/applications/table_apps.php">Unread</a><a class="dropdown-item" href="/applications/table_apps_archive.php">Archive</a></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/players.php" style="color: rgb(217,217,217);"><i class="icon ion-ios-person-outline" style="margin-right: 4px;"></i>Roster</a></li>
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: rgb(217,217,217);"><i class="icon ion-ios-folder-outline" style="margin-right: 4px;"></i>Tests</a>
                        <div class="dropdown-menu"><a class="dropdown-item" href="/tests/table_needs_theory.php">Theory</a><a class="dropdown-item" href="/tests/table_needs_practical.php">Practical</a><a class="dropdown-item" href="/tests/table_tests_archive.php">Archive</a></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/shifts/shifts_index.php" style="color: rgb(217,217,217);"><i class="far fa-clock" style="margin-right: 4px;"></i>Shifts</a></li>
                </ul>
            </div>
        </div>
    </nav>