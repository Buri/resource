<?php

namespace Buri\Resource\Localization;

use Latte\Runtime\FilterInfo;
use Nette\Localization\ITranslator;

class TranslationFilter
{
	/** @var ITranslator */
	protected $translator;

	/**
	 * TranslationFilter constructor.
	 * @param ITranslator $translator
	 */
	public function __construct(ITranslator $translator)
	{
		$this->translator = $translator;
	}

	public function __get($name)
	{
		$translator = $this->translator;
		return function (FilterInfo $fi, ...$args) use ($translator) {
			return $translator->translate(...$args);
		};
	}
}
