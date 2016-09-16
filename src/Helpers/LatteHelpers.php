<?php

namespace Buri\Resource\Helpers;

class LatteHelpers
{
	public static function register($filter, $value)
	{
		if (method_exists(__CLASS__, $filter)) {
			$args = func_get_args();
			array_shift($args);
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
