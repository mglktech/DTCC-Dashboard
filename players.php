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
    if (isset($_POST['search'])) {
        $q = $_POST['search'];
        $sql = "SELECT * FROM `public_players` WHERE (`char_name` like '%$q%')
        or (steam_name like '%$q%')
        or (discord_name like '%$q%')";
    } else {
        $sql = "SELECT * FROM `public_players` ORDER BY `rank` DESC";
    }
    include 'include/db_connection.php';

    return fetchAll($sql);
}

//print_r($ranks_array);

// output data of each row
?>

<h1>Roster</h1>
<h5 class="font-italic mb-3 font-weight-normal">my minions!</h5>
<table class="table table-striped blue-header roster-table">
    <tr>
        <th>Callsign</th>
        <th>Name</th>
        <th>Discord</th>
        <th>Rank</th>
        <th>Status</th>
        <th>
            <form action="players.php" method="post">
                <div class="input-group input-group float-left">
                    <input name="search" style="height: 27px;" type="text" class="form-control" placeholder="Search Players...">
                    <div class="input-group-append">
                        <button type="submit" style="height: 27px;" class="btn btn-secondary player-search" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </th>
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
            echo "<td><a class='btn btn-secondary view-player' href='/view_player.php?steamid=" . $row[2] . "'>View Player</button></td>";
            echo "</tr>";
        }
    } else {
        echo "no results";
    }
    ?>
</table>
<?php include 'include/footer.php'; ?>