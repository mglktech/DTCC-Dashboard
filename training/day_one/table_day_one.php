<?php
include "../../include/components/head.php";
include "../../include/elements.php";
isset($_SESSION["steam_id"]) ? $id = $_SESSION["steam_id"] : $id = null;
$client = q_fetchPlayer($id);

?>
<section>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3 class="d-flex justify-content-center">Welcome, <?= $client->char_name; ?></h3>
            </div>
        </div>
        <?php include "partial/tbl_day_one.php"; ?>
    </div>
</section>
<?php

include "../../include/components/foot.php";
