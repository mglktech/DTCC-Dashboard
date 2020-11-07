<?php

function SpanPrepend($text)
{
    return "<span class='input-group-text bg-secondary text-light'>" . $text . "</span>";
}
// simple change
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
