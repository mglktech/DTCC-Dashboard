<?php include "../include/components/head.php";
include "../steam/SteamWebAPI.php";
include "../include/elements.php";

isset($_GET["steamid"]) ? $steamid = quotefix($_GET["steamid"]) : $steamid = "76561197995263974";
isset($_SESSION["rank"]) ? $rank = $_SESSION["rank"] : $rank = null;

isset($_POST["new_steam_name"]) ? $newsteamname = quotefix($_POST["new_steam_name"])  : $newsteamname = null;
isset($_POST["new_name"]) ? $newname = quotefix($_POST["new_name"])  : $newname = null;
isset($_POST["new_phone"]) ? $newphone = quotefix($_POST["new_phone"])  : $newphone = null;
isset($_POST["new_status"]) ? $newstatus = quotefix($_POST["new_status"])  : $newstatus = null;
isset($_POST["new_discord"]) ? $newdiscord = quotefix($_POST["new_discord"])  : $newdiscord = null;
isset($_POST["new_rank"]) ? $newrank = quotefix($_POST["new_rank"])  : $newrank = null;

function getRecentSteamName($steamid)
{
    return GetSteamDetails($steamid)->steam_name;
}


if (Rank("Supervisor")) {

    if ($newname) {
        $sql = "UPDATE players SET char_name = '$newname' WHERE steam_id = '$steamid'";
        Query($sql);
    }
    if ($newsteamname) {
        $sql = "UPDATE players SET steam_name = '$newsteamname' WHERE steam_id = '$steamid'";
        Query($sql);
    }
    if ($newphone) {
        $sql = "UPDATE players SET phone_number = '$newphone' WHERE steam_id = '$steamid'";
        Query($sql);
    }
    if ($newstatus) {
        $sql = "UPDATE players SET `status` = '$newstatus' WHERE steam_id = '$steamid'";
        Query($sql);
    }
    if ($newdiscord) {
        $sql = "UPDATE players SET discord_name = '$newdiscord' WHERE steam_id = '$steamid'";
        Query($sql);
    }
    if ($newrank != null) {
        $sql = "UPDATE players SET rank = '$newrank' WHERE steam_id = '$steamid'";
        Query($sql);
        $sql = "UPDATE players SET whitelisted = '0' WHERE steam_id = '$steamid'";
        Query($sql);
    }
}



$doc_id = $steamid;
$sql = "SELECT * from public_players where steam_id = '$steamid'";
$player = Query($sql)[0];

$resp = Query($sql);
if ($resp) {
    $player = Query($sql)[0];
}
$doc_type = "player";

$flags = ["callsign", "name", "rank", "status", "phone", "discord", "SName"];

function ModalTitle($flag)
{
    $content = "";
    if ($flag == "callsign") {
        $content = "Callsign";
    }
    if ($flag == "name") {
        $content = "Character Name";
    }
    if ($flag == "rank") {
        $content = "Rank";
    }
    if ($flag == "status") {
        $content = "Player Status";
    }
    if ($flag == "phone") {
        $content = "Phone Number";
    }
    if ($flag == "discord") {
        $content = "Discord Name";
    }
    if ($flag == "SName") {
        $content = "Steam Name";
    }

    return "Change " . $content;
}

function ModalBody($flag, $rank)
{
    if ($flag == "callsign") {
        $content = "not built yet!";
    }
    if ($flag == "SName") {
        $content = "<input type='text' class='form-control' name='new_steam_name' value='" . getRecentSteamName($_GET["steamid"]) . "'>";
    }
    if ($flag == "name") {
        $content = "<input type='text' class='form-control' name='new_name'>";
    }
    if ($flag == "rank") {
        if (Rank("Supervisor")) {
            $content = "<select class='form-select' name='new_rank'>
            <option selected>Choose Rank</option>
            <option value='0'>Driver</option>
            <option value='1'>Private Hire</option>
            <option value='1.5'>Instructor</option>
        </select>";
        }
        if (Rank("Senior Supervisor")) {
            $content = "<select class='form-select' name='new_rank'>
            <option selected>Choose Rank</option>
            <option value='0'>Driver</option>
            <option value='1'>Private Hire</option>
            <option value='1.5'>Instructor</option>
            <option value='2'>Supervisor</option>
        </select>";
        }
        if (Rank("Overboss")) {
            $content = "<select class='form-select' name='new_rank'>
            <option selected>Choose Rank</option>
            <option value='0'>Driver</option>
            <option value='1'>Private Hire</option>
            <option value='1.5'>Instructor</option>
            <option value='2'>Supervisor</option>
            <option value='3'>Senior Supervisor</option>
        </select>";
        }
    }
    if ($flag == "status") {
        $content = "<input type='text' class='form-control' name='new_status'>";
    }
    if ($flag == "phone") {
        $content = "<input type='text' class='form-control' name='new_phone'>";
    }
    if ($flag == "discord") {
        $content = "<input type='text' class='form-control' name='new_discord'>";
    }
    $footer =  "<button type='submit'class='btn btn-success'>Submit</button>
    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Go Back</button>";
    return "<form id='MyForm' action='' method='post'> " . $content . $footer .  "</form>";
}

function ModalFooter($flag)
{
    $action = "";
    if ($flag == "name") {
    }
    $footer =  "";

    return $footer;
}

function BtnEdit($flag)
{
    return  "<button type='button' class='btn btn-secondary' data-toggle='modal' data-target='#" . $flag . "'>
    <i class='far fa-edit'></i>
</button>";
}



if ($rank > 2) {


?>
    <div class="container col-6">
        <?php
        CreateInputElem("Callsign: ", $player->callsign, "");
        CreateInputElemFull(SpanPrepend("Name: "), SpanMiddleDefault($player->char_name), BtnEdit("name"));
        CreateInputElemFull(SpanPrepend("Rank: "), SpanMiddleDefault($player->rank_label), BtnEdit("rank"));
        CreateInputElemFull(SpanPrepend("Status: "), SpanMiddleDefault($player->status), BtnEdit("status"));
        CreateInputElemFull(SpanPrepend("Phone: "), SpanMiddleDefault($player->phone_number), BtnEdit("phone"));
        CreateInputElemFull(SpanPrepend("Discord: "), SpanMiddleDefault($player->discord_name), BtnEdit("discord"));
        CreateInputElemFull(SpanPrepend("Steam Name:"), SpanMiddleDefault($player->steam_name), BtnEdit("SName"));
        //CreateInputElem("SteamID:", $player->steam_id, "");
        ?>

    </div>








<?php
    foreach ($flags as $flag) {
        Modal($flag, ModalTitle($flag), ModalBody($flag, $rank), ModalFooter($flag));
    }
}
?>
<?php include "../include/components/foot.php";
