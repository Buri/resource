<?php

namespace Buri\Resource\Database;

use Nette\Database\Context;

class NetteDatabaseRepository implements IRepository
{
	/** @var Context */
	protected $connection;

	/** @var string */
	protected $tableName;

	/**
	 * NetteDatabaseRepository constructor.
	 * @param Context $connection
	 * @param string $tableName
	 */
	public function __construct(Context $connection, $tableName)
	{
		$this->connection = $connection;
		$this->tableName = $tableName;
	}

	public function find($objectId)
	{

	}

	public function findAll()
	{
		return $this->table()->fetchAll();
	}

	public function createPager()
	{
		return $this->table()->fetchAll();
	}

	protected function table()
	{
		return $this->connection->table($this->tableName);
	}

}
