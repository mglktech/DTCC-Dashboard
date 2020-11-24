<?php
include "../include/sqlconnection.php";
include "../include/elements.php";
if (isset($_POST["ChangePW"])) {
    if ($_POST["txtNewPass"] == $_POST["txtNewPassConfirm"] && $_POST["txtNewPass"] != '') {
        set_temp_code($_POST["txtSteamName"], $_POST["txtNewPass"]);
        //header("Location: ../index.php");
    } else {
        echo "Passwords do not match!";
    }
}
include "../include/_header.php";
?>
<form method="post" target="">
    make pass
    <div class="col-3">
        <div class='input-group mb-3 '>
            <div class='input-group-prepend'>
                <span class='input-group-text bg-secondary text-light'>Steam Name</span>
            </div>
            <input class='form-control' value='' name='txtSteamName'>
        </div>
        <div class='input-group mb-3 '>
            <div class='input-group-prepend'>
                <span class='input-group-text bg-secondary text-light'>New Password</span>
            </div>
            <input class='form-control' type='password' value='' name='txtNewPass'>
        </div>
        <div class='input-group mb-3 '>
            <div class='input-group-prepend'>
                <span class='input-group-text bg-secondary text-light'>Confirm New Password</span>
            </div>
            <input class='form-control' type='password' value='' name='txtNewPassConfirm'>
        </div>

        <input type="submit" name="ChangePW" class="btn btn-secondary" value="Change">
    </div>
</form>
<?php include "../include/footer.php";
