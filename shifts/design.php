<?php include "../include/header.php";
include "../include/sqlconnection.php";
include "../include/elements.php";
isset($_POST['tab']) ? $tab = quotefix($_POST['tab']) : $tab = "PastWeek";
isset($_POST['IncludeStaff']) ? $inc_staff = quotefix($_POST['IncludeStaff']) : $inc_staff = "on";
$cururl = $_SERVER["REQUEST_URI"];
function getLastUpdated()
{
    $sql = "SELECT MAX(`timestamp`) AS max FROM `public_verified_shifts`";
    $result = Query($sql)[0]->max;
    return date("F j, Y, g:i a", $result);
}

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
    $sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id" . $time_inc . ") duration FROM public_verified_shifts a  where a.callsign is not null" . $inc . " order by duration desc LIMIT 10";
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

<div class="container-fluid-p0">
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
</div>



<?= include "../include/footer.php"; ?>