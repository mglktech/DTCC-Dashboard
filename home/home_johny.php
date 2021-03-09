<?php

function CreateTableUnread()
{
    //$btn = "<a class='btn btn-table' href='/applications/view_app.php?doc_id=0'>View Application</a>"; //DEBUG
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
            $tblRow[] = "<a class='btn btn-sm btn-secondary' href='/applications/view_app.php?doc_id=" . $row->app_id . "'>View</a>";
            $tblBody[] = $tblRow;
        }
    } else {
        $tblBody = [[]];
    }
    return Tablefy($tblHeaders, $tblBody);
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



?>
<!-- <a class='btn btn-secondary mt-1 mb-3' data-toggle="tooltip" data-placement="top" title="Request access if you haven't got it yet" target="_blank" href='https://drive.google.com/drive/folders/1DkeJJ5uiEdpRJ0KCDMmGQsKFPxOaPzF4?usp=sharing'>Google Drive Folder</a>
<a class='btn btn-secondary mt-1 mb-3' data-toggle="tooltip" data-placement="top" title="Login to GitHub" target="_blank" href='https://github.com/pilotvollmar/DTCC-Dashboard/issues'>Feedback</a>
<br> -->


<section>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3 class="d-flex justify-content-center">Welcome, TBoss | <?= $client->char_name; ?>!</h3>
            </div>
        </div>

        <div class="container-fluid d-flex">
            <div class="row">
                <div class="col-auto d-flex flex-column flex-grow-0 flex-shrink-1 col-index">
                    <h4 class="h-title">New Applicants</h4>
                    <h5 class="h-subtitle">Need Friends?</h5>
                    <div class="table-responsive">
                        <?php CreateTableUnread() ?>
                    </div>
                </div>

                <div class="col d-xl-flex flex-column justify-content-xl-center align-items-xl-center">
                    <h5 class="h-subtitle">Useful Links</h5>
                    <a class="btn btn-secondary m-1" href="https://drive.google.com/drive/folders/1DkeJJ5uiEdpRJ0KCDMmGQsKFPxOaPzF4?usp=sharing">Google&nbsp;Drive&nbsp;Folder<br></a>
                    <a class="btn btn-secondary m-1" href="#">Feedback</a>

                    <a class='btn btn-dark m-1' href='/admin/inactive_drivers.php'>Inactive Staff</a>
                    <a class='btn btn-dark m-1' href='/admin/slackers.php'>Under-Achievers</a>
                    <a class='btn btn-dark m-1' href='/admin/whitelisting.php'>Needs Whitelisting</a>

                </div>
            </div>
        </div>
    </div>
</section>