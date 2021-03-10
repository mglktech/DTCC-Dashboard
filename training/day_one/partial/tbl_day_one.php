<?php


function CountTraining()
{
    return QueryFirst("SELECT COUNT(*) AS 'c' FROM `players` WHERE `rank` = '0' AND `instructor_trained` is null")->c;
}
// test
function CreateTableTraining()
{
    //$btn = "<a class='btn btn-table' href='/applications/view_app.php?doc_id=0'>View Application</a>"; //DEBUG

    $ntbt = Query("SELECT * FROM `players` WHERE `rank` = '0' AND `instructor_trained` is null ORDER BY `employment_start` DESC"); // have to pull only steamid because callsign is not stored here
    $tblHeaders = ["Name", "Hire Date", "Discord Name", ""];
    if ($ntbt) {
        $tblBody = array();
        foreach ($ntbt as $row) {
            $p = q_fetchPlayer($row->steam_id);
            $tblRow = array();
            $tblRow[] = $row->callsign . " | " . $row->char_name;
            $tblRow[] = toDateS($row->employment_start);
            $tblRow[] = $row->discord_name;
            $tblRow[] = "<a class='btn btn-secondary' href='/training/day_one/instructor_checklist.php?id=" . $row->steam_id . "'>Train</a>";
            $tblBody[] = $tblRow;
        }
    } else {
        $tblBody = [[]];
    }
    return Tablefy($tblHeaders, $tblBody);
}
?>



<div class="row">
    <div class="col d-flex justify-content-center">
        <h6><?= CountTraining() ?> drivers are waiting for Day One Training</h6>
    </div>
</div>
<div class="row">
    <?= CreateTableTraining() ?>
</div>