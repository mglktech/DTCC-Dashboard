<?php include "../include/components/head.php";

include "../include/elements.php";
isset($_POST['tab']) ? $tab = quotefix($_POST['tab']) : $tab = "PastWeek";
isset($_POST['IncludeStaff']) ? $inc_staff = quotefix($_POST['IncludeStaff']) : $inc_staff = "on";
$cururl = $_SERVER["REQUEST_URI"];
function getLastUpdated()
{
    $sql = "SELECT MAX(`timestamp`) AS max FROM `_verified_shifts`";
    $result = Query($sql)[0]->max;
    return date("F j, Y, g:i a", $result);
}

/*
CREATE VIEW _public_verified_shifts AS 
SELECT
    `id15098854_dtccdb`.`_verified_shifts`.`id` AS `id`,
    `id15098854_dtccdb`.`_verified_shifts`.`server` AS `server`,
    `id15098854_dtccdb`.`_verified_shifts`.`steam_id` AS `steam_id`,
    `id15098854_dtccdb`.`_verified_shifts`.`time_in` AS `time_in`,
    `id15098854_dtccdb`.`_verified_shifts`.`time_out` AS `time_out`,
    `id15098854_dtccdb`.`_verified_shifts`.`duration` AS `duration`,
    `id15098854_dtccdb`.`_verified_shifts`.`timestamp` AS `timestamp`,
    `id15098854_dtccdb`.`_verified_shifts`.`signed_by` AS `signed_by`,
    `id15098854_dtccdb`.`callsigns`.`label` AS `callsign`,
    `id15098854_dtccdb`.`players`.`char_name` AS `char_name`,
    `id15098854_dtccdb`.`players`.`discord_name` AS `discord_name`,
    `id15098854_dtccdb`.`players`.`rank` AS `rank`
    FROM `id15098854_dtccdb`.`_verified_shifts`
    LEFT JOIN `id15098854_dtccdb`.`callsigns` ON `id15098854_dtccdb`.`callsigns`.`assigned_steam_id` = `id15098854_dtccdb`.`_verified_shifts`.`steam_id`
    LEFT JOIN `id15098854_dtccdb`.`players` on `id15098854_dtccdb`.`players`.`steam_id` = `id15098854_dtccdb`.`_verified_shifts`.`steam_id`  
ORDER BY `time_in` DESC

*/

function PopulateTab($timeframe, $inc_staff)
{
    if ($inc_staff == "on") {
        $inc = "";
    }
    if ($inc_staff == "off") {
        $inc = " and a.rank<2";
    }

    if ($timeframe == "AllTime") {

        $time_inc = "";
        //$sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id) duration FROM public_verified_shifts a  where a.callsign is not null" . $inc . " order by duration desc LIMIT 10";
    }
    if ($timeframe == "PastWeek") {
        $curtime = time();
        $oneTime = 604800; //one week in seconds
        $TimeAgo = $curtime - $oneTime;
        $time_inc = " and b.time_out > '$TimeAgo'";
        //$sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id and b.time_out > '$TimeAgo') duration FROM public_verified_shifts a  where a.callsign is not null" . $inc . " order by duration desc LIMIT 10";
    }
    if ($timeframe == "PastMonth") {
        $curtime = time();
        $oneTime = 2629743; //one month in seconds
        $TimeAgo = $curtime - $oneTime;
        $time_inc = " and b.time_out > '$TimeAgo'";
        //$sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id and b.time_out > '$TimeAgo') duration FROM public_verified_shifts a  where a.callsign is not null" . $inc . " order by duration desc LIMIT 10";
    }
    $sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM `_public_verified_shifts` b WHERE b.steam_id=a.steam_id" . $time_inc . ") duration FROM `_public_verified_shifts` a  where a.callsign is not null" . $inc . " order by duration desc LIMIT 10";
    $tData =  Query($sql);
    $thead = ["", "Name", "Time Clocked In"];
    $tbody = array();
    if ($tData) {
        foreach ($tData as $index => $row) {
            $tRow = array();
            $tRow[] = $index + 1;
            $tRow[] = $row->callsign . " | " . $row->char_name;
            $tRow[] = toDurationDays($row->duration);
            $tbody[] = $tRow;
        }
    }
    Tablefy($thead, $tbody);
}
function IncludeInfoBtn($tab)
{
    if ($tab == "PastWeek") {
        echo "<a class='btn btn-sm btn-primary mb-3' href='shifts_index_PastWeek.php'>More Info</a>";
    }
    if ($tab == "PastMonth") {
        echo "<a class='btn btn-sm btn-primary mb-3' href='shifts_index_PastMonth.php'>More Info</a>";
    }
    if ($tab == "AllTime") {
        echo "<a class='btn btn-sm btn-primary mb-3' href='shifts_index_AllTime.php'>More Info</a>";
    }
}
function IncludeStaffBtn($inc_staff)
{
    echo "<a class='btn btn-success' href='?IncludeStaff=off'>btn</a>";
}

?>

<div class="container">
    <h4>Top Time Spent On-Shift</h4>
    <h6>Last Updated: <?php echo getLastUpdated(); ?></h6>
    <ul class="nav nav-tabs">

        <li class="nav-item">
            <form method="post">
                <input name="IncludeStaff" value="<?= $inc_staff ?>" hidden>
                <input name="tab" value="PastWeek" hidden>
                <button class="nav-link <?php if ($tab == "PastWeek") echo "active" ?>" type="submit">
                    <h5>Past Week</h5>
                </button>
            </form>
        </li>
        <li class="nav-item">
            <form method="post">
                <input name="IncludeStaff" value="<?= $inc_staff ?>" hidden>
                <input name="tab" value="PastMonth" hidden>
                <button class="nav-link <?php if ($tab == "PastMonth") echo "active" ?>" type="submit">
                    <h5>Past Month</h5>
                </button>
            </form>
        </li>
        <li class="nav-item">
            <form method="post">
                <input name="IncludeStaff" value="<?= $inc_staff ?>" hidden>
                <input name="tab" value="AllTime" hidden>
                <button class="nav-link <?php if ($tab == "AllTime") echo "active" ?>" type="submit">
                    <h5>All Time</h5>
                </button>
            </form>
        </li>

        <div class="container align-self-center">

            <form method="post">

                <?php if ($inc_staff == "on") {
                ?>
                    <input name="IncludeStaff" value="off" hidden>
                    <input name="tab" value="<?= $tab ?>" hidden>
                    <button class='btn btn-success' type="submit">Include Staff</button>
                <?php } else {

                ?>
                    <input name="IncludeStaff" value="on" hidden>
                    <input name="tab" value="<?= $tab ?>" hidden>
                    <button class='btn btn-danger' type="submit">Include Staff</button>
                <?php } ?>

            </form>

        </div>
    </ul>

    <?php PopulateTab($tab, $inc_staff);
    IncludeInfoBtn($tab);
    ?>



    <?php
    if ($_SESSION["rank"] >= 3) {
    ?>
        Senior Supervisor Commands:<br>
        <button class="btn bg-danger text-light mb-2" data-toggle="modal" data-target="#chkUpdateSteamDetails">Refresh Steam Names</button>
        <button class="btn bg-danger text-light mb-2" data-toggle="modal" data-target="#chkCleanBlankShifts">Clean Blank Shifts</button>
        <a class='btn btn-secondary  mb-2' href='/shifts/upload_shifts.php'>Upload Shift Data</a>
        <a class='btn btn-secondary  mb-2' href='/shifts/table_unver_shifts.php'>Verify Shifts</a>
        <div class="modal fade" id="chkUpdateSteamDetails" tabindex="-1" role="dialog" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Refresh Steam Names</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        You are about to refresh Steam Names for <b>The Entire Database</b>. are you sure you want to do this?<br>
                        This may take a while to complete. Do not refresh the page!
                    </div>
                    <div class="modal-footer">

                        <a href="/admin/update_steam_details.php" class="btn btn-success">Do it</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>

                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="chkCleanBlankShifts" tabindex="-1" role="dialog" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Clean Blank Shifts</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <b>WARNING: </b> You should only run this command <i>after</i> you have refreshed Steam Names. are you sure you want to do this?
                    </div>
                    <div class="modal-footer">

                        <a href="/dev/BlankSteamIDCleanup.php" class="btn btn-success">Do it</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>

                    </div>
                </div>
            </div>

        </div>
</div>
<?php }
    include "../include/components/foot.php";
