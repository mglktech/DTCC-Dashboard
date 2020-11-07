<?php include 'include/header.php';  ?>
<?php

function OldWay()
{
    $conn = OpenCon();
    $ranks_array = fetchAll("SELECT `display_name`,`placement` FROM `ranks`");
    $sql = "SELECT `steam_id`,`char_name`,`phone_number`,`rank` FROM `players` WHERE `status` IS NOT NULL ORDER BY `rank` DESC";
    $result = $conn->query($sql);
    CloseCon($conn);
}

function getRostor()
{
    include 'include/db_connection.php';
    $sql = "SELECT * FROM `public_players` ORDER BY `rank` DESC";
    return fetchAll($sql);
}

//print_r($ranks_array);

// output data of each row
?>

<h1 class="mb-3">Roster</h1>

<table class="table">
    <tr>
        <th>Callsign</th>
        <th>Name</th>
        <th>Discord</th>
        <th>Rank</th>
        <th>Status</th>
        <th></th>
    </tr>
    <?php
    $table = getRostor();
    if ($table) {
        foreach ($table as $row) {
            echo "<tr>";
            echo "<td>" . $row[0] . "</td>";
            echo "<td>" . $row[1] . "</td>";
            echo "<td>" . $row[4] . "</td>";
            echo "<td>" . $row[7] . "</td>";
            echo "<td>" . $row[8] . "</td>";
            echo "<td><a class='btn btn-secondary' href='/view_player.php?steamid=" . $row[2] . "'>View Player</button></td>";
            echo "</tr>";
        }
    } else {
        echo "no results";
    }
    ?>
</table>
<?php include 'include/footer.php'; ?>