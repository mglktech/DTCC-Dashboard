<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION["steam_id"])) {
    //$_SESSION["redirect"] = $_SERVER['REQUEST_URI'];
    //echo "not logged in";
    header("Location: ../login.php");
}
include "includes.php";
?>

<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 pl-3 p-1" href="#"><img src="/images/logo.png"></a>
        <h1 class="text-light"></h1>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <span style="color: rgba(255, 255, 255, 0.5)" id="SteamName"><?php echo $_SESSION["char_name"]; ?>
                    <img id="DisplayImg" style="height: 32px; width: 32px" src="<?php echo $_SESSION['profile_pic'] ?>" />
                    |
                </span><a style="display: inline-block" class="nav-link" href="../logout.php">Sign out</a>
            </li>
        </ul>

        <button class="navbar-toggler collapsed m-2" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <!-- <div class="progress rounded-0">
        <div class="progress-bar bg-success rounded-0" id="HeaderPgBar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div> -->
    <div class="container-fluid">
        <div class="row">
            <?php include "sidebar.php" ?>

            <main id="main" role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">