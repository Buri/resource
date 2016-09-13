<?php

namespace Buri\Resource\Extension;

use Buri\Resource\Configuration\RequestConfiguration;
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
			'table' => null,
			'presenter' => ResourcePresenter::class,
			'actions' => [
				'default' => [
					'secure' => true,
					'paginate' => true,
					'sortable' => true,
					'sort' => [
						'id' => 'desc',
					],
				],
			],
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

		$builder->addDefinition($this->prefix('requestConfiguration'))
			->setClass(RequestConfiguration::class, [$config]);

		// Modify presenter factory to look for defined resource
		$presenterFactory = $builder->getDefinition('application.presenterFactory');
		$presenterFactory
			->setClass(PresenterFactory::class)
			->setFactory(PresenterFactory::class, [new Statement(
				PresenterFactoryCallback::class, $presenterFactory->getFactory()->arguments[0]->arguments
			)])
			->addSetup('setRequestConfiguration', [$this->prefix('@requestConfiguration')]);
	}

	private function getNormalizedConfig()
	{
		$config = $this->getConfig($this->defaults);
		foreach ($config['definitions'] as $resource => $configuration) {
			$config['definitions'][$resource] = array_replace_recursive($config['defaults'], $configuration);
		}

		return $config;
	}
}
