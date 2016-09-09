<?php

use Tracy\Debugger;

function d($a, $b = false){
    return Debugger::dump($a, $b);
}
function dd ($a, $b = false) {
    d($a, $b);
    exit;
}
function bd ($var, $title = NULL, array $options = NULL) {
    return Debugger::barDump($var, $title, $options);
}
