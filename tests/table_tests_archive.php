<?php include '../include/header.php';
include "../include/sqlconnection.php";
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
        return "<b class='text-success'>PASS</b>";
    } else {
        return "<b class='text-danger'>FAIL</b>";
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
    $sql = "SELECT * FROM `test_history` ORDER BY `submit_date` DESC LIMIT 25";
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
$tblHeaders = ["Player Name", "Date", "Test Type", "Score", "Author", "Outcome", $searchbar];
$tblBody = array();
foreach ($result as $row) {
    $metas = getMetas($row->type, $row->version);
    $super_line = $row->callsign . " | " . $row->signed_by;
    $tblRow = array();
    $tblRow[] = $row->student_name;
    $tblRow[] = toDateS($row->submit_date);
    $tblRow[] = $row->type;
    $tblRow[] = ($row->score_percent * 100) . "%";
    $tblRow[] = $super_line;
    $tblRow[] = PassFail($metas, $row->score_percent);
    $tblRow[] = "<a class='btn btn-primary' href='view_test.php?test_id=" . $row->id . "'>View Test</button>";
    $tblBody[] = $tblRow;
}
?>
<h2>Tests Archive</h2>
<h5 class="font-italic mb-3 font-weight-normal">Why so serious?</h5>
<?php
Tablefy($tblHeaders, $tblBody);

include '../include/footer.php';
