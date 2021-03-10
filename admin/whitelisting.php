<?php include "../include/components/head.php";

include "../include/elements.php";

// create table of people who need whitelisting
// select from players where whitelisted = false and rank >=0;
// allow for checkbox to be marked when player has been whitelisted
// have approve button at bottom for verifying checked table items



if (isset($_POST["chk"])) {

    $chk_array = $_POST["chk"];
    foreach ($chk_array as $val) {
        $sql = "UPDATE `players` SET `whitelisted` = '1' WHERE `steam_name` = '$val'";
        Query($sql);
    }
}
if (isset($_POST["chk2"])) {

    $chk_array = $_POST["chk2"];
    foreach ($chk_array as $val) {
        $sql = "UPDATE `players` SET `whitelisted` = '0' WHERE `steam_name` = '$val'";
        Query($sql);
    }
}


function create_whitelist_table()
{

    $sql = "SELECT * FROM `public_players` WHERE `whitelisted` = '0' and `rank` >= '0' ";
    $tData = Query($sql);
    $thead = ["Name", "Steam Name", "Rank", "Hex", "Whitelisted?"];
    $tbody = array();
    if ($tData) {
        foreach ($tData as $index => $row) {
            $tRow = array();
            $tRow[] = $row->callsign . " | " . $row->char_name;
            $tRow[] = $row->steam_name . "<input form='ThisForm' name='sName[]' value = " . $row->steam_name . " hidden>";
            $tRow[] = $row->rank;
            $tRow[] = "steam:" . dechex($row->steam_id);
            $tRow[] = "<input form='ThisForm' type='checkbox' name='chk[]' value='" . $row->steam_name . "'>";
            $tbody[] = $tRow;
        }
    }
    Tablefy($thead, $tbody);
}

function create_remove_table()
{
    $sql = "SELECT * FROM `public_players` WHERE `whitelisted` = '1' and `rank` is null ";
    $tData = Query($sql);
    $thead = ["Name", "Steam Name", "Rank", "Hex", "Whitelisted?"];
    $tbody = array();
    if ($tData) {
        foreach ($tData as $index => $row) {
            $tRow = array();
            $tRow[] = $row->callsign . " | " . $row->char_name;
            $tRow[] = $row->steam_name . "<input form='ThisForm2' name='sName2[]' value = " . $row->steam_name . " hidden>";
            $tRow[] = $row->rank;
            $tRow[] = "steam:" . dechex($row->steam_id);
            $tRow[] = "<input form='ThisForm2' type='checkbox' name='chk2[]' value='" . $row->steam_name . "'>";
            $tbody[] = $tRow;
        }
    }
    Tablefy($thead, $tbody);
}


if ($_SESSION["rank"] > 2) {


?>
<div class="container">
    <a class='btn btn-lg btn-info' href='https://highliferoleplay.net/whitelisting/index.php' target='_blank'>Highlife Whitelisting</a>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="home" aria-selected="true">
                <h5>Needs Whitelist</h5>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="profile" aria-selected="false">
                <h5>Needs Removing</h5>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
            <?php create_whitelist_table(); ?>
            <form id="ThisForm" action="whitelisting.php" method="post">

                <button name="submit" type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
            <?php create_remove_table(); ?>
            <form id="ThisForm2" action="whitelisting.php" method="post">
                <button name="submit" type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
    </div>





<?php }
include "../include/components/foot.php";
