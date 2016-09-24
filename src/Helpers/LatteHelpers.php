<?php

namespace Buri\Resource\Helpers;

class LatteHelpers
{
	public static function register()
	{
		$args = func_get_args();
		$filter = array_shift($args);
		if (method_exists(__CLASS__, $filter)) {
			return call_user_func_array([__CLASS__, $filter], $args);
		}
	}

	public static function humanize($value)
	{
		$value = preg_replace('/_id/', '', $value);
		$value = preg_replace('/[-_]/', ' ', $value);
		return mb_convert_case($value, MB_CASE_TITLE);
	}
}
