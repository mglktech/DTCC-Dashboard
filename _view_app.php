<?php include "include/header.php"; ?>

<!-- must first include appid from html request -->
<?php
$appid = $_GET["appid"];

function PrepareStatus($phone_number)
{
    include_once "include/db_connection.php";
    $sql = "SELECT `status` FROM `players` WHERE `phone_number` = '$phone_number'";
    $status_prepare = fetchAll($sql);
    print_r($sql);
    print_r($status_prepare);
    if ($status_prepare) {
        if ($status_prepare[0][0] == "BANNED") {
            return $status_prepare[0][0];
        }
    } else {
        return "Not Banned";
    }
}
include_once "include/db_connection.php";
$appdata = fetchAll("SELECT * FROM Applications_v0 WHERE app_id = $appid");
// print_r($appdata[0]);
$id = $appdata[0][0];
$timestamp = $appdata[0][1];
$char_name = $appdata[0][2];
$phone_number = $appdata[0][3];
$steam_name = $appdata[0][4];
$discord_name = $appdata[0][5];
$steam_link = $appdata[0][6];
$backstory = $appdata[0][7];
$reason = $appdata[0][8];
$status = PrepareStatus($phone_number);


?>
<div class="col-6">
    <p>
        <h3><?php echo $char_name; ?>'s Application</h3>
        <h5 class="mb-3">
            Status:
            <span class="font-weight-normal"><?php echo $status; ?></span>
        </h5>
        <h5 class="mb-3">
            Applied On:
            <span class="font-weight-normal"><?php echo $timestamp; ?></span>
        </h5>
        <h5 class="mb-3">
            Steam Name:
            <span class="font-weight-normal"><?php echo $steam_name; ?></span>
        </h5>
        <h5 class="mb-3">
            Phone Number:
            <span class="font-weight-normal"><?php echo $phone_number; ?></span>
        </h5>
        <h5 class="mb-3">
            Discord:
            <span class="font-weight-normal"><?php echo $discord_name; ?></span>
        </h5>
        <h5 class="mb-3">
            Steam Profile:
            <a href="<?php echo $steam_link; ?>" target="_blank"><span class="font-weight-normal"><?php echo $steam_link; ?></span></a>
        </h5>
        <h5 class="mb-4">
            Backstory:<br /><span class="font-weight-normal"><?php echo $backstory; ?></span>
        </h5>
        <h5 class="mb-4">
            Why do you want to join Downtown Cab Co?<br /><span class="font-weight-normal"><?php echo $reason; ?></span>
        </h5>
    </p>



</div>




<a type="button" class="btn btn-secondary float-right" data-dismiss="modal" href="applications.php">Back</a>

<?php include_once "include/footer.php"; ?>