<?php

class SupportHelpersTest extends PHPUnit_Framework_TestCase {

	protected $testArray = array('fourty', 'two');

	public function testCanBeString()
	{
		$result = can_be_valid_string(null);
		$this->assertEquals(false, $result);

		$result = can_be_valid_string(new ObjectWithToStringTestClass());
		$this->assertEquals(true, $result);

		$result = can_be_valid_string(new ObjectWithoutToStringTestClass());
		$this->assertEquals(false, $result);

		$result = can_be_valid_string($this->testArray);
		$this->assertEquals(false, $result);

		$result = can_be_valid_string($this->testArray, true);
		$this->assertEquals(true, $result);

		$result = can_be_valid_string('This is a string');
		$this->assertEquals(true, $result);

		$result = can_be_valid_string(1);
		$this->assertEquals(true, $result);

		$result = can_be_valid_string(1.1);
		$this->assertEquals(true, $result);

		$result = can_be_valid_string(0x1A);
		$this->assertEquals(true, $result);

		$result = can_be_valid_string(false);
		$this->assertEquals(true, $result);
	}

}

class ObjectWithToStringTestClass {

	public function __toString()
	{
		return 'String';
	}

}

class ObjectWithoutToStringTestClass {

}