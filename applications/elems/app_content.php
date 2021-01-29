<h4 class="form-title">Driver Application Form</h4>
<h6 class="form-subtitle">Submitted On: <?= $app_info->date_submitted; ?></h6>
<div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-app">
    <div class="col">
        <div class="input-group d-flex justify-content-end justify-content-sm-center igroup-read">
            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Name:</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->name ?>" readonly="" style="max-width: 200px;">
            <div class="input-group-append"></div>
        </div>
        <div class="input-group d-flex justify-content-end justify-content-sm-center igroup-read">
            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Phone:</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->phone ?>" readonly="" style="max-width: 200px;">
            <div class="input-group-append"></div>
        </div>
        <div class="input-group d-flex justify-content-end justify-content-sm-center igroup-read">
            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Discord:</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->discord ?>" readonly="" style="max-width: 200px;">
            <div class="input-group-append"></div>
        </div>
        <div class="input-group d-flex justify-content-end justify-content-sm-center igroup-read">
            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">DOB:</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->DOB ?>" readonly="" style="max-width: 200px;">
            <div class="input-group-append"></div>
        </div>
    </div>
    <div class="col">
        <div class="input-group d-flex justify-content-start justify-content-sm-center igroup-read">
            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Steam:</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="url" value="<?= $app_info->steam_name ?>" readonly="" style="max-width: 200px;">
            <div class="input-group-append"></div>
        </div>
        <div class="input-group d-flex justify-content-start justify-content-sm-center igroup-read">
            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Steam ID:</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->SteamID ?>" readonly="" style="max-width: 200px;">
            <div class="input-group-append"></div>
        </div>
        <div class="input-group d-flex justify-content-start justify-content-sm-center igroup-read">
            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Zone:</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->Zone ?>" readonly="" style="max-width: 200px;">
            <div class="input-group-append"></div>
        </div>
        <div class="input-group d-flex justify-content-start justify-content-sm-center igroup-read">
            <div class="input-group-prepend"><span class="d-flex justify-content-end input-group-text span-form">Info:</span></div><input class="form-control d-flex d-xl-flex justify-content-start igroup-read-input" type="text" value="<?= $app_info->Info ?>" readonly="" style="max-width: 200px;">
            <div class="input-group-append"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="d-flex flex-column"><span class="span-textarea">Backstory</span><textarea class="architects-font" readonly="" rows="10"><?= $app_info->backstory ?></textarea></div>
    </div>
    <div class="col">
        <div class="d-flex flex-column"><span class="span-textarea">Reason</span><textarea class="architects-font" readonly="" rows="10"><?= $app_info->reason ?></textarea></div>
    </div>
</div>