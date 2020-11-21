<?php include 'include/header.php';
include "include/sqlconnection.php";
function CreateTable($tableType)
{

    if ($tableType == "unread") {
        $sql = "SELECT * FROM `unread_apps`";
        $table = Query($sql);
    }
    if ($tableType == "all") {
        $sql = "SELECT * FROM `app_history` ORDER BY `signed_timestamp` DESC LIMIT 20";
        //$sql = "SELECT `char_name`,`callsign`,`rank` FROM players WHERE steam_id = '$steam_id'";
        // needs to have way of ordering apps by super submit date

        $table = Query($sql); 
    }

    return $table;
}
?>

<h1>Applications</h1>
<h5 class="mb-3 font-weight-normal"><i>need friends?</i></h5>
<div class="container-fluid-p0">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="unreadapps-tab" data-toggle="tab" href="#unread" role="tab" aria-controls="home" aria-selected="true">
                <h5>Unread Apps</h5>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="allapps-tab" data-toggle="tab" href="#all" role="tab" aria-controls="profile" aria-selected="false">
                <h5>App History</h5>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- UNREAD APPS TAB -->
        <div class="tab-pane fade show active" id="unread" role="tabpanel" aria-labelledby="unreadapps-tab">
            <?php $tableData = CreateTable("unread");
            if ($tableData) { ?>
                <table class="table table-striped blue-header">
                    <tr>
                        <th>Date</th>
                        <th>Player Name</th>
                        <th>Discord Name</th>
                        <th></th>
                    </tr>
                    <?php

                    foreach ($tableData as $row) {
                        echo "<tr>";
                        echo "<td>" . toDate($row->app_timestamp) . "</td>";
                        echo "<td>" . $row->char_name . "</td>";
                        echo "<td>" . $row->discord_name . "</td>";

                        echo "<td><a class='btn btn-info' href='/view_app.php?appid=" . $row->app_id . "'>View Application</a></td>";
                        echo "</tr>";
                    }

                    ?>
                </table>
            <?php } else {
                echo "Table is empty";
            }
            ?>
        </div>
        <!-- ALL APPS TAB -->
        <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="allapps-tab">
            <?php $tableData = CreateTable("all");
            if ($tableData) { ?>
                <table class="table table-striped blue-header">
                    <tr>
                        <th>Player Name</th>
                        <th>Time</th>
                        <th>Phone Number</th>
                        <th>Signed By</th>
                        <th>App Status</th>
                        <th></th>
                    </tr>
                    <?php

                    foreach ($tableData as $row) {
                        // row 3 is equal to signed_by steamid. grab name here;
                        $super_line = $row->callsign . " | " . $row->signed_by;
                        echo "<tr>";
                        echo "<td>" . $row->app_char_name . "</td>";
                        echo "<td>" . toDate($row->signed_timestamp) . "</td>";
                        echo "<td>" . $row->phone_number . "</td>";
                        echo "<td>" . $super_line . "</td>";
                        echo "<td>" . $row->status . "</td>";
                        echo "<td><a class='btn btn-outline-secondary' href='/view_app.php?appid=" . $row->app_id . "'>View</a></td>";
                        echo "</tr>";
                    } ?>
                </table>
            <?php } else {
                echo "Table is empty";
            }
            ?>
        </div>
    </div>

</div>

<?php include 'include/footer.php'; ?>