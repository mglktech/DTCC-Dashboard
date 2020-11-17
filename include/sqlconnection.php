<?php include "db_connection.php";


function Query($sql)
{
    $response = array();
    $conn = OpenCon();
    $stmt = $conn->prepare($sql);
    if ($result = $conn->query($sql)) {

        if (!is_bool($result)) {
            while ($obj = $result->fetch_object()) {
                $response[] = $obj;
            }
            $result->close();
        }
    }
    if (!$conn->query($sql)) {
        //printf("Error message: %s\n", $conn->error);
        LogError($sql, $conn->error);
    }

    if (count($response) > 0) {
        return $response;
    }
    $conn->close();
}
function quotefix($str)
{
    return str_replace("'", "''", "$str");
}

function LogError($statement, $error)
{
    $time = time();
    $conn = OpenCon();
    $client = $_SESSION['steam_id'];
    $statement = quotefix($statement);
    $error = quotefix($error);
    $sql = "INSERT INTO `sql_errors` (`timestamp`,`client`,`sqlcode`,`errormsg`)VALUES('$time','$client','$statement','$error')";
    $conn->query($sql);
    $sql = "SELECT `id` FROM `sql_errors` where `timestamp` = '$time' ORDER BY `id`";
    $resp = Query($sql);
    printf("SQL Error detected. Error ID: %s\n", $resp[0]->id);
}
