<!DOCTYPE html>
<html lang="en">
<?php include 'include/includes.php'; ?>

<body>
  <?php include('include/header.php'); ?>
  <div class="container-fluid">
    <div class="row">
      <?php include('include/sidebar.php'); ?>
      <main id="main" role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">


        <!-- Below his is the content you want to work within!!! -->
        <h1 class="mb-5">Applications</h1>
        <?php
        include 'include/db_connection.php';
        $conn = OpenCon();
        $sql = "SELECT * FROM Applications_v0 WHERE signed_by = ''";
        $result = $conn->query($sql);
        CloseCon($conn);
        ?>
        <!-- Above is the content you want to work within!!! -->


      </main>
    </div>
  </div>
  <?php include 'include/footer.php'; ?>
</body>

</html>