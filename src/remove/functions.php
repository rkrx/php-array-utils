<?php
namespace arr\remove;

use arr;
use ArrayAccess;

/**
 * Run into each level of recursion until the last key was found, or until a key was missing at any level.
 * If the last key was found, it gets removed by unset()
 *
 * @param array|ArrayAccess $array
 * @param string[] $path
 * @return array
 */
function recursive($array, array $path) {
	while (count($path)) { // Only try this while a valid path is given
		$key = array_shift($path); // Get the current key
		if(array_key_exists($key, $array)) { // If the current key is present in the current recursion-level...
			if(count($path)) { // After involving array_shift, the path could now be empty. If not...
				if(is_array($array[$key])) { // If it is an array we need to step into the next recursion-level...
					$array[$key] = remove($array[$key], $path);
				}
				// If it is not an array, the sub-path can't be reached - stop.
			} else { // We finally arrived at the targeted node. Remove...
				unset($array[$key]);
			}
			break;
		} else { // If not, the path is not fully present in the array - stop.
			break;
		}
	}
	return $array;
}