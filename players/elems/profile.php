<section>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3 class="text-center"><?=$pInfo->public_player->callsign?> | <?=$pInfo->public_player->char_name?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h4 style="text-align: center;"><?=$pInfo->public_player->rank_label?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center align-items-center"><img style="width: 300px;height: 300px;border-radius: 100%;border-style: solid;border-color: #333333;" src="<?=$pInfo->public_player->av_full?>"></div>
                <div class="col d-flex"><textarea class="d-flex flex-fill txt-backstory-profile" rows="10" readonly=""><?=$pInfo->public_player->backstory?></textarea></div>
            </div>
            <div class="row">
                <div class="col">
                    <ul class="list-unstyled text-center">
                        <li>Administrative Executive Officer</li>
                        <li>Senior Supervisor</li>
                        <li>Head of Recruitment GMT</li>
                        <li>Dashboard Developer</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center"><label class="col-form-label text-center text-success">Gorgeous George has 0 active warnings.</label></div>
                <?php if($_SESSION['rank'] >= 3) {
                    include "super_partial.php";
                }?>
            </div>
        </div>
    </section>