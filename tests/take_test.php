<?php include '../include/header.php';
include '../include/db_connection.php';
if (isset($_GET['type'])) {
    $test_type = $_GET['type'];
    $student_steamid = $_GET['steamid'];
    $sql = "SELECT `char_name`,`discord_name`,`timezone` FROM `players` WHERE `steam_id` = '$student_steamid'";
    $applicant = fetchRow($sql);
    $char_name = $applicant[0];
    $discord_name = $applicant[1];
    $region = $applicant[2];

    if ($test_type == "theory") {
        include "theory_test.php";
    }
    if ($test_type == "practical") {
        include "practical_test.php";
    }
}

?>



<?php include_once "../include/footer.php"; ?>
