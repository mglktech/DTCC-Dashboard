<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include_once "includes.php"
?>

<body>
  <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Downtown Cab Co. Administration Panel</a>
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <span style="color: rgba(255, 255, 255, 0.5)">
          <div id="SteamName"></div>
          <img id="DisplayImg" style="height: 32px; width: 32px" src="https://via.placeholder.com/32x32" />
          |
        </span><a style="display: inline-block" class="nav-link" href="">Sign out</a>
      </li>
    </ul>
  </nav>
  <div class="progress rounded-0">
    <div class="progress-bar bg-success rounded-0" id="HeaderPgBar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <!-- <?php include "sidebar.php" ?> -->

      <main id="main" role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">