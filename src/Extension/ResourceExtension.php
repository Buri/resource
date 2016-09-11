<?php

namespace Buri\Resource\Extension;

use Buri\Resource\Presenter\PresenterFactory;
use Buri\Resource\Presenter\ResourcePresenter;
use Nette\Bridges\ApplicationDI\PresenterFactoryCallback;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;

class ResourceExtension extends CompilerExtension
{
	public $defaults = [
		'driver' => 'nettedb',
		'defaults' => [
			'presenter' => ResourcePresenter::class,
//			'views' => [
//				'default' => '',
//			],
		],
		'definitions' => [],
	];

	public function loadConfiguration()
	{
		$config = $this->getNormalizedConfig();
		$builder = $this->getContainerBuilder();

		// Load barebone definitions from service file
		Compiler::loadDefinitions(
			$builder,
			$this->loadFromFile(__DIR__ . '/../Configuration/services.neon'),
			$this->name
		);

		// Modify presenter factory to look for defined resource
		$presenterFactory = $builder->getDefinition('application.presenterFactory');
		$presenterFactory
			->setClass(PresenterFactory::class)
			->setFactory(PresenterFactory::class, [new Statement(
				PresenterFactoryCallback::class, $presenterFactory->getFactory()->arguments[0]->arguments
			)])
			->addSetup('setResourcesConfiguration', [$config]);
	}

	private function getNormalizedConfig()
	{
		$config = $this->getConfig($this->defaults);
		foreach ($config['definitions'] as $resource => $configuration) {
			$config['definitions'][$resource] = array_merge($config['defaults'], $configuration);
		}

		return $config;
	}
}
