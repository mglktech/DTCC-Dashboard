<?php
if (isset($_POST["Login"])) {
    //echo $_POST["txtInput"];
    $_SESSION['code'] = $_POST["txtInput"];
    include "db_connection.php";
    $conn = OpenCon();
    $sql = "SELECT * FROM `players` WHERE `code` = '" . $_POST['txtInput'] . "';";
    $result = $conn->query($sql);
    CloseCon($conn);
    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            $_SESSION['steam_id'] = $row['steam_id'];
            $_SESSION['steam_name'] = $row['steam_name'];
            $_SESSION['char_name'] = $row['char_name'];
            $_SESSION['rank'] = $row['rank'];
            $_SESSION['phone_number'] = $row['phone_number'];
        }
        header("Location: " . $_COOKIE['redirect']);
    } else {
        header("Location: ../login.php");
    }
}
