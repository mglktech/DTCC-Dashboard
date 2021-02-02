<?php include "../include/header.php";

if (isset($_POST["doc_id"])) {
    if (isset($_POST["doc_type"])) {
        $doc_id = $_POST["doc_id"];
        $doc_type = $_POST["doc_type"];
        if ($doc_type = "application") {
            $sql = "DELETE FROM applications_v0 WHERE app_id = $doc_id";
        }
        Query($sql);
    }
}
echo "Document has been successfully removed.";
include "../include/footer.php";
