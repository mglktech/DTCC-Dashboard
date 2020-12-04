<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <h6>Copy/Pasta to send directly to recruit:</h6>
            <div class="border text-center p-4">
                Congratulations <?php echo $rvals['char_name'] ?><br>
                You are now a member of Downtown Cab Co!<br>
                Your new callsign is: <?php echo $rvals["callsign"] . " | " . $rvals["char_name"]; ?><br>
                feel free to join our Offical Discord Server ->https://discord.gg/C8xjc29WXz<br>
                Please be patient while we assign you a ***shiny new tag***, You will be able to clock in as soon as you are whitelisted.<br>
                Whitelisting usually takes 12-24 hours.<br>
                We look forward to seeing you on shift!<br>
                Kind Regards<br>
                <?php echo $rvals["signed_by"]; ?>
            </div>
        </div>
        <div class="col-6">
            <h6>Copy/paste for #database-todos:</h6>
            <div class="border text-center p-4 mb-3">
                Please **remove** DTCC Recruitment tag from @<?php echo explode("#", $rvals['discord_name'])[0] ?>, and give him the Downtown Cab Co. tag. Thanks!
            </div>
        </div>
    </div>
</div>