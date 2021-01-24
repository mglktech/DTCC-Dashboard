<?php
include "include/sqlconnection.php";
include "include/elements.php";
include "steam/SteamWebAPI_Simple.php";


// function getAvatars($steam_id)
// {
//     require_once "steam/SteamUser.php";
//     $user = new SteamUser($steam_id);
//     //$_SESSION['profile_pic'] = $user->avatarIcon;
//     $avIcon = $user->avatarIcon;
//     $avMedium = $user->avatarMedium;
//     $avFull = $user->avatarFull;
//     $sql = "UPDATE players
//     SET av_icon='$avIcon',av_medium='$avMedium',av_full='$avFull' WHERE steam_id='$steam_id'";
//     Query($sql);
// }
function chkOnline()
{
    $resp = Query("SELECT * FROM public_players LIMIT 1");
    if ($resp) {
        return true;
    } else {
        return false;
    }
}

function BeginSession($player, $temp)
{
    //getAvatars($player->steam_id);
    session_start();
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

if (isset($_POST["Login"])) {

    $user = quotefix($_POST["txtUserName"]);
    $pass = quotefix($_POST["txtPassword"]);
    $response = check_temp_code($user, $pass);
    if ($response) {
        BeginSession(q_fetchPlayer(getSteamID($user)), true);
    }
    if (!$response) {
        $response2 = check_password($user, $pass);
        if ($response2) {
            BeginSession(q_fetchPlayer(getSteamID($user)), false);
        } else {
            $error = "Incorrect Username/password combination.";
        }
    }
}
include "include/_header.php";
?>
<form method="post" target="">
    <div class="col">
        <div class='input-group mb-3 '>
            <div class='input-group-prepend'>
                <span class='input-group-text bg-secondary text-light'>Username</span>
            </div>
            <input class='form-control' value='' name='txtUserName' data-toggle="tooltip" data-placement="right" title="Your Steam Name">
        </div>
        <div class='input-group mb-3 '>
            <div class='input-group-prepend'>
                <span class='input-group-text bg-secondary text-light'>Password</span>
            </div>
            <input class='form-control' type='password' value='' name='txtPassword'>
        </div>

        <input type="submit" name="Login" class="btn btn-secondary" value="Login">
    </div>
</form>

<label>MySQL is: <?php if (chkOnline()) {
                        echo "online";
                    } else {
                        echo "offline";
                    } ?></label>

<?php include "include/footer.php";
