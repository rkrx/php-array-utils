<?php
namespace arr\diff\recursive;

use arr;

/**
 * @param array $arrayA
 * @param array $arrayB
 * @return array
 */
function assoc($arrayA, $arrayB) {
	$a = arr\filter($arrayA, function ($value) { return !is_array($value); });
	$b = arr\filter($arrayB, function ($value) { return !is_array($value); });

	// Get all entries from $arrayX not showing up in $x
	$aA = array_diff_key($arrayA, $a);

	// Get all differences from non-array-valued entries
	$diff1 = array_diff_assoc($a, $b);

	// Find all differencing array-valued entries
	$diff2 = array_diff_key($aA, $arrayB);

	// Find all intersect array-valued entries
	$diffSub = array_intersect_key($aA, $arrayB);

	// Go though all sub arrays and look for differences
	$diff3 = array();
	foreach($diffSub as $key => $subA) {
		$subB = $arrayB[$key];
		$diff = assoc($subA, $subB);
		if(count($diff) > 0) {
			$diff3[$key] = $diff;
		}
	}

	// Merge all differences
	return array_merge($diff1, $diff2, $diff3);
}