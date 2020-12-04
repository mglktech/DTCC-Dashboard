<?php include "include/header.php";
include "include/sqlconnection.php";
include "include/elements.php";

?>
<a class='btn btn-secondary mt-1 mb-3' data-toggle="tooltip" data-placement="top" title="Request access if you haven't got it yet" target="_blank" href='https://drive.google.com/drive/folders/1DkeJJ5uiEdpRJ0KCDMmGQsKFPxOaPzF4?usp=sharing'>Google Drive Folder</a>
<a class='btn btn-secondary mt-1 mb-3' data-toggle="tooltip" data-placement="top" title="Login to GitHub" target="_blank" href='https://github.com/pilotvollmar/DTCC-Dashboard/issues'>Feedback</a>
<br>
<?php

if ($_SESSION['rank'] > 2) {

    include "include/inc_index_senior.php";
}

include "include/footer.php";
