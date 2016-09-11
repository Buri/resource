<?php

namespace Buri\Resource\Unit;

use Buri\Resource\Presenter\ResourcePresenter;

abstract class ConfigurationTestData
{
	public static final function getConfiguration()
	{
		return [
			'defaults' => [
				'presenter' => ResourcePresenter::class,
			],
			'definitions' => [
				'some-resource' => [
					'presenter' => ResourcePresenter::class,
				],
				'customised' => [
					'presenter' => ResourcePresenter::class,
				],
			],
		];
	}
}
