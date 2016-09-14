<?php

namespace Buri\Resource\Database;


interface IRepository
{
	public function find($objectId);
	public function findAll();

	public function createPager();
}
