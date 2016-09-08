<?php

namespace Buri\Resource\Routing;

use Nette;
use Nette\Application\IRouter;
use Nette\Application\Request;

class ResourceRouter implements IRouter
{
    /** @var Nette\Caching\Cache */
    protected $cache;

    function match(Nette\Http\IRequest $httpRequest)
    {
        // TODO: Implement match() method.
    }

    function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
    {
        // TODO: Implement constructUrl() method.
    }

}