<?php

namespace Buri\Resource\Extension;

use Buri\Resource\Configuration\RequestConfiguration;
use Buri\Resource\Controls\ResourceGrid\ResourceGrid;
use Buri\Resource\Database\ResourceRepositoryFactory;
use Buri\Resource\Helpers\LatteHelpers;
use Buri\Resource\Helpers\ResourceHelper;
use Buri\Resource\Presenter\PresenterFactory;
use Buri\Resource\Presenter\ResourcePresenter;
use IPub\VisualPaginator\DI\VisualPaginatorExtension;
use Nette\Bridges\ApplicationDI\PresenterFactoryCallback;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;

class ResourceExtension extends CompilerExtension
{
	public $defaults = [
		'driver' => ResourceRepositoryFactory::NETTE_DATABASE,
		'defaults' => [
			'table' => null,
			'presentation' => 'name',
			'presenter' => ResourcePresenter::class,
			'actions' => [
				'default' => [
					'component' => ResourceGrid::class,
					'secure' => true,
					'paginate' => true,
					'pageSize' => 20,
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

		// Define request configuration as a service to be accessible from app
		$builder->addDefinition($this->prefix('requestConfiguration'))
			->setClass(RequestConfiguration::class, [$config]);

		// Create repositories for all registered resources
		$builder->addDefinition($this->prefix('driverFactory'))
			->setClass(ResourceRepositoryFactory::class, [$config['driver']]);
		foreach ($config['definitions'] as $resource => $configuration) {
			$builder->addDefinition($this->prefix(ResourceHelper::normalizeResourceName($resource) . '.repository'))
				->setFactory($this->prefix('@driverFactory::createRepositoryForResource'), [$configuration]);
		}

		// Register our helpers to latte
		$builder->getDefinition('latte.latteFactory')
			->addSetup('addFilter', [null, LatteHelpers::class . '::register']);

		// Modify presenter factory to look for defined resource
		$presenterFactory = $builder->getDefinition('application.presenterFactory');
		$presenterFactory
			->setClass(PresenterFactory::class)
			->setFactory(PresenterFactory::class, [new Statement(
				PresenterFactoryCallback::class, $presenterFactory->getFactory()->arguments[0]->arguments
			)])
			->addSetup('setRequestConfiguration', [$this->prefix('@requestConfiguration')]);
	}

	public function setCompiler(Compiler $compiler, $name)
	{
		$compiler->addExtension('visualPaginator', new VisualPaginatorExtension());
		return parent::setCompiler($compiler, $name);
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
