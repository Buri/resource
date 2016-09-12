<?php

namespace Buri\Resource\Configuration;

use Nette\Application\Request;
use Nette\Http\IRequest;

class RequestConfiguration
{
	/**
	 * @var IRequest
	 */
	protected $request;
	/**
	 * @var array
	 */
	protected $configuration;

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
		$this->initialized = true;
	}

	public function getConfigurationForResource($resource)
	{
		if (isset($this->configuration[$resource])) {
			return $this->configuration[$resource];
		}

		return [];
	}

	/**
	 * @return array
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}

	public function isActionSecured()
	{
		$this->assertInitialized();
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
}
