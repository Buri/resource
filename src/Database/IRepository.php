<?php

namespace Buri\Resource\Database;

use Nette\Utils\Paginator;

interface IRepository
{
	public function find($objectId);
	public function findAll();
	public function getCurrentPage(Paginator $paginator);
	public function remove($object);
}
