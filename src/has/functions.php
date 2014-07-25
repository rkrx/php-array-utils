<?php
namespace arr\has;

use arr;
use ArrayAccess;

/**
 * @param array|ArrayAccess $array
 * @param string[] $path
 * @return bool
 */
function recursive($array, array $path) {
	$count = count($path);
	if (!$count) {
		return false;
	}
	for($idx = 0; $idx < $count; $idx++) {
		$part = $path[$idx];
		if(!\arr\has($array, $part)) {
			return false;
		}
		$array = $array[$part];
	}
	return true;
}