<?php 
function FigureName($cs, $name)
{
    if($cs)
    {
        return $cs . " | " . $name;
    }
    else
    {
        return $name;
    }
}

function FigureRankLabel($label)
{
    if($label)
    {
        return $label;
    }
    else
    {
        return "Not one of us";
    }
}

function FigureColour($warns)
{
    if($warns == 0)
    {
        return "text-success";
    }
    if($warns == 1)
    {
        return "text-warning";
    }
    else
    {
        return "text-danger";
    }
}

function FigureWarnings($id)
{
    $t = time();
    $sql = "SELECT COUNT(*) AS `count` FROM strikes WHERE steam_id = '$id' and end_date > '$t'";
    return Query($sql)[0]->count;   
}
?>
<section>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3 class="text-center"><?=FigureName($pInfo->public_player->callsign,$pInfo->public_player->char_name)?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h4 style="text-align: center;"><?=FigureRankLabel($pInfo->public_player->rank_label)?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center align-items-center"><img style="width: 300px;height: 300px;border-radius: 100%;border-style: solid;border-color: #333333;" src="<?=$pInfo->public_player->av_full?>"></div>
                <div class="col d-flex"><textarea class="d-flex flex-fill txt-backstory-profile" rows="10" readonly=""><?=$pInfo->public_player->backstory?></textarea></div>
            </div>
            <div class="row">
                <div class="col">
                    <ul class="list-unstyled text-center">
                        <!-- Company Tags -->
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center"><label class="col-form-label text-center <?=FigureColour(FigureWarnings($player_id))?>"><?=$pInfo->public_player->char_name?> has <?=FigureWarnings($player_id)?> active warnings.</label></div>
                <?php if($_SESSION['rank'] >= 3) {
                    include "super_partial.php";
                }?>
            </div>
        </div>
    </section>