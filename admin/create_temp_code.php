<?php
include "../include/sqlconnection.php";
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
    <div class="border-p4">
        Your password to the DTCC Dashboard has been reset. Your new password is: ``` <?php echo $_POST["txtNewPass"] ?>```
    </div>
<?php
    //header("Location: ../index.php");
}

include "../include/footer.php";
