<?php include '../include/header.php';
include '../include/db_connection.php';
function CollectCallsigns($rank, $region)
{
    $sql = "SELECT * FROM `callsigns` WHERE `min_rank` = $rank AND region = '$region' AND assigned_steam_id is null limit 10";

    return fetchAll($sql);
}

?>


<div class="container">
    <form method="post" action="#">
        <div class="form-group">
            <label for="callsign">Select Callsign</label>
            <select name="callsign" class="form-control" id="callsign">
                <?php $callsigns = CollectCallsigns(0, 'GMT');
                foreach ($callsigns as $c) {
                    echo "<option>" . $c[0] . "</option>";
                }

                ?>
            </select>
        </div>
    </form>
</div> <?php include '../include/footer.php'; ?>