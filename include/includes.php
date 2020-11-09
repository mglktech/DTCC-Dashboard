<!DOCTYPE html>
<html>

<head>
  <base target="_top" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <!-- Custom CSS -->
  <title>Downtown Cab Co. Dashboard</title>
  <!-- Bootstrap core CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://bobby-playground.s3.amazonaws.com/css/dashboard.css" />
  <link rel="stylesheet" href="/include/styles.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/f98d92a3e6.js" crossorigin="anonymous"></script>


</head>
<?php
// PHP include on every page

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