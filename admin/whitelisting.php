<?php include "../include/header.php";
include "../include/sqlconnection.php";
include "../include/elements.php";

// create table of people who need whitelisting
// select from players where whitelisted = false and rank >=0;
// allow for checkbox to be marked when player has been whitelisted
// have approve button at bottom for verifying checked table items



if (isset($_POST["chk"])) {

    $chk_array = $_POST["chk"];
    foreach ($chk_array as $val) {
        $sql = "UPDATE players SET whitelisted='1' WHERE steam_name = '$val'";
        Query($sql);
    }
}

function create_whitelist_table()
{
    $sql = "SELECT * FROM public_players WHERE whitelisted = '0' and rank >= '0' ";
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

if ($_SESSION["rank"] > 2) {


?>
    <h4>Players who need Whitelisting</h4>
    <a class='btn btn-lg btn-info' href='https://highliferoleplay.net/whitelisting/index.php' target='_blank'>Highlife Whitelisting</a>
    <?php create_whitelist_table(); ?>
    <form id="ThisForm" action="whitelisting.php" method="post">
        <button name="submit" type="submit" class="btn btn-success">Submit</button>
    </form>


<?php }
include "../include/footer.php";
