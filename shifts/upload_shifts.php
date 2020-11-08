<?php include "../include/header.php"; ?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="btn_submit" value="Upload File" />
</form>

<?php
if (isset($_POST['btn_submit'])) {
    $fh = fopen($_FILES['file']['tmp_name'], 'r');

    $lines = array();
    while (($row = fgetcsv($fh, 100)) !== FALSE) {
        $lines[] = $row;
    }
    foreach ($lines as $line) {
        $Author = $line[1];
        $Datetime = strtotime($line[2]);
        $text = $line[3];
        if ($Datetime > 1604102400) {
            echo $Author . ": " . $text . " (at " . $Datetime . " )";
            echo "<br>";
        }
    }
    //var_dump($lines);
}

?>
<?php include "../include/footer.php"; ?>