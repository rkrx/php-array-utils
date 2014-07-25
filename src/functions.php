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
 * @param array $keys
 * @return array
 */
function intersectKeys($array, $keys) {
	$aKeys = array_keys($array);
	$bKeys = array_values($keys);
	$result = array_intersect($aKeys, $bKeys);
	$cKeys = array_combine($result, $result);
	return array_intersect_key($array, $cKeys);
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
 * @param array $keys
 * @return array
 */
function diffKeys($array, $keys) {
	$aKeys = array_keys($array);
	$bKeys = array_values($keys);
	$result = array_diff($aKeys, $bKeys);
	$cKeys = array_combine($result, $result);
	return array_intersect_key($array, $cKeys);
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
 * @param null|callable $callable
 * @return array
 */
function filterKeys($array, $callable = null) {
	$keys = array_keys($array);
	$keys = filter($keys, $callable);
	return intersectKeys($array, $keys);
}

/**
 * @param array $array
 * @param callable $callable
 * @return array
 */
function filterKeysAndValues($array, $callable) {
	$result = array();
	foreach($array as $key => $value) {
		if(call_user_func($callable, $key, $value)) {
			$result[$key] = $value;
		}
	}
	return $result;
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
 * @param callable $callable
 * @return array
 */
function mapKeys($array, $callable) {
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
function mapKeysAndValues($array, $callable) {
	$result = array();
	foreach($array as $key => $value) {
		$value = call_user_func_array($callable, [&$key, $value]);
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
function mapFunc($array, $callable, array $params = array(), $paramPos = 0) {
	return map($array, function ($value) use ($callable, $params, $paramPos) {
		$p = $params;
		array_splice($p, $paramPos, 0, array($value));
		return call_user_func_array($callable, $p);
	});
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
