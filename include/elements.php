<?php

function SpanPrepend($text)
{
    return "<span class='input-group-text bg-secondary text-light'>" . $text . "</span>";
}

function SpanIsAlive($IsAlive)
{
    if ($IsAlive == 1) {
        return "<span class='input-group-text bg-success text-light font-weight-bold'>Alive</span>";
    }
    if ($IsAlive == 0) {
        return "<span class='input-group-text bg-danger text-light font-weight-bold'>Deceased</span>";
    }
    if ($IsAlive == -1) {
        return "<span class='input-group-text bg-warning text-dark font-weight-bold'>Player Not Found</span>";
    }
    if ($IsAlive == -2) {
        return "<span class='input-group-text bg-info text-dark font-weight-bold'>Can't Connect</span>";
    }
    if ($IsAlive == -3) {
        return "<span class='input-group-text bg-danger text-dark font-weight-bold'>Bad Character Name</span>";
    }
}

function Pill($text)
{
    if ($text == "accept") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:green'>Accepted</div>";
    }
    if ($text == "ignore") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:grey'>Ignored</div>";
    }
    if ($text == "deny") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:crimson'>Denied</div>";
    }
    if ($text == "passed") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:green'>PASS</div>";
    }
    if ($text == "failed") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:crimson'>FAIL</div>";
    }
    if ($text == "theory") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:steelblue'>Theory</div>";
    }
    if ($text == "practical") {
        return "<div class='border border-dark text-dark text-center font-weight-bold rounded-pill' style='background-color:goldenrod'>Practical</div>";
    }
    if ($text == "Recruit") {
        return "<div class='border border-dark text-dark text-center font-weight-bold rounded-pill' style='background-color:white; font-size:12px;'>Recruit</div>";
    }
    if ($text == "Driver") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:gray; font-size:12px;'>Driver</div>";
    }
    if ($text == "Private Hire") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:darkslategrey; font-size:12px;'>Private Hire</div>";
    }
    if ($text == "Instructor") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:darkslategrey; font-size:12px;'>Instructor</div>";
    }
    if ($text == "Supervisor") {
        return "<div class='border border-dark text-dark text-center font-weight-bold rounded-pill' style='background-color:khaki; font-size:12px;'>Supervisor</div>";
    }
    if ($text == "Senior Supervisor") {
        return "<div class='border border-dark text-dark text-center font-weight-bold rounded-pill' style='background-color:gold; font-size:12px;'>Senior Supervisor</div>";
    }
    if ($text == "Overboss") {
        return "<div class='border border-dark text-white text-center font-weight-bold rounded-pill' style='background-color:dodgerblue; font-size:12px;'>CabCo Boss</div>";
    }
}

function Modal($id, $header, $body, $footer)
{
    echo "<div class='modal fade' id='" . $id . "' tabindex='-1' role='dialog' aria-hidden='true'>";
    echo "<div class='modal-dialog modal-dialog-centered' role='document'>"; // could potentially forward entire modal content
    echo "<div class='modal-content'>";
    echo "<div class='modal-header'>";
    echo "<h5 class='modal-title text-center'>" . $header . "</h5>";
    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
    echo "<span aria-hidden='true'>&times;</span>";
    echo "</button></div>";
    echo "<div class='modal-body text-center'>";
    echo $body . "</div>";
    echo "<div class='modal-footer'>";
    echo $footer;
    echo "</div></div></div></div>";
}


function Tablefy($headers, $body)
{
    if ($body) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
        foreach ($headers as $h) {
            echo "<th>" . $h . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($body as $row) {
            echo "<tr>";
            foreach ($row as $d) {
                echo "<td>" . $d . "</td>";
            }
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "Table is empty";
    }
}

function SpanBtnLink($label, $link)
{
    return "<a class='btn btn-info' href='$link' target='_blank'>$label</a>";
}
function SpanMiddleDefault($text)
{
    return "<input class='form-control' value='" . $text . "' disabled>";
}


function CreateRichInputElem($prepend, $middle, $append)
{

    echo "<div class='input-group mb-3'>";
    if ($prepend) {
        echo "<div class='input-group-prepend'>";
        echo "<span class='input-group-text bg-secondary text-light'>" . $prepend . "</span>";
        echo "</div>";
    }
    echo $middle;
    if ($append) {
        echo "<div class='input-group-append'>";
        echo "<span class='input-group-text bg-secondary text-light'>" . $append . "</span>";
        echo "</div>";
    }
    echo "</div>";
}

function CreateInputElemFull($prepend, $middle, $append)
{
    echo "<div class='input-group mb-3 '>";
    if ($prepend) {
        echo "<div class='input-group-prepend'>";
        echo $prepend;
        echo "</div>";
    }
    echo $middle;
    if ($append) {
        echo "<div class='input-group-append'>";
        echo $append;
        echo "</div>";
    }
    echo "</div>";
}

function CreateInputElem($prepend, $middle, $append)
{

    echo "<div class='input-group mb-3 '>";
    if ($prepend) {
        echo "<div class='input-group-prepend'>";
        echo "<span class='input-group-text bg-secondary text-light'>" . $prepend . "</span>";
        echo "</div>";
    }
    echo "<input class='form-control' value='" . $middle . "' disabled>";
    if ($append) {
        echo "<div class='input-group-append'>";
        echo "<span class='input-group-text bg-secondary text-light'>" . $append . "</span>";
        echo "</div>";
    }
    echo "</div>";
}

function CreateInputBtnElem($prepend, $middle, $link)
{

    echo "<div class='input-group mb-3 '>";
    if ($prepend) {
        echo "<div class='input-group-prepend'>";
        echo "<span class='input-group-text bg-secondary text-light'>" . $prepend . "</span>";
        echo "</div>";
    }
    echo "<input class='form-control' value='" . $middle . "' disabled>";
    if ($link) {
        echo "<div class='input-group-append'>";
        echo "<a class='btn btn-info' href= '" . $link->href . "' target='_blank'>" . $link->label . "</a>";
        echo "</div>";
    }
    echo "</div>";
}

function CreatePaginateObj($count, $limit)
{
    isset($_GET['page']) ? $page = quotefix($_GET['page']) : $page = 1;
    if ($page > 1) {
        $start = ($page * $limit) - $limit;
    } else {
        $start = 0;
    }
    $totalPages = ceil($count / $limit);
    $obj = new stdClass();
    $obj->start = $start;
    $obj->totalPages = $totalPages;
    $obj->page = $page;
    $obj->count = $count;
    $obj->limit = $limit;
    return $obj;
}

function Paginate($obj)
{
    //$obj = CreatePaginateObj($count, $limit);

    echo " <nav> <ul class='pagination flex-wrap'>";
    echo "<li class='page-item";
    if ($obj->page == 1) {
        echo " disabled";
    }
    echo "'>";


    echo "<a class='page-link' href='?page=" . ($obj->page - 1) . "' tabindex='-1'>Previous</a></li>";

    for ($i = 1; $i <= $obj->totalPages; $i++) {
        echo "<li class='page-item";
        if ($obj->page == $i) {
            echo " active";
        }
        echo "'> <a class='page-link' href='?page=" . $i . "'>" . $i . "</a></li>";
    }
    echo "<li class='page-item";
    if ($obj->page == $obj->totalPages) {
        echo " disabled";
    }
    echo "'>";
    echo "<a class='page-link' href='?page=" . ($obj->page + 1) . "' tabindex='-1'>Next</a>";
    echo "</li></ul></nav>";
    // echo $obj->totalPages . " total pages <br>";
    // echo $obj->count . " counted <br>";
    // echo "limit " . $obj->limit . "<br>";
}
