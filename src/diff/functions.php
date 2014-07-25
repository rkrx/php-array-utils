<?php
namespace arr\diff;

/**
 * @param array $array
 * @param array $keys
 * @return array
 */
function keys($array, $keys) {
	$aKeys = array_keys($array);
	$bKeys = array_values($keys);
	$result = array_diff($aKeys, $bKeys);
	$cKeys = array_combine($result, $result);
	return array_intersect_key($array, $cKeys);
}