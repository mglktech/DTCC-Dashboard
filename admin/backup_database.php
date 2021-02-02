
<?php

$db = EXPORT_DATABASE($_GET["host"], $_GET["user"], $_GET["pass"], $_GET["name"]);
$js = new stdClass();
$js->name = $db[0];
$js->content = $db[1];
echo json_encode($js);
