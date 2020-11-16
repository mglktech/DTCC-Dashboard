<?php
include "../include/header.php";
include "../include/db_connection.php";
$sql = "SELECT DISTINCT `steam_name` FROM `shift_records` WHERE `signed_by` IS NULL";
$result = fetchAll($sql);
if ($result) {
    //$tblCol_steam_names = $result;
    $tblCol_num_records = array();
    $tblCol_steam_names = array();
    foreach ($result as $steam_name) {

        $sql = "SELECT COUNT(`steam_name`) FROM `shift_records` WHERE (`steam_name` = '$steam_name[0]' AND `signed_by` IS NULL)";
        $numrecs = fetchRow($sql)[0];
        if ($numrecs > 1) {
            $tblCol_steam_names[] = $steam_name[0];
            $tblCol_num_records[] = $numrecs;
        }
    }
}




?>
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
        if (isset($tblCol_steam_names)) {
            foreach ($tblCol_steam_names as $index => $sn) {
                echo "<tr>";
                echo "<td>" . $sn . "</td>";
                echo "<td>" . $tblCol_num_records[$index] . "</td>";
                echo "<td><a class='btn btn-info' href='verify_shifts.php?id=" . $sn . "'>View</a></td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

<?php include "../include/footer.php"; ?>