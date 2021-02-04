<?php include "../include/components/head.php"; ?>
<?php

include "../include/elements.php";



function getCountDrivers()
{
    $sql = "SELECT COUNT(`steam_id`) as `count` FROM `public_players` WHERE `status`='Active' ORDER BY `rank` DESC";
    return Query($sql)[0]->count;
}
function getCountRecruits()
{
    $sql = "SELECT COUNT(`steam_id`) as `count` FROM `public_players` WHERE `rank_label`='Recruit'";
    return Query($sql)[0]->count;
}

function getRostor($start, $limit)
{
    if (isset($_GET['search'])) {
        $q = $_GET['search'];
        $sql = "SELECT * FROM `public_players` WHERE (`char_name` like '%$q%')
        or (steam_name like '%$q%')
        or (discord_name like '%$q%')";
    } else {

        $sql = "SELECT * FROM `public_players` WHERE `rank`>=-1 ORDER BY -`callsign_id` DESC LIMIT $start,$limit";
    }

    return Query($sql);
}

//print_r($ranks_array);

// output data of each row

$search_bar = "<form action='players.php' method='get'>
<div class='input-group input-group float-left'>
    <input name='search' style='height: 27px;' type='text' class='form-control' placeholder='Search Players...'>
    <div class='input-group-append'>
        <button type='submit' style='height: 27px;' class='btn btn-secondary player-search' type='button'>
            <i class='fa fa-search'></i>
        </button>
    </div>
</div>
</form>";

$tblHeaders = ["Name", "Discord", "Rank", "Status", $search_bar];

$tBody = array();
$limit = 25;
$count = Query("SELECT count(*) AS `count` FROM public_players WHERE `rank`>=-1")[0]->count;
$obj = CreatePaginateObj($count, $limit);
$tData = getRostor($obj->start, $limit);
if ($tData) {
    foreach ($tData as $row) {
        $tRow = array();
        $tRow[] = $row->callsign . " | " . $row->char_name;
        $tRow[] = $row->discord_name;
        $tRow[] = Pill("rank_" . $row->rank);
        $tRow[] = $row->status;
        $tRow[] = "<a class='btn btn-sm btn-secondary view-player' style = 'align-self:stretch;' href='../players/view_player.php?id=" . $row->steam_id . "'>View Player</button>";
        $tBody[] = $tRow;
    }
}



?>

<h1 class="h-title">Roster</h1>
<h5 class="h-subtitle font-italic mb-3 font-weight-normal">My Minions!</h5>
<h5 class="h-subtitle mb-1 font-weight-normal font-weight-bold"> <?php echo getCountDrivers(); ?> Active Drivers, <?php echo getCountRecruits(); ?> Recruits</h5>

<div class="container">
    <?php
    Tablefy($tblHeaders, $tBody);
    if (!isset($_GET["search"])) {
        Paginate($obj);
    }
    ?></div><?php
        include "../include/components/foot.php";
