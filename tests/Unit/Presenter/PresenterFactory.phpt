<?php

require_once __DIR__ . '/../bootstrap.php';

use Buri\Resource\Configuration\RequestConfiguration;
use Buri\Resource\Presenter\PresenterFactory;
use Buri\Resource\Presenter\ResourcePresenter;
use Nette\Application\InvalidPresenterException;
use Tester\Assert;
use Buri\Resource\Unit\ConfigurationTestData;

class PresenterFactoryTest extends Tester\TestCase
{
	/** @var  PresenterFactory */
	protected $presenterFactory;

	public function setUp()
	{
		$this->presenterFactory = new PresenterFactory();
		$this->presenterFactory->setRequestConfiguration(new RequestConfiguration(ConfigurationTestData::getConfiguration()));
	}

	public function tearDown()
	{
		$this->presenterFactory = null;
	}

	/**
	 * @param $expected
	 * @param $test
	 * @dataProvider getPresenterClassArgs
	 */
	public function testGetPresenterClassKnown($expected, $test)
	{
		Assert::same($expected, $this->presenterFactory->getPresenterClass($test));
	}

	public function testGetPresenterClassUnknown()
	{
		$presenterName = 'SomeNonexistentPresenter';
		Assert::throws(function () use ($presenterName) {
			$this->presenterFactory->getPresenterClass($presenterName);
		}, InvalidPresenterException::class);
	}

	public function getPresenterClassArgs()
	{
		return [
			[ResourcePresenter::class, 'SomeResource'],
			[ResourcePresenter::class, 'Customised'],
		];
	}
}

$testCase = new PresenterFactoryTest;
$testCase->run();
