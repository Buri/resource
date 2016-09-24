<?php

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/utils/test_utils.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode('192.168.0.29'); // enable for your remote IP
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$travisConfig = __DIR__ . '/config/config.travis.neon';
$localConfig = __DIR__ . '/config/config.local.neon';
if (file_exists($localConfig)) {
	$configurator->addConfig($localConfig);
} elseif (file_exists($travisConfig)) {
	$configurator->addConfig($travisConfig);
}


$container = $configurator->createContainer();

return $container;
