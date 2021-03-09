<?php
include "../../include/components/head.php";


isset($_SESSION["steam_id"]) ? $id = $_SESSION["steam_id"] : $id = null;
isset($_POST["id"]) ? $student_id = $_POST["id"] : $student_id = null;
isset($_POST["comments"]) ? $comments = quotefix($_POST["comments"]) : $comments = null;
isset($_POST["SubmitDocument"])  ? $flag = true : $flag = false;

if ($flag) {
    $time = time();
    Query("INSERT INTO `training_sessions` (`student_id`,`signed_by`,`comments`) VALUES('$student_id','$id','$comments')");
    Query("UPDATE `players` SET `instructor_trained` = '1' WHERE `steam_id` = '$student_id'");
}

?>

<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-center">
            <h4>All Done!</h4>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex justify-content-center">
            <p class="text-center">Your document has been submitted.<br>Thanks!</p>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex justify-content-center"><a class="btn btn-secondary btn-pad" role="button" href="/training/day_one/table_day_one.php">Finish</a></div>
    </div>
</div>
<?php include "../../include/components/foot.php";
