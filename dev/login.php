<?php include "../include/header.php";
include "../include/sqlconnection.php";
?>
<form method="post" target="">
    <input type="text" name="txtInput" />
    <input type="submit" name="Login" class="btn btn-secondary" value="Login">
    <input type="text" name="txtInput2" />
    <input type="submit" name="UnHash" class="btn btn-secondary" value="UnHash">
</form>
<?php include "../include/footer.php";
