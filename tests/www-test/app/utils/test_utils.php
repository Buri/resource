<?php

use Tracy\Debugger;

function d($a, $b = false){
    return Debugger::dump($a, $b);
}
function dd () {
    foreach (func_get_args() as $arg) {
        d($arg);
    }
    exit;
}
function bd ($var, $title = NULL, array $options = NULL) {
    return Debugger::barDump($var, $title, $options);
}
