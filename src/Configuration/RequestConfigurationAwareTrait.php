<?php

namespace Buri\Resource\Configuration;

trait RequestConfigurationAwareTrait
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
}
