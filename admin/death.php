<?php include 'include/components/head.php';
include "include/db_connection.php";

?>


<form action="death.php" method="post">
    <h6>Type a player's name to check alive status</h6>
    <input name="Name"></input>
    <button type="submit">Submit</button>
</form>
<?php
if (isset($_POST["Name"])) {
    $r = IsAlive($_POST["Name"]);
    if ($r == 1) {
        echo "player is alive";
    }
    if ($r == 0) {
        echo "player is dead";
    }
    if ($r == -1) {
        echo "player not found";
    }
}
?>

<?php include_once "include/components/foot.php"; ?>