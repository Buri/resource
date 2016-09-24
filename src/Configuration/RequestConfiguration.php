<?php

namespace Buri\Resource\Configuration;

use Nette\Application\BadRequestException;
use Nette\Application\Request;

class RequestConfiguration
{
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var array
	 */
	protected $configuration;

	/** @var array */
	protected $currentConfiguration = null;

	/** @var string */
	protected $normalizedName;

	/** @var string */
	protected $resourceName;

	/**
	 * @var bool Has configuration handled request already?
	 */
	private $initialized = false;

	/**
	 * @param array $configuration
	 */
	public function __construct(array $configuration)
	{
		$this->configuration = $configuration;
	}

	public function handleRequest(Request $request)
	{
		if ($request->getPresenterName() !== $this->normalizedName) {
			throw new BadRequestException(sprintf(
				'Resource "%s" is not configured for presenter "%s"',
				$this->normalizedName,
				$request->getPresenterName()
			));
		}

		$this->request = $request;
		$this->initialized = true;
	}

	/**
	 * @param $currentConfiguration
	 * @param null $normalizedName
	 */
	public function setCurrentConfiguration($currentConfiguration, $normalizedName = null)
	{
		if (empty($this->configuration['definitions'][$currentConfiguration])) {
			throw new ResourceNotConfiguredException(
				sprintf(
					'Resource "%s" not found in configuration, did you mean one of those: %s',
					$currentConfiguration,
					implode(', ', array_keys($this->configuration['definitions']))
				)
			);
		}
		$this->currentConfiguration = $this->configuration['definitions'][$currentConfiguration];
		$this->normalizedName = $normalizedName === null ? $currentConfiguration : $normalizedName;
		$this->resourceName = $currentConfiguration;
	}

	public function getConfigurationForResource($resource)
	{
		if (isset($this->configuration['definitions'][$resource])) {
			return $this->configuration['definitions'][$resource];
		}

		return null;
	}

	/**
	 * @return array
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}

	/**
	 * @return string
	 */
	public function getNormalizedName()
	{
		return $this->normalizedName;
	}

	/**
	 * @return string
	 */
	public function getResourceName()
	{
		return $this->resourceName;
	}

	/**
	 * @param $action
	 * @return bool
	 */
	public function isActionSecured($action)
	{
		return $this->action($action)['secure'];
	}

	/**
	 * @param $name
	 * @return array
	 */
	protected function action($name)
	{
		$this->assertInitialized();
		if (isset($this->currentConfiguration['actions'][$name])) {
			return $this->currentConfiguration['actions'][$name];
		}

		return [];
	}

	protected function assertInitialized()
	{
		if (!$this->initialized) {
			throw new ResourceNotInitializedException(
				sprintf(
					'Called function "%s::%s" before request was handled',
					get_called_class(),
					debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']
				)
			);
		}
	}

	/**
	 * @param $action
	 * @return string
	 */
	public function getComponentClass($action)
	{
		return $this->action($action)['component'];
	}

	public function isPageable($action)
	{
		return $this->action($action)['paginate'];
	}

	public function getItemsPerPage()
	{
		return $this->action('default')['pageSize'];
	}

	public function getPresentationForTable($table)
	{
		foreach ($this->configuration['definitions'] as $definition) {
			if ($table === $definition['table']) {
				return $definition['presentation'];
			}
		}

		return $this->configuration['defaults']['presentation'];
	}

	/**
	 * @return Request
	 */
	public function getRequest()
	{
		return $this->request;
	}
}
