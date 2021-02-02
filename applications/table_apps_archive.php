<?php include "../include/components/head.php";
include "../include/sqlconnection.php";
include "../include/elements.php";








function CreateTableArchive($start, $limit)
{



    if (isset($_GET['search'])) {
        $q = quotefix($_GET['search']);
        $sql = "SELECT * FROM `app_history` WHERE (`app_char_name` like '%$q%')
    or (app_steam_name like '%$q%')
    or (app_discord_name like '%$q%')";
    } else {

        $sql = "SELECT * FROM `app_history` ORDER BY `signed_timestamp` DESC LIMIT $start,$limit";
    }
    $search = "<form action='table_apps_archive.php' method='get'>
    <div class='input-group input-group float-left'>
        <input name='search' style='height: 27px;' type='text' class='form-control' placeholder='Search Apps...'>
        <div class='input-group-append'>
            <button type='submit' style='height: 27px;' class='btn btn-dark' type='button'>
                <i class='fa fa-search'></i>
            </button>
        </div>
    </div>
</form>";
    $tblHeaders = ["Player Name", "Time", "Phone Number", "Signed By", "App Status", $search];
    $result = Query($sql);
    $tblBody = array();
    foreach ($result as $row) {
        $super_line = $row->callsign . " | " . $row->signed_by;
        $tblRow = array();
        $tblRow[] = $row->app_char_name;
        $tblRow[] = toDateS($row->signed_timestamp);
        $tblRow[] = $row->phone_number;
        $tblRow[] = $super_line;
        $tblRow[] = Pill($row->status);
        $tblRow[] = "<a class='btn btn-sm btn-secondary' href='/applications/view_app.php?doc_id=" . $row->app_id . "'>View</a>";
        $tblBody[] = $tblRow;
    }
    return Tablefy($tblHeaders, $tblBody);
}
?>

<?php
$limit = 25;
$count = Query("SELECT count(*) AS `count` FROM app_history")[0]->count;
$obj = CreatePaginateObj($count, $limit);

?>

<h1 class="h-title">Applications Archive</h1>
<h5 class=" h-subtitle mb-3 font-weight-normal"><i>Previously Signed Applications</i></h5>

<div class="container">


    <?php CreateTableArchive($obj->start, $limit);
    if (!isset($_GET["search"])) {
        Paginate($obj);
    }




    ?>

</div>





<?php include "../include/components/foot.php"; ?>