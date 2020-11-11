<?php
include "../include/header.php";
include "../include/db_connection.php";
$sql = "SELECT DISTINCT `steam_name` FROM `shift_records`";
$tblCol_steam_names = fetchAll($sql);
$tblCol_num_records = array();
foreach ($tblCol_steam_names as $steam_name) {

    $sql = "SELECT COUNT(`steam_name`) FROM `shift_records` WHERE `steam_name` = '$steam_name[0]'";
    $tblCol_num_records[] = fetchRow($sql)[0];
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
                echo "<td>" . $sn[0] . "</td>";
                echo "<td>" . $tblCol_num_records[$index] . "</td>";
                echo "<td><a class='btn btn-info' href='verify_shifts.php?id=" . $sn[0] . "'>View</a></td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

<?php include "../include/footer.php"; ?>