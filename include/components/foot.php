<?php
$pages_noback = ["login.php", "index.php", "shifts_index.php", "shifts_index_AllTime.php", "shifts_index_PastMonth.php", "shifts_index_PastWeek.php", "verify_shifts.php"]; ?>
<div class="container">
    <?php if (!in_array(basename($_SERVER['PHP_SELF']), $pages_noback)) { ?>
        <button class="btn btn-dark btn-sm" type="button" onclick="goBack()">Go Back</button>
    <?php } ?>


    <footer class="d-flex justify-content-end justify-content-lg-end"><label style="color: rgb(185,190,194);">Version 0.1.0</label></footer>
</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/f98d92a3e6.js" crossorigin="anonymous"></script>
</body>

</html>