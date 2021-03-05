<?php include '../include/components/head.php';

isset($_POST["witness"]) ? $witness_id = $_POST["witness"] : $witness_id = null;

function InitPrep($test_type,$test_version,$student_steamid)
{
    $student = QueryFirst("SELECT * FROM `public_players` WHERE `steam_id` = '$student_steamid'");
    $test_data = Query("SELECT * FROM `test_questions` WHERE `type` = '$test_type' and `version` = '$test_version' ORDER BY `q_number`");   
    include "test_".$test_type.".php";
}


if (isset($_GET['type'])) {
    $test_type = $_GET['type'];
    $student_steamid = $_GET['steamid'];
    $version = $_GET['ver'];
    InitPrep($test_type,$version,$student_steamid);
    // $sql = "SELECT `char_name`,`discord_name`,`timezone` FROM `players` WHERE `steam_id` = '$student_steamid'";
    // $applicant = Query($sql)[0];
    // $char_name = $applicant->char_name;
    // $discord_name = $applicant->discord_name;
    // $region = $applicant->timezone;
    

    // if ($test_type == "theory") {
    //     if(Query("SELECT * FROM `globals` where `name` = 'enable_test_witnessing'")[0]->value == 1 && $witness_id == null)
    //     {
    //         include "interview_pre1.php";
    //     }
    //     else
    //     {
    //         include "theory_test_new.php";
    //     }
    // }
    // if ($test_type == "practical") {
    //     include "practical_test_new.php";
    // }




}
?>



<?php include '../include/components/foot.php';
