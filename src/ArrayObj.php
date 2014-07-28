<?php
namespace arr;

use ArrayAccess;

class ArrayObj {
	/**
	 * @var array
	 */
	private $array;

	/**
	 * @param array $array
	 */
	public function __construct(array $array) {
		$this->array = $array;
	}

	/**
	 * @param string $concatenator
	 * @return string
	 */
	public function join($concatenator = '') {
		return join($concatenator, $this->array);
	}

	/**
	 * @param array $values
	 * @return ArrayObj
	 */
	public function intersect($values) {
		return arr(array_intersect($this->array, $values));
	}

	/**
	 * @param array $keys
	 * @return ArrayObj
	 */
	public function intersectKeys($keys) {
		$aKeys = array_keys($this->array);
		$bKeys = array_values($keys);
		$result = array_intersect($aKeys, $bKeys);
		$cKeys = array_combine($result, $result);
		$res = array_intersect_key($this->array, $cKeys);
		return arr($res);
	}

	/**
	 * @param array $values
	 * @return ArrayObj
	 */
	public function diff($values) {
		return arr(array_diff($this->array, $values));
	}

	/**
	 * @param array $keys
	 * @return ArrayObj
	 */
	public function diffKeys($keys) {
		$aKeys = array_keys($this->array);
		$bKeys = array_values($keys);
		$result = array_diff($aKeys, $bKeys);
		$cKeys = array_combine($result, $result);
		return arr(array_intersect_key($this->array, $cKeys));
	}

	/**
	 * @param array $arrayB
	 * @return ArrayObj
	 */
	public function diffAssocRecursive($arrayB) {
		$arrayA = $this->array;
		$a = arr($arrayA)->filter(function ($value) { return !is_array($value); })->asArray();
		$b = arr($arrayB)->filter(function ($value) { return !is_array($value); })->asArray();

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
			$diff = arr($subA)->diffAssocRecursive($subB)->asArray();
			if(count($diff) > 0) {
				$diff3[$key] = $diff;
			}
		}

		// Merge all differences
		return arr(array_merge($diff1, $diff2, $diff3));
	}

	/**
	 * @param null|callable $callable
	 * @return ArrayObj
	 */
	public function filter($callable = null) {
		$array = $this->array;
		if($callable === null) {
			return arr(array_filter($array));
		}
		return arr(array_filter($array, $callable));
	}

	/**
	 * @param null|callable $callable
	 * @return ArrayObj
	 */
	public function filterKeys($callable = null) {
		$array = $this->array;
		$keys = array_keys($array);
		if($callable !== null) {
			$keys = array_filter($keys, $callable);
		} else {
			$keys = array_filter($keys);
		}
		return arr($array)->intersectKeys($keys);
	}

	/**
	 * @param callable $callable
	 * @return ArrayObj
	 */
	public function filterKeysAndValues($callable) {
		$array = $this->array;
		$result = array();
		foreach($array as $key => $value) {
			if(call_user_func($callable, $key, $value)) {
				$result[$key] = $value;
			}
		}
		return arr($result);
	}

	/**
	 * @param callable $callable
	 * @return ArrayObj
	 */
	public function map($callable) {
		$array = $this->array;
		return arr(array_map($callable, $array));
	}

	/**
	 * @param callable $callable
	 * @return ArrayObj
	 */
	public function mapKeys($callable) {
		$array = $this->array;
		$result = array();
		foreach($array as $key => $value) {
			$key = call_user_func($callable, $key);
			$result[$key] = $value;
		}
		return arr($result);
	}

	/**
	 * @param callable $callable
	 * @return ArrayObj
	 */
	public function mapKeysAndValues($callable) {
		$array = $this->array;
		$result = array();
		foreach($array as $key => $value) {
			$value = call_user_func_array($callable, array(&$key, $value));
			$result[(string) $key] = $value;
		}
		return arr($result);
	}

	/**
	 * @param callable $callable
	 * @param array $params
	 * @param int $paramPos
	 * @return ArrayObj
	 */
	public function mapFunc($callable, array $params = array(), $paramPos = 0) {
		$array = $this->array;
		return arr($array)->map(function ($value) use ($callable, $params, $paramPos) {
			$p = $params;
			array_splice($p, $paramPos, 0, array($value));
			return call_user_func_array($callable, $p);
		});
	}

	/**
	 * @param string $key
	 * @return ArrayObj
	 */
	public function has($key) {
		$array = $this->array;
		if(!is_array($array) && !($array instanceof \ArrayAccess)) {
			return false;
		}
		return array_key_exists($key, $array);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return ArrayObj
	 */
	public function get($key, $default = null) {
		$array = $this->array;
		if(arr($array)->has($key)) {
			return $array[$key];
		}
		return $default;
	}

	/**
	 * @param string $key
	 * @param $value
	 * @return ArrayObj
	 */
	public function set($key, $value) {
		$array[$key] = $value;
		return $array;
	}


	/**
	 * @param string $key
	 * @return ArrayObj
	 */
	public function remove($key) {
		$array = $this->array;
		if(arr($array)->has($key)) {
			unset($array[$key]);
		}
		return $array;
	}

	/**
	 * @param array $path
	 * @param callable $callback
	 * @return ArrayObj
	 */
	public function recursiveGroupBy(array $path, $callback = null) {
		$array = $this->array;
		$result = array();
		foreach($array as $value) {
			if(!is_array($value) || !$this->_recursiveHas($value, $path)) {
				continue;
			}
			$columnValue = (string) $this->_recursiveGet($value, $path, null);
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
	 * @param string[] $path
	 * @return bool
	 */
	public function recursiveHas(array $path) {
		return $this->_recursiveHas($this->array, $path);
	}

	/**
	 * @param string[] $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function recursiveGet(array $path, $default = null) {
		return $this->_recursiveGet($this->array, $path, $default);
	}

	/**
	 * @param string[] $path
	 * @param mixed $value
	 * @return ArrayObj
	 */
	public function recursiveSet(array $path, $value) {
		$this->_recursiveSet($this->array, $path, $value);
		return $this;
	}

	/**
	 * Run into each level of recursion until the last key was found, or until a key was missing at any level.
	 * If the last key was found, it gets removed by unset()
	 *
	 * @param string[] $path
	 * @return ArrayObj
	 */
	public function recursiveRemove(array $path) {
		$this->_recursiveRemove($this->array, $path);
		return $this;
	}

	/**
	 * @return array
	 */
	public function asArray() {
		return $this->array;
	}

	/**
	 * @param array $array
	 * @param array $path
	 * @return bool
	 */
	private function _recursiveHas($array, $path) {
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
	 * @param array $array
	 * @param array $path
	 * @param mixed $default
	 * @return array
	 */
	private function _recursiveGet($array, $path, $default) {
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
	 * @param array $array
	 * @param array $path
	 * @param mixed $value
	 * @return mixed
	 */
	private function _recursiveSet($array, $path, $value) {
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
	 * @param array $array
	 * @param array $path
	 * @return mixed
	 */
	private function _recursiveRemove($array, array $path) {
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