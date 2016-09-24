<?php

namespace Buri\Resource\Unit\Presenter;

use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\Presenter;
use Nette\Database\Context;
use Tester\Assert;
use Tester\DomQuery;
use Tester\TestCase;
use Tester\Environment as TesterEnvironment;

abstract class BasePresenterTest extends TestCase
{
	/** @var Presenter */
	protected $presenter;

	/** @var IPresenterFactory */
	protected $presenterFactory;

	/** @var TextResponse */
	protected $response;

	/** @var  DomQuery */
	protected $dom;

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
		$this->dom = DomQuery::fromHtml((string)$this->response->getSource());
	}

	protected function setUp()
	{
		$this->presenter = $this->presenterFactory->createPresenter($this->getPresenterName());
		$this->presenter->autoCanonicalize = false;

		// Run tests that use database synchronously
		$tempDir = $this->presenter->context->parameters['tempDir'];
		TesterEnvironment::lock('database', $tempDir);

		/** @var Context $connection */
		$connection = $this->presenter->context->getService('database.default.context');
		$connection->query(file_get_contents(__DIR__ . '/../../test-data.sql'));
	}

	protected function tearDown()
	{
		unset($this->presenter, $this->response, $this->dom);
	}
}
