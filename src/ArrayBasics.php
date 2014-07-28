<?php
namespace arr;

use ArrayAccess;

class ArrayBasics {
	/**
	 * @param array $array
	 * @param string $concatenator
	 * @return string
	 */
	public function join($array, $concatenator = '') {
		return \join($concatenator, $array);
	}

	/**
	 * @param array $array
	 * @param array $values
	 * @return array
	 */
	public function intersect($array, $values) {
		return array_intersect($array, $values);
	}

	/**
	 * @param array $array
	 * @param array $keys
	 * @return array
	 */
	public function intersectKeys($array, $keys) {
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
	public function diff($array, $values) {
		return array_diff($array, $values);
	}

	/**
	 * @param array $array
	 * @param array $keys
	 * @return array
	 */
	public function diffKeys($array, $keys) {
		$aKeys = array_keys($array);
		$bKeys = array_values($keys);
		$result = array_diff($aKeys, $bKeys);
		$cKeys = array_combine($result, $result);
		return array_intersect_key($array, $cKeys);
	}

	/**
	 * @param array $arrayA
	 * @param array $arrayB
	 * @return array
	 */
	public function diffAssocRecursive($arrayA, $arrayB) {
		$a = $this->filter($arrayA, function ($value) { return !is_array($value); });
		$b = $this->filter($arrayB, function ($value) { return !is_array($value); });

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
			$diff = $this->diffAssocRecursive($subA, $subB);
			if(count($diff) > 0) {
				$diff3[$key] = $diff;
			}
		}

		// Merge all differences
		return array_merge($diff1, $diff2, $diff3);
	}

	/**
	 * @param array $array
	 * @param null|callable $callable
	 * @return array
	 */
	public function filter($array, $callable = null) {
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
	public function filterKeys($array, $callable = null) {
		$keys = array_keys($array);
		$keys = $this->filter($keys, $callable);
		return $this->intersectKeys($array, $keys);
	}

	/**
	 * @param array $array
	 * @param callable $callable
	 * @return array
	 */
	public function filterKeysAndValues($array, $callable) {
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
	public function map($array, $callable) {
		return array_map($callable, $array);
	}

	/**
	 * @param array $array
	 * @param callable $callable
	 * @return array
	 */
	public function mapKeys($array, $callable) {
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
	public function mapKeysAndValues($array, $callable) {
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
	public function mapFunc($array, $callable, array $params = array(), $paramPos = 0) {
		return $this->map($array, function ($value) use ($callable, $params, $paramPos) {
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
	public function has($array, $key) {
		if(!is_array($array) && !($array instanceof \ArrayAccess)) {
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
	public function get($array, $key, $default = null) {
		if($this->has($array, $key)) {
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
	public function set($array, $key, $value) {
		$array[$key] = $value;
		return $array;
	}


	/**
	 * @param array $array
	 * @param string $key
	 * @return array
	 */
	public function remove($array, $key) {
		if($this->has($array, $key)) {
			unset($array[$key]);
		}
		return $array;
	}

	/**
	 * @param array|ArrayAccess $array
	 * @param array $path
	 * @param callable $callback
	 * @return array
	 */
	public function recursiveGroupBy($array, array $path, $callback = null) {
		$result = array();
		foreach($array as $value) {
			if(!is_array($value) || !$this->recursiveHas($value, $path)) {
				continue;
			}
			$columnValue = $this->recursiveGet($value, $path);
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

	/**
	 * @param array|ArrayAccess $array
	 * @param string[] $path
	 * @return bool
	 */
	public function recursiveHas($array, array $path) {
		$count = count($path);
		if (!$count) {
			return false;
		}
		for($idx = 0; $idx < $count; $idx++) {
			$part = $path[$idx];
			if(!array_key_exists($part, $array)) {
				return false;
			}
			$array = $array[$part];
		}
		return true;
	}

	/**
	 * @param array|ArrayAccess $array
	 * @param string[] $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function recursiveGet($array, array $path, $default = null) {
		$count = count($path);
		if (!$count) {
			return $default;
		}
		for($idx = 0; $idx < $count; $idx++) {
			$part = $path[$idx];
			if(!array_key_exists($part, $array)) {
				return $default;
			}
			$array = $array[$part];
		}
		return $array;
	}

	/**
	 * @param array|ArrayAccess $array
	 * @param string[] $path
	 * @param mixed $value
	 * @return array
	 */
	public function recursiveSet($array, array $path, $value) {
		$key = array_shift($path);
		if (!array_key_exists($key, $array)) {
			$data[$key] = array();
		}
		if (count($path)) {
			$data[$key] = $this->recursiveSet($array[$key], $path, $value);
		} else {
			$data[$key] = $value;
		}
		return $data;
	}

	/**
	 * Run into each level of recursion until the last key was found, or until a key was missing at any level.
	 * If the last key was found, it gets removed by unset()
	 *
	 * @param array|ArrayAccess $array
	 * @param string[] $path
	 * @return array
	 */
	public function recursiveRemove($array, array $path) {
		while (count($path)) { // Only try this while a valid path is given
			$key = array_shift($path); // Get the current key
			if(array_key_exists($key, $array)) { // If the current key is present in the current recursion-level...
				if(count($path)) { // After involving array_shift, the path could now be empty. If not...
					if(is_array($array[$key])) { // If it is an array we need to step into the next recursion-level...
						$array[$key] = $this->recursiveRemove($array[$key], $path);
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
}