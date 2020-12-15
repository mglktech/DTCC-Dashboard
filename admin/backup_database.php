
<?php
include "../include/sqlconnection.php";
EXPORT_DATABASE($_GET["host"], $_GET["user"], $_GET["pass"], $_GET["name"]);
