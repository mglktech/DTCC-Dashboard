<?php include "include/header.php"; 
include "steam/SteamWebAPI_Simple.php";
$steam_name = GetSteamDetails(ResolveSteamID("https://steamcommunity.com/profiles/76561198256900062/"))->steam_name;
?>
<?php echo $steam_name; ?>
<?php include_once "include/footer.php";
