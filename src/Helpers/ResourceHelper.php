<?php

namespace Buri\Resource\Helpers;

use Nette\StaticClass;

class ResourceHelper
{
	use StaticClass;

	public static function normalizeResourceName($name)
	{
		$formatted = mb_convert_case($name, MB_CASE_TITLE);
		$formatted = preg_replace('/[-_]/', '', $formatted);

		return $formatted;
	}
}
