<?php

include "include/sqlconnection.php";
if (isset($_POST["Login"])) {
    session_start();
    //echo $_POST["txtInput"];
    $_SESSION['code'] = $_POST["txtInput"];
    $row = DoLogin($_SESSION['code']);
    //var_dump($row);
    if ($row) {
        $_SESSION['steam_id'] = $row->steam_id;
        $_SESSION['steam_name'] = $row->steam_name;
        $_SESSION['char_name'] = $row->char_name;
        $_SESSION['rank'] = $row->rank;
        $_SESSION['phone_number'] = $row->phone_number;
        require_once "steam/SteamUser.php";
        $user = new SteamUser($_SESSION['steam_id']);
        $_SESSION['profile_pic'] = $user->avatarIcon;

        header("Location: applications.php");
    } else {
        //echo "Login unsuccessful!";
        header("Location: login.php");
    }
}

function DoLogin($code)
{
    $code = quotefix($code); // security
    return Query("SELECT * FROM `players` WHERE `code` = '$code'")[0];
}
include 'include/_header.php';
?>

<h4>Please input your 6 digit code so we know who you are.</h4>
<form action="" method="post">
    <input type="password" name="txtInput" />
    <input type="submit" name="Login" class="btn btn-secondary" value="Login">
</form>
<?php include 'include/footer.php'; ?>