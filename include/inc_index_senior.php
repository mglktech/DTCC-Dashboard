<div class="col-auto d-flex flex-column flex-grow-0 flex-shrink-1 col-index">
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
            <form id="ThisForm" method="post">

                <button name="submit" type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
            <?php create_remove_table(); ?>
            <form id="ThisForm2" method="post">
                <button name="submit" type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
</div>

<!-- <h6> SS commands</h6>
<a class='btn btn-secondary mt-1 mb-3' href='/admin/inactive_drivers.php'>Inactive Staff</a></a><br>
<a class='btn btn-secondary mt-1 mb-3' href='/admin/slackers.php'>Under-Achievers</a></a><br>
<a class='btn btn-secondary mt-1 mb-3' href='/admin/whitelisting.php'>Needs Whitelisting</a></a><br>
<a hidden class='btn btn-secondary mt-1 mb-3' href='/admin/highlife_char_status.php'>Character Alive Check</a></a><br> -->

<?php

if (isset($_POST["chk"])) {

    $chk_array = $_POST["chk"];
    foreach ($chk_array as $val) {
        $sql = "UPDATE `players` SET `whitelisted`='1' WHERE `steam_name` = '$val'";
        Query($sql);
    }
}
if (isset($_POST["chk2"])) {

    $chk_array = $_POST["chk2"];
    foreach ($chk_array as $val) {
        $sql = "UPDATE `players` SET `whitelisted`='0' WHERE `steam_name` = '$val'";
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
    $sql = "SELECT * FROM `public_players` WHERE `whitelisted` = '1' and `rank` < '0' ";
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
