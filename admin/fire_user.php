<?php include "../include/components/head.php";


function CreateReasonPhrase($reason)
{

    if ($reason == "inactivity") {
        $str = "You have not turned up for work in a while.";
    } else if ($reason == "too_many_strikes") {
        $str = "You have received too many Warnings.";
    } else if ($reason == "resigned") {
        $str = "You have resigned.";
    } else if ($reason == "misconduct") {
        $str = "Gross Misconduct";
    } else if ($reason == "inactivity_recruit") {
        $str = "You have not passed your tests in time.";
    } else if ($reason == "failure_recruit") {
        $str = "You have failed too many tests.";
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
    if (isset($_POST["ban"]) || isset($_POST["banR"])) {
        $BanFire = "Banned";
        $banned = 1;
    }
    $sql = "INSERT INTO `fired` (`timestamp`,`steam_id`,`reason`,`signed_by`,`add_info`,`banned`) VALUES('$timestamp','$steam_id','$reason','$signed_by','$add_info','$banned')";
    Query($sql);
    $sql = "UPDATE `players`
    SET `status`='$BanFire',
    `code` = null,
    `pw_hash` = null,
    `rank` = null
    WHERE `steam_id` = '$steam_id'";
    Query($sql);
    $sql = "UPDATE `callsigns` 
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
        <?php if (isset($_POST["recruit"])) { ?>
            <h4><?php echo $_POST["char_name"] ?> has been removed from Downtown Cab Co. Recruitment.</h4>
        <?php } else { ?>
            <h4><?php echo $_POST["char_name"] ?> has been <?php echo $BanFire ?> from Downtown Cab Co.</h4>
        <?php } ?>

    </div>
    <div class="row">
        <div class="col border">
            <h6>Send this message to <?php echo $_POST['DiscordName'] ?></h6>
            Hello <?php echo $_POST["char_name"] ?> <br>
            <?php if (isset($_POST["recruit"])) { ?>
                We regret to imform you that you have been Removed from Downtown Cab Co. Recruitment.<br>
            <?php } else { ?>
                We regret to inform you that you have been <?php echo $BanFire ?> from Downtown Cab Co.<br>
            <?php } ?>
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

<?php include "../include/components/foot.php"; ?>