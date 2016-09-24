<?php

namespace Buri\Resource\Database;

use Buri\Resource\Configuration\RequestConfiguration;
use Nette\Database\Context;
use Nette\DI\Container;

class ResourceRepositoryFactory
{
	const NETTE_DATABASE = 'nettedb';
	/** @var string */
	protected $driverName;

	/** @var Container */
	protected $context;

	/**
	 * ResourceRepositoryFactory constructor.
	 * @param string $driverName
	 * @param Container $context
	 */
	public function __construct($driverName, Container $context)
	{
		$this->driverName = $driverName;
		$this->context = $context;
	}


	/**
	 * @param $configuration
	 * @return IRepository
	 */
	public function createRepositoryForResource($configuration)
	{
		switch ($this->driverName) {
			case self::NETTE_DATABASE:
				$repository = new NetteDatabaseRepository($this->context->getByType(Context::class), $configuration['table']);
				$repository->setRequestConfiguration($this->context->getByType(RequestConfiguration::class));
				return $repository;
		}

		throw new \InvalidArgumentException(
			sprintf('Unrecognized driver "%s"', $this->driverName)
		);
	}
}
