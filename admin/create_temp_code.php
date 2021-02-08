<?php
include "../include/components/head.php";
include "../include/elements.php";




if (isset($_POST["SteamID"])) {
    set_temp_code($_POST["SteamID"], $_POST["txtNewPass"]);

?>
<div class="container">
    <h6> Send this message to <?=q_fetchPlayer($_POST["SteamID"])->discord_name ?></h6>
    <p class="border-p4">
        Your password to the DTCC Dashboard has been reset.<br>
        All passwords are stored in an encrypted format, and cannot be retrieved by staff.<br>
        Please use this code next time you login:<br>
        ``` <?php echo $_POST["txtNewPass"] ?>```
        Your code will only work once, after which you will be redirected to reset your password.<br>
        Thanks!
    </p>
    </div>
<?php
    //header("Location: ../index.php");
}

include "../include/components/foot.php";
