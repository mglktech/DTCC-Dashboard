<?php include "include/components/head.php";
//include "include/sqlconnection.php";
include "include/elements.php";

isset($_SESSION["steam_id"]) ? $id = $_SESSION["steam_id"] : $id = null;
$client = q_fetchPlayer($id);

if (Rank_Strict("Senior Supervisor")) {

    include "home/home_sen_super.php";
}
if (Rank_Strict("Supervisor")) {

    include "home/home_super.php";
}
if (Rank_Strict("Instructor")) {

    include "home/home_instructor.php";
}

if (Rank_Strict("Private Hire")) { // Temporary!!! [removeme]

    include "home/home_instructor.php";
}

if (Rank_Strict("Overboss")) {
    include "home/home_johny.php";
}

include "include/components/foot.php";
