<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../www-test/app/utils/test_utils.php';

Tester\Environment::setup();

$configurator = new Nette\Configurator;
$configurator->setDebugMode(false);
$configurator->setTempDirectory(__DIR__ . '/../www-test/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../www-test/app')
	->register();
$configurator->addConfig(__DIR__ . '/../www-test/app/config/config.neon');
$travisConfig = __DIR__ . '/config/config.travis.neon';
$localConfig = __DIR__ . '/config/config.local.neon';
if (file_exists($localConfig)) {
	$configurator->addConfig($localConfig);
} elseif (file_exists($travisConfig)) {
	$configurator->addConfig($travisConfig);
}
return $configurator->createContainer();
