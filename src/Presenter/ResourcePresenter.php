<?php

namespace Buri\Resource\Presenter;

use Buri\Resource\Configuration\RequestConfiguration;
use Nette\Application;
use Nette\Application\UI\Presenter;

class ResourcePresenter extends Presenter
{
	/** @var  RequestConfiguration */
	protected $requestConfiguration;

	public $onPreCreate;

	/**
	 * @param RequestConfiguration $requestConfiguration
	 */
	public function setRequestConfiguration(RequestConfiguration $requestConfiguration)
	{
		$this->requestConfiguration = $requestConfiguration;
	}

	/**
	 * {@inheritdoc}
	 * Allow to load default templates for resources from this library
	 */
	public function formatTemplateFiles()
	{
		$files = parent::formatTemplateFiles();
		$files[] = realpath(__DIR__ . '/../Templates/' . $this->view . '.latte');

		return $files;
	}

	/**
	 * {@inheritdoc}
	 * Setup resource configuration based on current request
	 */
	public function run(Application\Request $request)
	{
		$this->requestConfiguration->handleRequest($request);
		return parent::run($request);
	}
}
