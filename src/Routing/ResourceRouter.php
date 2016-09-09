<?php

namespace Buri\Resource\Routing;

use Nette;
use Nette\Application;
use Tracy\Debugger;

class ResourceRouter extends Application\Routers\Route  implements Application\IRouter
{
    /** @var Nette\Caching\Cache */
    protected $cache;

    /** @var  array */
    protected $resources;

    /**
     * ResourceRouter constructor.
     * @param Nette\Caching\IStorage $storage
     * @param array $resources
     */
    public function __construct(Nette\Caching\IStorage $storage, array $resources)
    {
        $this->cache = new Nette\Caching\Cache($storage, 'resource.router');
        $this->resources = $resources;
    }


    function match(Nette\Http\IRequest $httpRequest)
    {
        $res = parent::match($httpRequest);
        Debugger::barDump($res);
    }

    function constructUrl(Application\Request $appRequest, Nette\Http\Url $refUrl)
    {
        // TODO: Implement constructUrl() method.
    }

}