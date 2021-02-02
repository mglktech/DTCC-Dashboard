<?php include "../include/header.php";


function char_status($name)
{

    $exp = explode(" ", $name);
    if (count($exp) > 1) {
        $ctx = stream_context_create(array('http' =>
        array(
            'timeout' => 1,  //1200 Seconds is 20 Minutes
        )));
        $link = "http://playhigh.life:30120/highlife/character/" . $exp[0] . "/" . $exp[1];
        $reply = @file_get_contents($link, false, $ctx);
        if ($reply ===  FALSE) {
            return -2; // Can't Connect to highlife's servers
        } else {
            $r = json_decode($reply);
        }
        if (isset($r->data->dead)) {
            if ($r->data->dead) {
                return 0; // Character is Deceased.
            } else {
                return 1; // Character is Alive
            }
        } else {
            return -1; // Character Name not found
        }
    } else {
        return -3;
    }
}

function CheckAllPlayers()
{
    $sql = "SELECT char_name FROM players";
    $players = Query($sql);
    foreach ($players as $p) {
        usleep(500000);
        $status = char_status($p->char_name);
        if ($status != -2) {
            $sql = "UPDATE players SET hl_char_status='$status' where char_name = '$p->char_name'";
            $r = Query($sql);
        }
        print_r($status);
    }
}

function SpanIsAlive($IsAlive)
{
    if ($IsAlive == 1) {
        return "<span class='input-group-text bg-success text-light font-weight-bold'>Alive</span>";
    }
    if ($IsAlive == 0) {
        return "<span class='input-group-text bg-danger text-light font-weight-bold'>Deceased</span>";
    }
    if ($IsAlive == -1) {
        return "<span class='input-group-text bg-warning text-dark font-weight-bold'>Player Not Found</span>";
    }
    if ($IsAlive == -2) {
        return "<span class='input-group-text bg-info text-dark font-weight-bold'>Can't Connect</span>";
    }
    if ($IsAlive == -3) {
        return "<span class='input-group-text bg-danger text-dark font-weight-bold'>Bad Character Name</span>";
    }
}

CheckAllPlayers();
include "../include/footer.php";
