<?php

namespace Buri\Resource\Database;


interface RepositoryInterface
{
	public function find($id);
	public function findAll();

	public function createPager();
}
