<?php
include "../include/components/head.php";
if (isset($_POST["ChangePW"])) {
    if ($_POST["txtNewPass"] == $_POST["txtNewPassConfirm"] && $_POST["txtNewPass"] != '') {
        $resp = set_password($_POST["steam_id"], $_POST["txtNewPass"]);
        //echo $resp;
        header("Location: ../home.php");
    } else {
        echo "Passwords do not match!";
    }
}
?>
<form method="post" target="">
    You have logged in with a temporary code. Please choose a more permanent password!
    <div class="col-6">
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
        <input name="ChangePW" value="" hidden>
        <input name="steam_id" value='<?php echo $_SESSION['steam_id'] ?>' hidden>
        <input type="submit" name="ChangePW" class="btn btn-secondary" value="Change">
    </div>
</form>

<?php include "../include/components/foot.php";
