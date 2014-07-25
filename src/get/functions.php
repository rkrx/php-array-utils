<?php
namespace arr\get;

use arr;
use ArrayAccess;

/**
 * @param array|ArrayAccess $array
 * @param string[] $path
 * @param mixed $default
 * @return mixed
 */
function recursive($array, array $path, $default = null) {
	$count = count($path);
	if (!$count) {
		return $default;
	}
	for($idx = 0; $idx < $count; $idx++) {
		$part = $path[$idx];
		if(!arr\has($array, $part)) {
			return $default;
		}
		$array = $array[$part];
	}
	return $array;
}