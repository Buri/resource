<?php

namespace Buri\Resource\Helpers;

use Nette\Utils\ObjectMixin;

class ResourceHelper
{
	/**
	 * @throws \LogicException
	 */
	final public function __construct()
	{
		throw new \LogicException('Class ' . get_class($this) . ' is static and cannot be instantiated.');
	}

	/**
	 * Call to undefined static method.
	 * @throws MemberAccessException
	 */
	public static function __callStatic($name, $args)
	{
		ObjectMixin::strictStaticCall(get_called_class(), $name);
	}

	public static function normalizeResourceName($name)
	{
		$formatted = mb_convert_case($name, MB_CASE_TITLE);
		$formatted = preg_replace('/[-_]/', '', $formatted);

		return $formatted;
	}
}
