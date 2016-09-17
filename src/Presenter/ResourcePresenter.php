<?php

namespace Buri\Resource\Presenter;

use Buri\Resource\Configuration\IRequestConfigurationAware;
use Buri\Resource\Configuration\RequestConfiguration;
use Buri\Resource\Database\IRepository;
use Nette\Application;
use Nette\Application\UI\Presenter;
use IPub\VisualPaginator\Components\Control as VisualPaginatorControl;

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

	public function formatLayoutTemplateFiles()
	{
		$files = parent::formatLayoutTemplateFiles();
		$appRoot = $this->context->parameters['appDir'];
		$layout = $this->layout ? $this->layout : 'layout';
		$presenter = $this->getName();

		$files[] = "$appRoot/presenters/templates/$presenter/@$layout.latte";
		$files[] = "$appRoot/presenters/templates/$presenter.@$layout.latte";
		$files[] = "$appRoot/presenters/templates/@$layout.latte";

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
			$this->repository->getCurrentPage($this['visualPaginator']->getPaginator()) : $this->repository->findAll();

		$this->template->resources = $resources;
		$this->template->requestConfiguration = $this->requestConfiguration;
	}

	public function actionShow($id)
	{

	}

	public function actionUpdate($id)
	{

	}

	public function actionDelete($id)
	{

	}

	protected function createComponent($name)
	{
		$component = parent::createComponent($name);
		if (null === $component) {
			$serviceName = sprintf(
				'resource.control.%s.%s',
				lcfirst($this->requestConfiguration->getNormalizedName()),
				$name
			);
			if ($this->context->hasService($serviceName)) {
				$component = $this->context->getService($serviceName);
			}
			if (null === $component) {
				$fqcn = $this->requestConfiguration->getComponentClass($this->action);
				$component = $this->context->getByType($fqcn, false);
				if (null === $component) {
					$component = new $fqcn($this, $name);
				}
			}
		}

		if ($component instanceof IRequestConfigurationAware) {
			$component->setRequestConfiguration($this->requestConfiguration);
		}

		return $component;
	}

	protected function createComponentVisualPaginator($name) {
		// Init visual paginator
		$control = new VisualPaginatorControl('default.latte', $this, $name);
		$control->disableAjax();

		return $control;
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
