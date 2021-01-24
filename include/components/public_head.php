<?php



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


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Downtown Cab Co. Dashboard</title>
    <link rel="stylesheet" href="include/components/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bad+Script">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="include/components/assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="include/components/assets/css/Header-Blue.css">
    <link rel="stylesheet" href="include/components/assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="include/components/assets/css/styles.css">
</head>

<body style="background: rgb(35,39,41);text-align: center;">

    <!-- PAGE HEADER -->
    <div class="container d-lg-flex justify-content-lg-start align-items-lg-center" style="width: 100%;max-width: 100%;background: linear-gradient(rgb(34,77,124), rgba(255,255,255,0));">
        <div class="row d-lg-flex justify-content-lg-start align-items-lg-center" style="width: auto;">
            <div class="col d-flex d-lg-flex justify-content-center align-items-center justify-content-lg-start" style="height: 82px;margin: 0px;"><img src="include/components/assets/img/logo.png"></div>
            <div class="col d-flex d-lg-flex justify-content-center align-items-center justify-content-lg-center align-items-lg-center" style="text-align: center;max-width: 373px;">
                <h3 class="d-flex align-items-center" style="color: rgb(251,251,251);font-family: 'Bad Script', cursive;">Dashboard</h3>
            </div>
        </div>
    </div>