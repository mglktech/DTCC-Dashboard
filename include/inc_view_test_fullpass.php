<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <h6>Copy/Pasta to send directly to recruit:</h6>
            <div class="border text-center p-4">
                Congratulations <?php echo $rvals['char_name'] ?><br>
                You are now a member of Downtown Cab Co!<br>
                Your new callsign is: <?php echo $rvals["callsign"] . " | " . $rvals["char_name"]; ?><br>
                feel free to join our Offical Discord Server ->https://discord.gg/C79Kgcys<br>
                Please be patient while we assign you a ***shiny new tag***, you should be able to clock in on the next server restart.<br>
                We look forward to seeing you on shift!<br>
                Kind Regards<br>
                <?php echo $rvals["signed_by"]; ?>
            </div>
        </div>
        <div class="col-6">
            <h6>Copy/paste for #database-todos:</h6>
            <div class="border text-center p-4 mb-3">
                Please **remove** DTCC Recruitment tag from @<?php echo $rvals['discord_name']; ?>, and give him the main Downtown Cab Co. tag. Thanks!
            </div>
            <h6>No Whitelisting access?
                Post this in #supervisors:</h6>
            <div class="border text-center p-4">
                Please **Whitelist** @<?php echo $rvals['discord_name']; ?> <br>
                as ```<?php echo $rvals["callsign"] . " | " . $rvals["char_name"]; ?>```<br>
                Hex: ```<?php echo $rvals['hex']; ?>```<br>
                Thanks!
            </div>
        </div>
    </div>
</div>