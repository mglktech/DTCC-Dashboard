<?php
$steamid = $_GET["steamid"];
$strikes = Query("SELECT * FROM public_strikes WHERE steam_id = '$steamid'");
$head = ["Issue Date", "Severity", "reason", "Struck by", "End Date"];
$body = array();
if ($strikes) {
    foreach ($strikes as $s) {
        $row = array();
        $row[] = toDateS($s->issue_date);
        $row[] = $s->severity;
        $row[] = quotefix($s->strike_desc);
        $row[] = quotefix($s->signed_callsign . " | " . $s->signed_by);
        $row[] = toDateS($s->end_date);
        $body[] = $row;
    }
}
Tablefy($head, $body);
