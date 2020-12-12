<?php include '../include/header.php';
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
            $tblRow[] = "<a class='btn btn-info' href='/applications/view_app.php?doc_id=" . $row->app_id . "'>View Application</a>";
            $tblBody[] = $tblRow;
        }
    } else {
        $tblBody = [[]];
    }
    return Tablefy($tblHeaders, $tblBody);
}

?>

<h1>Unread Applications</h1>
<h5 class="mb-3 font-weight-normal"><i>need friends?</i></h5>
<div class="container-fluid-p0">
    <?php CreateTableUnread(); ?>
</div>

<?php include '../include/footer.php'; ?>