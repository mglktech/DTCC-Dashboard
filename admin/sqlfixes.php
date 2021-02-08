<?php include "../include/components/head.php";



function Fill()
{
    $steamids = Query("SELECT `steam_id` FROM `players` WHERE 1");
    foreach($steamids as $s)
    {
        // $extras = CollectFurtherAppInfo($s->steam_id);
        // if($extras)
        // {
        //     Query("UPDATE `players` SET `backstory` = '$extras[0]' , `steam_link`='$extras[1]' WHERE `steam_id` = '$s->steam_id'");
        // }
        $ES = EmploymentStart($s->steam_id);
        if($ES)
        {
            Query("UPDATE `players` SET `employment_start` = '$ES' WHERE `steam_id` = '$s->steam_id'");
        }
    }
}

function CollectFurtherAppInfo($steamid)
{
    $sql = "SELECT `char_backstory`, `steam_link` FROM `applications_v0` WHERE `steam_id` = '$steamid' AND `status` = 'accept' ORDER BY `app_id` DESC LIMIT 1";
    $resp = Query($sql);
    if(isset($resp[0]))
    {
        return [quotefix($resp[0]->char_backstory),$resp[0]->steam_link];
    }
    else
    {
        return null;
    }
}

Fill();

function EmploymentStart($steamid)
{
    $sql = "SELECT `submit_date` FROM `tests` WHERE `type` = 'Practical' and `score_total` >= '26' and `steam_id` = '$steamid' ORDER BY `id` DESC LIMIT 1";
    $resp = Query($sql);
    if(isset($resp[0]))
    {
        return $resp[0]->submit_date;
    }
    else
    {
        return null;
    }
}

include "../include/components/foot.php";