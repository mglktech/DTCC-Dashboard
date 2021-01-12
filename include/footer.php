<?php
$pages_noback = ["q_login.php", "index.php", "shifts_index.php", "shifts_index_AllTime.php", "shifts_index_PastMonth.php", "shifts_index_PastWeek.php", "verify_shifts.php"]; ?>

<?php if (!in_array(basename($_SERVER['PHP_SELF']), $pages_noback)) { ?>
  <div><button class="btn btn-secondary mt-3 mb-3" onclick="goBack()">Go Back</button></div>
<?php } ?>
<script>
  function goBack() {
    window.history.back();
  }
</script>
</main>
</div>
</div>
</body>
<!-- Bootstrap core JavaScript
  ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<!-- Icons -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
  feather.replace();
</script>

</html>