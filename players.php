<?php include 'include/header.php';  ?>
<?php
include "include/sqlconnection.php";




function getCountDrivers()
{
    $sql = "SELECT COUNT(`steam_id`) as `count` FROM `public_players` WHERE `status`='Active' ORDER BY `rank` DESC";
    return Query($sql)[0]->count;
}
function getCountRecruits()
{
    $sql = "SELECT COUNT(`steam_id`) as `count` FROM `public_players` WHERE `rank`='Recruit'";
    return Query($sql)[0]->count;
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

    return Query($sql);
}

//print_r($ranks_array);

// output data of each row
?>

<h1>Roster</h1>
<h5 class="font-italic mb-3 font-weight-normal">My Minions!</h5>
<h6 class="mb-1 font-weight-normal"> <?php echo getCountDrivers(); ?> Active Drivers, <?php echo getCountRecruits(); ?> Recruits</h6>
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
            echo "<td>" . $row->callsign . "</td>";
            echo "<td>" . $row->char_name . "</td>";
            echo "<td>" . $row->discord_name . "</td>";
            echo "<td>" . $row->rank_label . "</td>";
            echo "<td>" . $row->status . "</td>";
            echo "<td><a class='btn btn-secondary view-player' href='/view_player.php?steamid=" . $row->steam_id . "'>View Player</button></td>";
            echo "</tr>";
        }
    } else {
        echo "no results";
    }
    ?>
</table>
<?php include 'include/footer.php'; ?>