<?php

namespace Buri\Resource\Presenter;

use Nette\Application\PresenterFactory as BasePresenterFactory;

class PresenterFactory extends BasePresenterFactory
{
	/** @var  array */
	protected $resourcesConfiguration;

	/**
	 * @param array $resourcesConfiguration
	 */
	public function setResourcesConfiguration(array $resourcesConfiguration)
	{
		$this->resourcesConfiguration = $resourcesConfiguration;
	}

	public function getPresenterClass(& $name)
	{
		foreach ($this->resourcesConfiguration['definitions'] as $resource => $configuration) {
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
