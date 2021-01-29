<?php
include "include/sqlconnection.php";
include "steam/SteamWebAPI_Simple.php";


function chkOnline()
{
    $resp = Query("SELECT * FROM public_players LIMIT 1");
    if ($resp) {
        return true;
    } else {
        return false;
    }
}
function BeginSession($player, $temp, $pw)
{
    //getAvatars($player->steam_id);
    session_start();
    $_SESSION['password'] = $pw;
    $_SESSION['steam_id'] = $player->steam_id;
    $_SESSION['steam_name'] = $player->steam_name;
    $_SESSION['callsign'] = $player->callsign;
    $_SESSION['char_name'] = $player->char_name;
    $_SESSION['rank'] = $player->rank;
    $_SESSION['phone_number'] = $player->phone_number;
    $_SESSION['av_icon'] = $player->av_icon;
    if ($temp) {
        header("Location: admin/change_password.php");
    }
    if (!$temp) {
        header("Location: index.php");
    }
}

function response($r = null)
{

}

if (isset($_POST["Login"])) {

    $user = quotefix($_POST["steam_name"]);
    $pass = quotefix($_POST["password"]);
    $response = check_temp_code($user, $pass);
    if ($response) {
        BeginSession(q_fetchPlayer(getSteamID($user)), true, $pass);
    }
    if (!$response) {
        $response2 = check_password($user, $pass);
        if ($response2) {
            BeginSession(q_fetchPlayer(getSteamID($user)), false, $pass);
        } else {
            $error = "Incorrect Username/password combination.";
        }
    }
}

include "include/components/head.php";
?>


<div class="container-fluid d-flex justify-content-center justify-content-sm-center align-items-sm-center justify-content-lg-center" style="min-height: 39vh;">
    <form method="post" style="text-align: center;padding: 57px;max-width: 392px;height: 278px;"><label style="color: rgb(255,255,255);">Please Login Below.</label>
    <label style="color: rgb(255,255,255);"></label>
        <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
        <div class="form-group"><input class="form-control" type="text" name="steam_name" placeholder="Steam Name"></div>
        <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password"></div>
        <div class="form-group"><button class="btn btn-primary btn-block " name="Login" type="submit" style="border-style: none;background: rgb(34,77,124);">Log In</button></div>
    </form>
</div>

<label>MySQL is: <?php if (chkOnline()) {
                        echo "online";
                    } else {
                        echo "offline";
                    } ?></label>

<?php include "include/components/foot.php";
