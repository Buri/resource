<?php

namespace Buri\Resource\Controls\ResourceGrid;

use Buri\Resource\Configuration\IRequestConfigurationAware;
use Buri\Resource\Configuration\RequestConfigurationAwareTrait;
use Nette\Application\UI\Control;
use Nette\ArgumentOutOfRangeException;
use Nette\Database\Table\IRow;

class ResourceGrid extends Control implements IRequestConfigurationAware
{
	use RequestConfigurationAwareTrait;

	public function render($resources)
	{
		$this->template->setFile($this->getTemplateForResources(reset($resources)));
		$this->template->resources = $resources;
		$this->template->render();
	}

	public function getValue($resource, $property)
	{
		if (is_array($resource)) {
			return $resource[$property];
		}

		if (is_object($resource)) {
			if ($resource instanceof IRow) {
				return $this->getActiveRowProperty($resource, $property);
			}
			return $resource->{$property};

		}

		throw new \TypeError();
	}

	protected function getTemplateForResources($resource)
	{
		if ($resource instanceof IRow) {
			return __DIR__ . '/nettedb.latte';
		}

		throw new ArgumentOutOfRangeException('Unknown resource driver');
	}

	protected function getActiveRowProperty(IRow $row, $property)
	{
		$fkIdPos = strpos($property, '_id');
		if (false !== $fkIdPos) {
			$property = substr($property, 0, $fkIdPos);
			return $row->{$property}->{$this->requestConfiguration->getPresentationForTable($property)};
		}

		return $row->{$property};
	}
}
