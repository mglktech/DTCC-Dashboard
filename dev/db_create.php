<?php
include_once "../include/header.php";

include "../include/db_connection.php";


class Callsign
{
    public $label;
    public $desc;
    public $min_rank;
    public $max_rank;
    public $region;

    function getLabel()
    {
        return $this->label;
    }
    function getMinRank()
    {
        return $this->min_rank;
    }
    function getMaxRank()
    {
        return $this->max_rank;
    }
    function getRegion()
    {
        return $this->region;
    }
}

if (isset($_POST['Gen'])) {
    $callsigns = CreateCallsigns();
    $responses = [];
    foreach ($callsigns as $c) {
        //print_r($c);
        $label = $c->getLabel();
        $min_rank = $c->getMinRank();
        $max_rank = $c->getMaxRank();
        $region = $c->getRegion();
        $sql = "REPLACE INTO `callsigns`(`label`,`min_rank`, `max_rank`, `region` ) VALUES ('$label',$min_rank,$max_rank,'$region')";
        $response = sqlRun($sql);
        array_push($responses, $response);
    }
    foreach ($responses as $r) {
        echo $r . "<br>";
    }
}

function CreateCallsigns()
{
    //T1 = High Command
    //T2 = EU/UK Timezone
    //T3 = US/Eastern Timezone
    //01-10 - Supervisor
    //10-99 - Driver/PrivateHire

    // T1-01 min 4 max 4

    // T1-02-10 min 3 max 3

    // T2-01-10 min 2 max 2
    // T3-01-10 min 2 max 2

    // T2-11-75 min 0 max 1
    // T3-11-75 min 0 max 1

    $sign = "T1";
    $signs = [];

    for ($Ri = 1; $Ri <= 3; $Ri++) {
        $str = "T";
        for ($i = 1; $i <= 75; $i++) {

            if ($Ri == 1) {

                // T1 = minmax 4;
                if ($i == 1) {
                    $pad = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $c = new Callsign;
                    $c->label = $str . $Ri . "-" . $pad;
                    $c->desc = "";
                    $c->min_rank = "4";
                    $c->max_rank = "4";
                    array_push($signs, $c);
                }
                if ($i > 1 && $i <= 10) {
                    $pad = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $c = new Callsign;
                    $c->label = $str . $Ri . "-" . $pad;
                    $c->desc = "";
                    $c->min_rank = "3";
                    $c->max_rank = "3";
                    array_push($signs, $c);
                } else {
                }
            } else {
                if ($i <= 10) {
                    $pad = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $c = new Callsign;
                    $c->label = $str . $Ri . "-" . $pad;
                    $c->desc = "";
                    $c->min_rank = "2";
                    $c->max_rank = "2";
                    if ($Ri == 2) {
                        $c->region = "GMT";
                    }
                    if ($Ri == 3) {
                        $c->region = "EST";
                    }
                    array_push($signs, $c);
                } else {
                    $pad = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $c = new Callsign;
                    $c->label = $str . $Ri . "-" . $pad;
                    $c->desc = "";
                    $c->min_rank = "0";
                    $c->max_rank = "1";
                    if ($Ri == 2) {
                        $c->region = "GMT";
                    }
                    if ($Ri == 3) {
                        $c->region = "EST";
                    }
                    array_push($signs, $c);
                }
            }
        }
    }
    return $signs;
}
?>
<form method="post" action="db_create.php">
    <p><input type="submit" name="Gen" class="btn btn-secondary" value="Click To Generate Callsigns"></p>
</form>