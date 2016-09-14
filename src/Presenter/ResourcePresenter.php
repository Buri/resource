<?php

namespace Buri\Resource\Presenter;

use Buri\Resource\Configuration\RequestConfiguration;
use Buri\Resource\Database\IRepository;
use Nette\Application;
use Nette\Application\UI\Presenter;

class ResourcePresenter extends Presenter
{
	const ACTION_INDEX = 'default';

	/** @var  RequestConfiguration */
	protected $requestConfiguration;

	/** @var IRepository */
	protected $repository;

	public $onPreCreate;

	/**
	 * @param RequestConfiguration $requestConfiguration
	 */
	public function setRequestConfiguration(RequestConfiguration $requestConfiguration)
	{
		$this->requestConfiguration = $requestConfiguration;
		$this->repository = $this->context->getService(
			sprintf(
				'resource.%s.repository',
				$requestConfiguration->getNormalizedName()
			)
		);
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

	public function actionDefault()
	{
		$this->isGrantedOr403(self::ACTION_INDEX);

		$resources = $this->requestConfiguration->isPageable(self::ACTION_INDEX) ?
			$this->repository->createPager() : $this->repository->findAll();

		$this->template->resources = $resources;
	}

	protected function createComponent($name)
	{
		$component = parent::createComponent($name);
		if (null === $component) {
			// TODO: create desired component
		}

		return $component;
	}


	protected function isGrantedOr403($permission)
	{
		if ($this->requestConfiguration->isActionSecured($permission)) {
			$user = $this->getUser();
			if (!$user->isAllowed($this->requestConfiguration->getNormalizedName(), $permission)) {
				throw new Application\ForbiddenRequestException();
			}
		}
	}
}
