<?php
include "../include/components/head.php";

$sql = "SELECT DISTINCT `steam_id` FROM `clockin_data` WHERE (`verified_shift_id` IS NULL AND `type` = 'out')";
$result = Query($sql);
if ($result) {
    //$tblCol_steam_names = $result;
    $tblCol_num_records = array();
    $tblCol_steam_ids = array();
    foreach ($result as $steam_ids) {

        $sql = "SELECT COUNT(`steam_id`) AS cnt FROM `clockin_data` WHERE (`steam_id` = '$steam_ids->steam_id' AND `verified_shift_id` IS NULL)";
        $numrecs = Query($sql)[0]->cnt;
        if ($numrecs > 1) {
            $tblCol_steam_ids[] = $steam_ids->steam_id;
            $tblCol_num_records[] = $numrecs;
        }
    }
}




?>
<div class="container">
    <h3>Unverified Shifts</h3>
    <table class="table table-striped blue-header">
        <thead>
            <tr>
                <th>Name</th>
                <th>No.Records</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($tblCol_steam_ids)) {
                foreach ($tblCol_steam_ids as $index => $sid) {
                    echo "<tr>";
                    echo "<td>" . q_fetchPlayerFormatted($sid) . "</td>";
                    echo "<td>" . $tblCol_num_records[$index] . "</td>";
                    echo "<td><a class='btn btn-info' href='verify_shifts.php?id=" . $sid . "'>View</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php "../include/components/foot.php"; ?>