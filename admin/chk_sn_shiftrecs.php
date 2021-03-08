<?php

include "../include/elements.php";
include "../include/components/head.php";

$searchbar = "<form method='get'>
    <div class='input-group input-group float-left'>
        <input name='search' style='height: 27px;' type='text' class='form-control' placeholder='Search...'>
        <div class='input-group-append'>
            <button type='submit' style='height: 27px;' class='btn btn-secondary player-search' type='button'>
                <i class='fa fa-search'></i>
            </button>
        </div>
    </div>
</form>";

if (isset($_GET['search'])) {
    $q = $_GET['search'];
    $sql = "SELECT * FROM `shift_records` WHERE (`steam_name` like '%$q%') ORDER BY `timestamp`";
    $result = Query($sql);

    $tblHeaders = ["steam_name", "timestamp", "server", "io"];
    $tblBody = array();
    if ($result) {
        foreach ($result as $r) {
            $tblRow = array();
            $tblRow[] = $r->steam_name;
            $tblRow[] = toDateTime($r->timestamp);
            $tblRow[] = $r->server;
            $tblRow[] = $r->io;
            $tblBody[] = $tblRow;
        }
    }
    Tablefy($tblHeaders, $tblBody);
}
echo $searchbar;
include "../include/components/foot.php";
