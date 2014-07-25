<?php
namespace arr;
use ArrayAccess;

/**
 * @param array $array
 * @param string $concatenator
 * @return string
 */
function join($array, $concatenator = '') {
	return \join($concatenator, $array);
}

/**
 * @param array $array
 * @param array $values
 * @return array
 */
function intersect($array, $values) {
	return array_intersect($array, $values);
}

/**
 * @param array $array
 * @param array $values
 * @return array
 */
function diff($array, $values) {
	return array_diff($array, $values);
}

/**
 * @param array $array
 * @param null|callable $callable
 * @return array
 */
function filter($array, $callable = null) {
	if($callable === null) {
		return array_filter($array);
	}
	return array_filter($array, $callable);
}

/**
 * @param array $array
 * @param callable $callable
 * @return array
 */
function map($array, $callable) {
	return array_map($callable, $array);
}

/**
 * @param array $array
 * @param string $key
 * @return array
 */
function has($array, $key) {
	if(!is_array($array) && !($array instanceof ArrayAccess)) {
		return false;
	}
	return array_key_exists($key, $array);
}

/**
 * @param array $array
 * @param string $key
 * @param mixed $default
 * @return array
 */
function get($array, $key, $default = null) {
	if(has($array, $key)) {
		return $array[$key];
	}
	return $default;
}

/**
 * @param array $array
 * @param string $key
 * @param $value
 * @return array
 */
function set($array, $key, $value) {
	$array[$key] = $value;
	return $array;
}


/**
 * @param array $array
 * @param string $key
 * @return array
 */
function remove($array, $key) {
	if(has($array, $key)) {
		unset($array[$key]);
	}
	return $array;
}
