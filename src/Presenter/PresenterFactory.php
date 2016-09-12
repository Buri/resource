<?php

namespace Buri\Resource\Presenter;

use Buri\Resource\Configuration\RequestConfiguration;
use Nette\Application\PresenterFactory as BasePresenterFactory;

class PresenterFactory extends BasePresenterFactory
{
	/** @var  RequestConfiguration */
	protected $requestConfiguration;

	/**
	 * @param RequestConfiguration $requestConfiguration
	 */
	public function setRequestConfiguration(RequestConfiguration $requestConfiguration)
	{
		$this->requestConfiguration = $requestConfiguration;
	}

	public function createPresenter($name)
	{
		$presenter = parent::createPresenter($name);

		if ($presenter instanceof ResourcePresenter) {
			$presenter->setRequestConfiguration($this->requestConfiguration);
		}

		return $presenter;
	}


	public function getPresenterClass(& $name)
	{
		foreach ($this->requestConfiguration->getConfiguration()['definitions'] as $resource => $configuration) {
			$normalizedResourceName = $this->resourceToPresenter($resource);
			if ($normalizedResourceName === $name) {
				return $configuration['presenter'];
			}
		}

		return parent::getPresenterClass($name);
	}

	private function resourceToPresenter($name)
	{
		$formatted = mb_convert_case($name, MB_CASE_TITLE);
		$formatted = preg_replace('/[-_]/', '', $formatted);

		return $formatted;
	}

}
