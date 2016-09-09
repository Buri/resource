<?php

namespace Buri\Resource\Routing;

use Nette\Caching\IStorage;
use Nette\MemberAccessException;

/**
 * Class ResourceRouterFactory
 * @package Buri\Resource\Routing
 * @property-read resourceRouter
 */
class ResourceRouterFactory
{
    /**
     * @var array
     */
    protected $resources;

    /** @var  IStorage */
    protected $storage;

    /**
     * @param array $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * @param IStorage $storage
     */
    public function setStorage(IStorage $storage)
    {
        $this->storage = $storage;
    }

    public function __get($name)
    {
        if ('resourceRouter' === $name) {
            return new ResourceRouter($this->storage, $this->resources);
        }

        throw new MemberAccessException();
    }
}