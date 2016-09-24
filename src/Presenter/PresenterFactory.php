<?php

namespace Buri\Resource\Presenter;

use Buri\Resource\Configuration\RequestConfiguration;
use Buri\Resource\Helpers\ResourceHelper;
use Nette\Application\PresenterFactory as BasePresenterFactory;

class PresenterFactory extends BasePresenterFactory
{
	/** @var  RequestConfiguration */
	protected $requestConfiguration;

	/** @var string[] */
	protected $presenterToResourceMap = [];

	/**
	 * @param RequestConfiguration $requestConfiguration
	 */
	public function setRequestConfiguration(RequestConfiguration $requestConfiguration)
	{
		$this->requestConfiguration = $requestConfiguration;
		$this->warmUpResourceMap();
	}

	private function warmUpResourceMap()
	{
		$resourceNames = array_keys($this->requestConfiguration->getConfiguration()['definitions']);
		foreach ($resourceNames as $resource) {
			$normalizedResourceName = ResourceHelper::normalizeResourceName($resource);
			$this->presenterToResourceMap[$normalizedResourceName] = $resource;
		}
	}

	public function createPresenter($name)
	{
		$presenter = parent::createPresenter($name);

		if ($presenter instanceof ResourcePresenter) {
			if (isset($this->presenterToResourceMap[$name])) {
				$this->requestConfiguration->setCurrentConfiguration($this->presenterToResourceMap[$name], $name);
			}
			$presenter->setRequestConfiguration($this->requestConfiguration);
		}

		return $presenter;
	}

	public function getPresenterClass(& $name)
	{
		if (isset($this->presenterToResourceMap[$name])) {
			return $this->requestConfiguration->getConfigurationForResource(
				$this->presenterToResourceMap[$name]
			)['presenter'];
		}

		return parent::getPresenterClass($name);
	}

}
