<?php
if (isset($_POST["Login"])) {
    session_start();
    //echo $_POST["txtInput"];
    $_SESSION['code'] = $_POST["txtInput"];
    include "include/db_connection.php";
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
            require_once "steam/SteamUser.php";
            $user = new SteamUser($row['steam_id']);
            $_SESSION['profile_pic'] = $user->avatarIcon;
        }
        header("Location: index.php");
        //echo "login successful!";
        //echo "session set, header " . $_COOKIE['redirect'];
    } else {
        //echo "Login unsuccessful!";
        header("Location: login.php");
    }
}
include 'include/_header.php';
?>

<h4>Please input your 6 digit code so we know who you are.</h4>
<form action="" method="post">
    <input type="password" name="txtInput" />
    <input type="submit" name="Login" class="btn btn-secondary" value="Login">
</form>
<?php include 'include/footer.php'; ?>