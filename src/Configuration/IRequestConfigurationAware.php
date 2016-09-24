<?php

namespace Buri\Resource\Configuration;


interface IRequestConfigurationAware
{
	public function setRequestConfiguration(RequestConfiguration $requestConfiguration);
}
