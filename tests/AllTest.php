<?php
namespace arr;

use arr;
use PHPUnit_Framework_TestCase;

class AllTest extends PHPUnit_Framework_TestCase {
	public function testAll() {
		$input = array('a' => 0, 'b' => 1, 'c' => 2, 'd' => 3);

		$this->assertEquals('0123', arr\join($input));

		$this->assertEquals('0, 1, 2, 3', arr\join($input, ', '));

		$this->assertEquals(array('b' => 1, 'd' => 3), arr\intersect($input, array(1, 3)));

		$this->assertEquals(array('a' => 0, 'b' => 1), arr\intersect\keys($input, array('a', 'b')));

		$this->assertEquals(array('a' => 0, 'd' => 3), arr\diff($input, array(1, 2)));

		$this->assertEquals(array('b' => 1, 'd' => 3), arr\diff\keys($input, array('a', 'c')));

		$this->assertEquals(array('b' => 1, 'c' => 2, 'd' => 3), arr\filter($input));

		$this->assertEquals(array('a' => 0, 'c' => 2, 'd' => 3), arr\filter($input, function ($val) { return $val !== 1; }));

		$this->assertEquals(array('a' => 0, 'b' => 1, 'c' => 2, 'd' => 3), arr\filter\keys($input));

		$this->assertEquals(array('a' => 0, 'c' => 2, 'd' => 3), arr\filter\keys($input, function ($key) { return $key !== 'b'; }));

		$this->assertEquals(array('a' => 0, 'b' => 1), arr\filter\keysAndValues($input, function ($key, $value) { return $value === 0 || $key === 'b'; }));

		$this->assertEquals(array('a' => exp(0), 'b' => exp(1), 'c' => exp(2), 'd' => exp(3)), arr\map($input, 'exp'));

		$this->assertEquals(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4), arr\map($input, function ($val) { return $val + 1; }));

		$this->assertEquals(array('A' => 0, 'B' => 1, 'C' => 2, 'D' => 3), arr\map\keys($input, 'strtoupper'));

		$this->assertEquals(array(61 => 0, 62 => 1, 63 => 2, 64 => 3), arr\map\keys($input, function ($key) { return dechex(ord($key)); }));

		$this->assertEquals(array(0 => 'a', 1 => 'b', 2 => 'c', 3 => 'd'), arr\map\keysAndValues($input, function (&$key, $value) { list($value, $key) = array($key, $value); return $value; }));

		$this->assertEquals(array(0 => '#FF8000', 1 => '#80FF00'), arr\map\func(array(array(255, 128, 0), array(128, 255, 0)), 'vsprintf', array('#%02X%02X%02X'), 1));
	}
}