<?php

namespace Buri\Resource\Unit\Presenter;

use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\Presenter;
use Tester\Assert;
use Tester\TestCase;

abstract class BasePresenterTest extends TestCase
{
	/** @var Presenter */
	protected $presenter;

	/** @var IPresenterFactory */
	protected $presenterFactory;

	/** @var TextResponse */
	protected $response;

	protected function getPresenterName()
	{
		$fqcn = get_called_class();
		$ns = explode('\\', $fqcn);
		$className = array_pop($ns);

		return substr($className, 0, strripos($className, 'Presenter'));
	}

	public function setPresenterFactory(IPresenterFactory $factory)
	{
		$this->presenterFactory = $factory;
		return $this;
	}

	protected function presenterAction(Request $request)
	{
		$this->response = $response = $this->presenter->run($request);
		Assert::true($response instanceof TextResponse);
		Assert::true($response->getSource() instanceof ITemplate);
	}

	protected function setUp()
	{
		$this->presenter = $this->presenterFactory->createPresenter($this->getPresenterName());
		$this->presenter->autoCanonicalize = false;
	}

	protected function tearDown()
	{
		unset($this->presenter, $this->response);
	}
}
