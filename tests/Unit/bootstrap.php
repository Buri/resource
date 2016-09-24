<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../www-test/app/utils/test_utils.php';

Tester\Environment::setup();

$configurator = new Nette\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../www-test/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../www-test/app')
	->register();
$configurator->addConfig(__DIR__ . '/../www-test/app/config/config.neon');
$configurator->addConfig(__DIR__ . '/../www-test/app/config/config.local.neon');
return $configurator->createContainer();
