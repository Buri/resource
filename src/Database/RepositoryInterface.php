<?php

namespace Buri\Resource\Database;


interface RepositoryInterface
{
	public function find($objectId);
	public function findAll();

	public function createPager();
}
