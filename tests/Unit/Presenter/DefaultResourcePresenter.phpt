<?php

namespace Buri\Resource\Unit\Presenter;

use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\ITemplate;
use Nette\DI\Container;
use Tester\Assert;

/** @var Container $container */
$container = require_once __DIR__ . '/../bootstrap.php';

class DefaultResourcePresenter extends BasePresenterTest
{
	public function testDefaultAction()
	{
		$this->presenterAction(new Request($this->getPresenterName(), 'GET', ['action' => 'default']));
		dd((string)$this->response->getSource());
	}
}

$testCase = new DefaultResourcePresenter;
$testCase->setPresenterFactory($container->getByType(IPresenterFactory::class));
$testCase->run();
