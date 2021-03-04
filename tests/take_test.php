<?php include '../include/header.php';
include '../include/sqlconnection.php';
if (isset($_GET['type'])) {
    $test_type = $_GET['type'];
    $student_steamid = $_GET['steamid'];
    $sql = "SELECT `char_name`,`discord_name`,`timezone` FROM `players` WHERE `steam_id` = '$student_steamid'";
    $applicant = Query($sql)[0];
    $char_name = $applicant->char_name;
    $discord_name = $applicant->discord_name;
    $region = $applicant->timezone;

    if ($test_type == "theory") {
        include "theory_test_new.php";
    }
    if ($test_type == "practical") {
        include "practical_test_new.php";
    }
}

?>



<?php include "../include/footer.php"; ?>
