<?php include "../include/header.php";
include "../include/sqlconnection.php";
include "../include/elements.php";






function getLastUpdated()
{
    $sql = "SELECT MAX(`timestamp`) AS max FROM `public_verified_shifts`";
    $result = Query($sql)[0]->max;
    return date("F j, Y, g:i a", $result);
}

function sqlCollectTotalShiftTimes($timeframe)
{
    if ($timeframe == "all-time") {
        $sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id) duration FROM public_verified_shifts a  where a.callsign is not null order by duration desc";
    }
    if ($timeframe == "this-week") {
        $curtime = time();
        $oneWeek = 604800; //one week in seconds
        $WeekAgo = $curtime - $oneWeek;
        $sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, a.rank, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id and b.time_out > '$WeekAgo') duration FROM public_verified_shifts a  where a.callsign is not null order by duration desc LIMIT 10";
    }

    return Query($sql);
}


?>

<div class="container-fluid-p0">
    <h4>Top Time Spent On-Shift</h4>
    <h6>Last Updated: <?php echo getLastUpdated(); ?></h6>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="ThisWeek-tab" data-toggle="tab" href="#ThisWeek" role="tab" aria-controls="home" aria-selected="true">
                <h5>This Week</h5>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="AllTime-tab" data-toggle="tab" href="#AllTime" role="tab" aria-controls="profile" aria-selected="false">
                <h5>All Time</h5>
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="ThisWeek" role="tabpanel" aria-labelledby="ThisWeek-tab">
            <?php $tData = sqlCollectTotalShiftTimes("this-week");
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
            ?>
        </div>
        <div class="tab-pane fade" id="AllTime" role="tabpanel" aria-labelledby="AllTime-tab">
            <?php $tData = sqlCollectTotalShiftTimes("all-time");
            $thead = ["", "Name", "Rank", "Time Clocked In", ""];
            $tbody = array();
            if ($tData) {
                foreach ($tData as $index => $row) {
                    $tRow = array();
                    $tRow[] = $index + 1;
                    $tRow[] = $row->callsign . " | " . $row->char_name;
                    $tRow[] = $row->rank;
                    $tRow[] = toDurationDays($row->duration);
                    $tRow[] = "<a class='btn btn-outline-secondary' href='/view_player.php?steamid=" . $row->steam_id . "'>View Player</a>";
                    $tbody[] = $tRow;
                }
            }
            Tablefy($thead, $tbody);
            ?>
        </div>
    </div>
</div>

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
<?php }
include "../include/footer.php";
