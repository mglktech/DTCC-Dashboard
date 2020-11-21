<?php include 'include/header.php';

function CreateTable($tableType)
{
    include_once 'include/db_connection.php';
    if ($tableType == "unread") {
        $sql = "SELECT * FROM `unread_apps`";
        $table = fetchAll($sql);
    }
    if ($tableType == "all") {
        $sql = "SELECT * FROM `app_history`";
        //$sql = "SELECT `char_name`,`callsign`,`rank` FROM players WHERE steam_id = '$steam_id'";

        $table = fetchAll($sql);
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
                        echo "<td>" . toDate($row[4]) . "</td>";
                        echo "<td>" . $row[1] . "</td>";
                        echo "<td>" . $row[2] . "</td>";

                        echo "<td><a class='btn btn-info' href='/view_app.php?appid=" . $row[0] . "'>View Application</a></td>";
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
                        $super_line = $row[6] . " | " . $row[5];
                        echo "<tr>";

                        echo "<td>" . $row->app_char_name . "</td>";
                        echo "<td>" . toDate($row->signed_timestamp) . "</td>";
                        echo "<td>" . $row->phone_number . "</td>";

                        echo "<td>" . $super_line . "</td>";
                        echo "<td>" . $row[4] . "</td>";
                        echo "<td><a class='btn btn-outline-secondary' href='/view_app.php?appid=" . $row[3] . "'>View</a></td>";
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