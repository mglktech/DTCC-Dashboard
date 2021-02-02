<?php include "../include/components/head.php";
include "../include/sqlconnection.php";
include "../include/elements.php";

function CreateTableUnread()
{
    $sql = "SELECT * FROM `unread_apps`";
    $tblHeaders = ["Date", "Player Name", "Discord Name", ""];
    $result = Query($sql);
    if ($result) {
        $tblBody = array();
        foreach ($result as $row) {
            $tblRow = array();
            $tblRow[] = toDate($row->app_timestamp);
            $tblRow[] = $row->char_name;
            $tblRow[] = $row->discord_name;
            $tblRow[] = "<a class='btn btn-secondary' href='/applications/view_app.php?doc_id=" . $row->app_id . "'>View Application</a>";
            $tblBody[] = $tblRow;
        }
    } else {
        $tblBody = [[]];
    }
    return Tablefy($tblHeaders, $tblBody);
}

?>

<div class="container">
    <div class="row">
        <div class="col">
            <h3 class="h-title">Unread Applications</h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h6 class="h-subtitle">Need Friends?</h6>
        </div>
    </div>
</div>
<div class="container-table">
    <?php CreateTableUnread(); ?>
</div>

<?php include "../include/components/foot.php"; ?>