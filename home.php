<?php include "include/components/head.php";
//include "include/sqlconnection.php";
include "include/elements.php";
$rank = $_SESSION["rank"];
if ($rank >= 3) {
    include "home/home_sen_super.php";
}
if ($rank == 2) {
    include "home/home_super.php";
}
if ($rank == 1) {
    include "home/home_instructor.php";
}

include "include/components/foot.php";
