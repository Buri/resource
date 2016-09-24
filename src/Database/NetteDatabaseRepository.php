<?php

namespace Buri\Resource\Database;

use Buri\Resource\Configuration\IRequestConfigurationAware;
use Buri\Resource\Configuration\RequestConfigurationAwareTrait;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Paginator;

class NetteDatabaseRepository implements IRepository, IRequestConfigurationAware
{
	use RequestConfigurationAwareTrait;

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

	public function remove($object)
	{
		if ($object instanceof ActiveRow) {
			$object->delete();
		}
	}

	public function findAll()
	{
		return $this->table()->fetchAll();
	}

	protected function table()
	{
		return $this->connection->table($this->tableName);
	}

	public function getCurrentPage(Paginator $paginator)
	{
		$paginator->setItemsPerPage($this->requestConfiguration->getItemsPerPage());
		$paginator->setItemCount($this->table()->count('*'));

		return $this->table()->limit($paginator->getItemsPerPage(), $paginator->getOffset())->fetchAll();
	}

}
