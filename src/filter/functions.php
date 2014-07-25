<?php
namespace arr\filter;

use arr;

/**
 * @param array $array
 * @param null|callable $callable
 * @return array
 */
function keys($array, $callable = null) {
	$keys = array_keys($array);
	$keys = arr\filter($keys, $callable);
	return arr\intersect\keys($array, $keys);
}

/**
 * @param array $array
 * @param callable $callable
 * @return array
 */
function keysAndValues($array, $callable) {
	$result = array();
	foreach($array as $key => $value) {
		if(call_user_func($callable, $key, $value)) {
			$result[$key] = $value;
		}
	}
	return $result;
}