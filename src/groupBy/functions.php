<?php
namespace arr\groupBy;

use arr;
use ArrayAccess;

/**
 * @param array|ArrayAccess $array
 * @param array $path
 * @param callable $callback
 * @return array
 */
function recursive($array, array $path, $callback = null) {
	$result = array();
	foreach($array as $value) {
		if(!is_array($value) || !arr\has\recursive($value, $path)) {
			continue;
		}
		$columnValue = arr\has\recursive($value, $path);
		if(!array_key_exists($columnValue, $result)) {
			$result[$columnValue] = array();
		}
		$result[$columnValue][] = $value;
	}
	if($callback !== null) {
		return array_map($callback, $result);
	}
	return array_map('array_shift', $result);
}