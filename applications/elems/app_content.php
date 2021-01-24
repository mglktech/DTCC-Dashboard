<div class="container border">
    <div class="d-flex flex-row-reverse">
        <label>Version: <?= $app_info->version; ?></label>
    </div>

    <div class="row justify-content-center mt-2">
        <h1>Application Form</h1>
    </div>
    <div class="row justify-content-center">
        <h6>Submitted On: <?= $app_info->date_submitted; ?></h6>
    </div>
    <div class="row mt-5 mb-3">
        <div class="col">
            <?= CreateInputElemFull(SpanPrepend("Name: "), SpanMiddleDefault($app_info->name), ""); ?>
            <?= CreateInputElemFull(SpanPrepend("Phone: "), SpanMiddleDefault($app_info->phone), ""); ?>
            <?= CreateInputElemFull(SpanPrepend("Discord: "), SpanMiddleDefault($app_info->discord), ""); ?>
            <?= CreateInputElemFull(SpanPrepend("DOB: "), SpanMiddleDefault($app_info->DOB), ""); ?>
        </div>
        <div class="col">
            <?= CreateInputElemFull(SpanPrepend("Steam: "), SpanMiddleDefault($app_info->steam_name), ""); ?>
            <?= CreateInputElemFull(SpanPrepend("SteamID: "), SpanMiddleDefault($app_info->SteamID), ""); ?>
            <?= CreateInputElemFull(SpanPrepend("Zone: "), SpanMiddleDefault($app_info->Zone), ""); ?>
            <?= CreateInputElemFull(SpanPrepend("Info: "), SpanMiddleDefault($app_info->Info), ""); ?>
        </div>
    </div>
    <div class="row mt-5 mb-3">
        <div class="col">
            <h5 class="h5-header-label mb-0 w-100 text-center">Character Backstory</h5>
            <div class="border p-4 text-background-grey architects-font">
                <span class="font-weight-normal"><?= $app_info->backstory ?></span>
            </div>
        </div>
        <div class="col">
            <h5 class="h5-header-label mb-0 w-100 text-center">Reason for Applying</h5>
            <div class="border p-4 text-background-grey architects-font">
                <span class="font-weight-normal"><?= $app_info->reason ?></span>
            </div>
        </div>
    </div>
</div>