<?php
header("content-type:text/html;charset=utf-8");

function out($s, $t = '')
{
    if (is_array($s)) {
        echo '<pre style="font-family:Consolas;font-size:18px">';
        print_r($s);
        // var_dump($s);
        echo '</pre>';
    } else {
        echo '<div style="font-family:Consolas;font-size:18px">';
        echo $s;
        echo '</div>';
    }
    echo ($t);
}

function rnd($n)
{
    $r = rand(0, str_repeat('9', $n));
    return sprintf("%0{$n}d", $r);
}