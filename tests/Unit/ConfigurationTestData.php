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
					'actions' => [
						'default' => [
							'secure' => true,
							'paginate' => true,
							'pageSize' => 20,
							'sortable' => true,
							'sort' => [
								'id' => 'desc',
							],
						],
					],
				],
				'customised' => [
					'presenter' => ResourcePresenter::class,
					'actions' => [
						'default' => [
							'secure' => true,
							'paginate' => true,
							'pageSize' => 20,
							'sortable' => true,
							'sort' => [
								'id' => 'desc',
							],
						],
					],
				],
			],
		];
	}
}
