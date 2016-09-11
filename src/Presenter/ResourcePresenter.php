<?php

namespace Buri\Resource\Presenter;

use Nette\Application\UI\Presenter;

class ResourcePresenter extends Presenter
{
	/** @var  array */
	protected $resourceConfiguration;

	public function formatTemplateFiles()
	{
		$files = parent::formatTemplateFiles();
		$files[] = realpath(__DIR__ . '/../Templates/' . $this->view . '.latte');

		return $files;
	}

}
