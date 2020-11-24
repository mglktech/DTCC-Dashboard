<?php include "../include/header.php";

$key = "1";
if (isset($_POST["Login"])) {
    $hash = password_hash($_POST["txtInput"], $key, ["PASSWORD_BCRYPT"]);
    echo "Hash is: " . $hash . "PW: " . $_POST["txtInput"];
}
if (isset($_POST["UnHash"])) {
    $unhash = password_verify($_POST["txtInput"], $_POST["txtInput2"]);
    if ($unhash) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>
<form method="post" target="">
    <input type="text" name="txtInput" />
    <input type="submit" name="Login" class="btn btn-secondary" value="Login">
    <input type="text" name="txtInput2" />
    <input type="submit" name="UnHash" class="btn btn-secondary" value="UnHash">
</form>

<?php include "../include/footer.php";
