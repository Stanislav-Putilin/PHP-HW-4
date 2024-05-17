<?php
session_start();

function clear(string $str){
    return htmlentities(trim($str));
}

function redirect(string $page){
    header("Location: /$page");
    exit;
}

function dump($arrey)
{
    echo '<pre>' . print_r($arrey,true) . '</pre>';
}

