<?php

include "../include/elements.php";
include "../include/header.php";

function setCode($steam_name, $code)
{
    set_temp_code($_POST["txtSteamName"], $_POST["txtNewPass"]);
}

if (isset($_POST["ChangePW"])) {
    setCode($_POST["txtSteamName"], $_POST["txtNewPass"]);
?>
    <h6> Send this message to <?php echo $_POST['discord_name']; ?></h6>
    <p class="border-p4">
        Your password to the DTCC Dashboard has been reset.<br>
        All passwords are stored in an encrypted format, and cannot be retrieved by staff.<br>
        Please use this code next time you login:<br>
        ``` <?php echo $_POST["txtNewPass"] ?>```
        Your code will only work once, after which you will be redirected to reset your password.<br>
        Thanks!
    </p>
<?php
    //header("Location: ../index.php");
}

include "../include/footer.php";
