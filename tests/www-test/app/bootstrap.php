<?php

use Tracy\Debugger;

require __DIR__ . '/../../../vendor/autoload.php';

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

$configurator = new Nette\Configurator;

$configurator->setDebugMode('192.168.0.29'); // enable for your remote IP
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
