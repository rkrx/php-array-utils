<?php
namespace arr;

use arr;
use PHPUnit_Framework_TestCase;

class AllTest extends PHPUnit_Framework_TestCase {
	public function testAll() {
		$input = array('a' => 0, 'b' => 1, 'c' => 2, 'd' => 3);

		$this->assertEquals('0123', arr($input)->join());

		$this->assertEquals('0, 1, 2, 3', arr($input)->join(', '));

		$this->assertEquals(array('b' => 1, 'd' => 3), arr($input)->intersect(array(1, 3))->asArray());

		$this->assertEquals(array('a' => 0, 'b' => 1), arr($input)->intersectKeys(array('a', 'b'))->asArray());

		$this->assertEquals(array('a' => 0, 'd' => 3), arr($input)->diff(array(1, 2))->asArray());

		$this->assertEquals(array('b' => 1, 'd' => 3), arr($input)->diffKeys(array('a', 'c'))->asArray());

		$this->assertEquals(array('b' => 1, 'c' => 2, 'd' => 3), arr($input)->filter()->asArray());

		$this->assertEquals(array('a' => 0, 'c' => 2, 'd' => 3), arr($input)->filter(function ($val) { return $val !== 1; })->asArray());

		$this->assertEquals(array('a' => 0, 'b' => 1, 'c' => 2, 'd' => 3), arr($input)->filterKeys()->asArray());

		$this->assertEquals(array('a' => 0, 'c' => 2, 'd' => 3), arr($input)->filterKeys(function ($key) { return $key !== 'b'; })->asArray());

		$this->assertEquals(array('a' => 0, 'b' => 1), arr($input)->filterKeysAndValues(function ($key, $value) { return $value === 0 || $key === 'b'; })->asArray());

		$this->assertEquals(array('a' => exp(0), 'b' => exp(1), 'c' => exp(2), 'd' => exp(3)), arr($input)->map('exp')->asArray());

		$this->assertEquals(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4), arr($input)->map(function ($val) { return $val + 1; })->asArray());

		$this->assertEquals(array('A' => 0, 'B' => 1, 'C' => 2, 'D' => 3), arr($input)->mapKeys('strtoupper')->asArray());

		$this->assertEquals(array(61 => 0, 62 => 1, 63 => 2, 64 => 3), arr($input)->mapKeys(function ($key) { return dechex(ord($key)); })->asArray());

		$this->assertEquals(array(0 => 'a', 1 => 'b', 2 => 'c', 3 => 'd'), arr($input)->mapKeysAndValues(function (&$key, $value) { list($value, $key) = array($key, $value); return $value; })->asArray());

		$this->assertEquals(array(0 => '#FF8000', 1 => '#80FF00'), arr(array(array(255, 128, 0), array(128, 255, 0)))->mapFunc('vsprintf', array('#%02X%02X%02X'), 1)->asArray());
	}
}