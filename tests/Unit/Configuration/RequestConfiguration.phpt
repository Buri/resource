<?php

require_once __DIR__ . '/../bootstrap.php';

use Buri\Resource\Configuration\RequestConfiguration;
use Buri\Resource\Configuration\ResourceNotInitializedException;
use Nette\Application\Request;
use Tester\Assert;
use Buri\Resource\Unit\ConfigurationTestData;

class RequestConfigurationTest extends Tester\TestCase
{
	/** @var  RequestConfiguration */
	protected $requestConfiguration;

	public function setUp()
	{
		$this->requestConfiguration = new RequestConfiguration(ConfigurationTestData::getConfiguration());
		$this->requestConfiguration->setCurrentConfiguration('customised', 'Customised');
	}

	public function tearDown()
	{
		unset($this->requestConfiguration);
	}

	/**
	 * @dataProvider getFunctionNames
	 */
	public function testFunctionBeforeInitialization()
	{
		$arguments = func_get_args();
		$functionName = array_shift($arguments);
		Assert::exception(function () use ($functionName, $arguments) {
			call_user_func_array([$this->requestConfiguration, $functionName], $arguments);
		}, ResourceNotInitializedException::class);
	}

	/**
	 * @dataProvider getFunctionNames
	 */
	public function testFunctionAfterInitialization()
	{
		$this->requestConfiguration->handleRequest(new Request('Customised'));
		$arguments = func_get_args();
		$functionName = array_shift($arguments);
		call_user_func_array([$this->requestConfiguration, $functionName], $arguments);
	}

	public function getFunctionNames()
	{
		return [
			['isActionSecured', 'default'],
		];
	}
}

$testCase = new RequestConfigurationTest();
$testCase->run();
