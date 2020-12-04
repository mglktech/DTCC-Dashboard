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
        $sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id) duration FROM public_verified_shifts a  where a.callsign is not null order by duration desc LIMIT 10";
    }
    if ($timeframe == "this-week") {
        $curtime = time();
        $oneWeek = 604800; //one week in seconds
        $WeekAgo = $curtime - $oneWeek;
        $sql = "SELECT DISTINCT a.steam_id, a.callsign, a.char_name, (SELECT SUM(b.duration) FROM public_verified_shifts b WHERE b.steam_id=a.steam_id and b.time_out > '$WeekAgo') duration FROM public_verified_shifts a  where a.callsign is not null order by duration desc LIMIT 10";
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
    </div>
</div>

<?php
include "../include/footer.php";
