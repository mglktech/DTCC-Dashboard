<?php include "../include/header.php";
include "../include/sqlconnection.php";

function CreateReasonPhrase($reason)
{
    $str = "";
    if ($reason == "inactivity") {
        $str = "You have not turned up for work in a while.";
    }
    if ($reason == "too_many_strikes") {
        $str = "You have received too many Strikes.";
    }
    if ($reason == "resigned") {
        $str = "You have resigned.";
    }
    if ($reason == "misconduct") {
        $str = "Gross Misconduct";
    } else {
        $str = $_POST["otherreason"];
    }
    return $str;
}
$text = "";
$BanFire = "Fired";
$banned = 0;
if (isset($_POST["FireMe"])) {
    $steam_id = $_POST["SteamID"];
    $signed_by = $_POST["SignedBy"];
    $timestamp = time();
    $reason = $_POST['reason'];
    if ($reason == "other") {
        $reason = $_POST["otherreason"];
    }
    $add_info = $_POST["additionalInformation"];
    if (isset($_POST["ban"])) {
        $BanFire = "Banned";
        $banned = 1;
    }
    $sql = "INSERT INTO fired (timestamp,steam_id,reason,signed_by,add_info) VALUES('$timestamp','$steam_id','$reason','$signed_by','$add_info')";
    Query($sql);
    $sql = "UPDATE players 
    SET `status`='$BanFire',
    `code` = null,
    `pw_hash` = null,
    `rank` = '-1'
    WHERE `steam_id` = '$steam_id'";
    Query($sql);
    $sql = "UPDATE callsigns 
    SET `assigned_steam_id` = null
    WHERE `assigned_steam_id` = '$steam_id'";
    Query($sql);
    // echo "<br>Steam ID: " . $steam_id;
    // echo "<br>Signed By: " . $signed_by;
    // echo "<br>Timestamp: " . $timestamp;
    // echo "<br>reason: " . $reason;
    // echo "<br>Is Banned: " . $banned;
    // echo "<br>add_info: " . $add_info;
}
?>

<div class="container">
    <div class="row">
        <h4><?php echo $_POST["char_name"] ?> has been <?php echo $BanFire ?> from Downtown Cab Co.</h4>
    </div>
    <div class="row">
        <div class="col border">
            <h6>Send this message to <?php echo $_POST['DiscordName'] ?></h6>
            <p>Hello <?php echo $_POST["char_name"] ?> <br>
                We regret to inform you that you have been <?php echo $BanFire ?> from Downtown Cab Co.<br>
                The reason given is as follows: <br>
                <?php echo CreateReasonPhrase($reason); ?><br>
                <?php if ($banned) { ?>
                    You are not permitted to reapply, if you do, your application will be rejected automatically.<br>
                <?php } else { ?>
                    You are permitted to re-apply at a later date if you wish, our staff are informed of your previous employment with us.<br>
                <?php } ?>
                <br>
                Signed, <?php echo q_fetchPlayerFormatted($signed_by); ?><br>
                <?php if ($add_info != "") { ?>
                    Additional Notes: <br>
                <?php echo $add_info;
                } ?>
            </p>
        </div>
        <div class="col border">
            <h6> Send this message to #database-todos</h6>
            <p>
                Please remove all Downtown Cab Co. tags from @<?php echo explode("#", $_POST['DiscordName'])[0] ?> <br>
                Thank you!
            </p>

        </div>
    </div>
</div>

<?php include "../include/footer.php"; ?>