<?php
namespace arr;

use arr;
use PHPUnit_Framework_TestCase;

class DiffRecursiveTest extends PHPUnit_Framework_TestCase {
	public function testA() {
		$a = array(
			'a' => 1,
			'b' => 2,
			'c' => 3,
			'd' => array(
				'a' => 1,
				'c' => 3,
			),
		);

		$b = array(
			'a' => 1,
			'b' => 2,
			'd' => array(
				'a' => 1,
				'b' => 1,
			),
			'e' => 4,
		);

		$diff = arr($a)->diffAssocRecursive($b)->asArray();

		$this->assertEquals(array('c' => 3, 'd' => array('c' => 3)), $diff);
	}
}