<?php include '../include/components/head.php';

include "../include/elements.php";

function getMetas($type, $ver)
{
    $sql = "SELECT `pass_mark`,`max_score` FROM `tests_meta` WHERE `type`='$type' AND `version`='$ver'";
    return Query($sql)[0];
}

function PassFail($metas, $score_percent)
{
    $pass_percent = (round($metas->pass_mark / $metas->max_score, 2));
    //echo $score_percent . "/" . $pass_percent . "<br>";
    if ($score_percent >= $pass_percent) {
        return Pill("passed");
    } else {
        return Pill("failed");
    }
}

$searchbar = "<form method='get'>
    <div class='input-group input-group float-left'>
        <input name='search' style='height: 27px;' type='text' class='form-control' placeholder='Search Tests...'>
        <div class='input-group-append'>
            <button type='submit' style='height: 27px;' class='btn btn-secondary player-search' type='button'>
                <i class='fa fa-search'></i>
            </button>
        </div>
    </div>
</form>";


if (isset($_GET['search'])) {
    $q = $_GET['search'];
    $sql = "SELECT * FROM `test_history` WHERE (`student_name` like '%$q%')";
} else {
    $limit = 25;
    $count = Query("SELECT count(*) AS `count` FROM test_history")[0]->count;
    $obj = CreatePaginateObj($count, $limit);
    $sql = "SELECT * FROM `test_history` ORDER BY `submit_date` DESC LIMIT $obj->start,$limit";
}

$result = Query($sql);
/* $row = 
student_name
type
version
score_percent
scores
signed_by
callsign
*/
$tblHeaders = ["Player Name", "Date", "Test Type", "Version", "Score", "Author", "Outcome", $searchbar];
$tblBody = array();
foreach ($result as $row) {
    $metas = getMetas($row->type, $row->version);
    $super_line = $row->callsign . " | " . $row->signed_by;
    $tblRow = array();
    $tblRow[] = $row->student_name;
    $tblRow[] = toDateS($row->submit_date);
    $tblRow[] = Pill($row->type);
    $tblRow[] = $row->version;
    $tblRow[] = ($row->score_percent * 100) . "%";
    $tblRow[] = $super_line;
    $tblRow[] = PassFail($metas, $row->score_percent);
    $tblRow[] = "<a class='btn btn-sm btn-primary mx-2' href='view_test.php?test_id=" . $row->id . "'>View Test</button>" . "<a class='btn btn-sm btn-secondary view-player' style = 'align-self:stretch;' href='../players/view_player.php?id=" . $row->steam_id . "'>View Player</button>";
    $tblBody[] = $tblRow;
}
?>
<div class="container">
<h2>Tests Archive</h2>
<h5 class="font-italic mb-3 font-weight-normal">Why so serious?</h5>
<?php
Tablefy($tblHeaders, $tblBody);
if (!isset($_GET["search"])) {
    Paginate($obj);
}
?>
</div>
<?php 

include '../include/components/foot.php';
