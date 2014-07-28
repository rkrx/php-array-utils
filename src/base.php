<?php
/**
 * @return \arr\ArrayBasics
 */
function arr() {
	static $arr = null;
	if($arr === null) {
		$arr = new \arr\ArrayBasics();
	}
	return $arr;
}