<?php
namespace arr\map;

use arr;

/**
 * @param array $array
 * @param callable $callable
 * @return array
 */
function keys($array, $callable) {
	$result = array();
	foreach($array as $key => $value) {
		$key = call_user_func($callable, $key);
		$result[$key] = $value;
	}
	return $result;
}

/**
 * @param array $array
 * @param callable $callable
 * @return array
 */
function keysAndValues($array, $callable) {
	$result = array();
	foreach($array as $key => $value) {
		$value = call_user_func_array($callable, array(&$key, $value));
		$result[(string) $key] = $value;
	}
	return $result;
}

/**
 * @param array $array
 * @param callable $callable
 * @param array $params
 * @param int $paramPos
 * @return array
 */
function func($array, $callable, array $params = array(), $paramPos = 0) {
	return arr\map($array, function ($value) use ($callable, $params, $paramPos) {
		$p = $params;
		array_splice($p, $paramPos, 0, array($value));
		return call_user_func_array($callable, $p);
	});
}