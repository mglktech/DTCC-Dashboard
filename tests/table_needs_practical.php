<?php include '../include/components/head.php';

include "../include/elements.php";

function CountThem()
{
    return QueryFirst("SELECT COUNT(*) AS 'c' FROM `players` WHERE `status` = 'Needs Practical'")->c;
}

$sql = "SELECT `steam_id`,`char_name`,`discord_name`,`phone_number`,`last_seen` FROM `players` WHERE `status` = 'Needs Practical' ORDER BY `last_seen`";
$result = Query($sql);
$tblHeaders = ["Player Name", "Discord Name", "Phone Number", "Last Seen", ""];
$tblBody = array();
foreach ($result as $row) {
    $tblRow = array();
    $tblRow[] = $row->char_name;
    $tblRow[] = $row->discord_name;
    $tblRow[] = $row->phone_number;
    $tblRow[] = toDateS($row->last_seen);
    $tblRow[] = "<a class='btn btn-primary' href='take_test.php?type=practical&ver=0&steamid=" . $row->steam_id . "'>Take Test</button>";
    $tblBody[] = $tblRow;
}
?>
<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-center">
            <h2>Needs Practical</h2>

        </div>
    </div>
    <div class="row">
        <div class="col d-flex justify-content-center">

            <h6><?= CountThem() ?> recruits are waiting for a Practical Test</h6>
        </div>
    </div>
    <?php
    Tablefy($tblHeaders, $tblBody);
    ?>
</div>
<?php
include '../include/components/foot.php';
