<?php
namespace arr\set;

use arr;
use ArrayAccess;

/**
 * @param array|ArrayAccess $array
 * @param string[] $path
 * @param mixed $value
 * @return array
 */
function recursive($array, array $path, $value) {
	$key = array_shift($path);
	if (!array_key_exists($key, $array)) {
		$data[$key] = array();
	}
	if (count($path)) {
		$data[$key] = set($array[$key], $path, $value);
	} else {
		$data[$key] = $value;
	}
	return $data;
}