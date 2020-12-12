<div class="row">
    <div class="col pb-2 mb-5 border rounded">
        <?php if ($status == "accept") {
            include "inc_view_app_signed_accepted.php";
        }
        if ($status == "deny") {
            include "inc_view_app_signed_rejected.php";
        }
        ?>

    </div>

</div>