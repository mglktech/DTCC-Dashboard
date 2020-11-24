<?php

?>
<div class="container border">
    <h6>Senior Supervisor Commands:</h6>

    <button class="btn bg-danger text-light" data-toggle="modal" data-target="#ResetCodeModal" <?php if ($rank < 2) echo "disabled" ?>>Password Reset</button>

</div>
<div class="modal fade" id="ResetCodeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="admin/create_temp_code.php" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resetting password for: <?php echo $char_name; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='input-group'>
                        <div class='input-group-prepend'>
                            <input type="button" name="Gen" class="btn btn-secondary" value="Click To Generate Code" onclick="generateCode()"></input>
                        </div>
                        <input id="txtCode" class="form-control" disabled></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="ChangePW" value='1' hidden>
                    <input name="txtSteamName" value='<?php echo $steam_name; ?>' hidden>
                    <input name="discord_name" value='<?php echo $discord_name; ?>' hidden>
                    <input name="txtNewPass" id="txtCodeHidden" hidden>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>
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
        document.getElementById("txtCodeHidden").value = retVal;
    }
    generateCode();
</script>