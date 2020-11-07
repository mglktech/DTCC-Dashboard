<?php include_once "../include/header.php"; ?>
<form method="post">
    <p><input type="button" name="Gen" class="btn btn-secondary" value="Click To Generate Code" onclick="generateCode()"></p>
    <p><input type="text" id="txtCode" /></p>

</form>
<?php include_once "../include/footer.php"; ?>
<script>
    function generateCode() {
        var length = 6,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        // return retVal;
        document.getElementById("txtCode").value = retVal;
    }
</script>