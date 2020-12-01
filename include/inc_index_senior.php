<?php if ($_SESSION['steam_id'] == '76561197995263974' || $_SESSION['steam_id'] == '76561198050836459') { ?>
    <h6>"Special" commands:</h6>
    <a class='btn btn-secondary mt-1 mb-1' href='/shifts/upload_shifts.php'>Upload Shift Data</a><br>
    <a class='btn btn-secondary mt-1 mb-1' href='/shifts/table_unver_shifts.php'>Verify Shifts</a><br>
<?php } ?>
<h6> SS commands</h6>
<a class='btn btn-secondary mt-1 mb-3' href='/admin/inactive_drivers.php'>Inactive Staff List</a></a><br>
<button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#chkUpdateSteamDetails">Refresh Steam Names</button>
<button class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#chkCleanBlankShifts">Clean Blank Shifts</button>

<div class="modal fade" id="chkUpdateSteamDetails" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refresh Steam Names</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                You are about to refresh Steam Names for <b>The Entire Database</b>. are you sure you want to do this?<br>
                This may take a while to complete. Do not refresh the page!
            </div>
            <div class="modal-footer">

                <a href="/admin/update_steam_details.php" class="btn btn-success">Do it</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>

            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="chkCleanBlankShifts" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clean Blank Shifts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <b>WARNING: </b> You should only run this command <i>after</i> you have refreshed Steam Names. are you sure you want to do this?
            </div>
            <div class="modal-footer">

                <a href="/dev/BlankSteamIDCleanup.php" class="btn btn-success">Do it</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>

            </div>
        </div>
    </div>

</div>