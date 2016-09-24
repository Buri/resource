<?php

namespace Buri\Resource\Localization;

use Nette\Localization\ITranslator;

final class DefaultResourceTranslator implements ITranslator
{
	/** @var string[] */
	private $messages;

	/**
	 * DefaultResourceTranslator constructor.
	 * @param \string[] $messages
	 */
	public function __construct(array $messages)
	{
		$this->messages = $messages;
	}

	public function translate($message, $count = null)
	{
		if (isset($this->messages[$message])) {
			return $this->messages[$message];
		}
		return $message;
	}
}
