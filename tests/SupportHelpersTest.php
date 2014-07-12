<?php

class SupportHelpersTest extends PHPUnit_Framework_TestCase {

	protected $testArray = array('fourty', 'two');

	protected $testAssociativeArray = array('keyOne' => 'valueOne', 'keyTwo' => 'valueTwo');

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

	public function testIsNullThenLiteral()
	{
		$thisIsNull = null;

		$this->assertEquals('test', if_null_then($thisIsNull, 'test'));
	}

	public function testIsNullThenObject()
	{
		$thisIsNotNull = new stdClass;

		$this->assertEquals($thisIsNotNull, if_null_then($thisIsNotNull, 'test'));
	}

	public function testIsNullThenClosure()
	{
		$this->assertEquals('test', if_null_then(null, function(){
			return 'test';
		}));
	}

	public function testArrayKeyExistsThenOrThen()
	{
		$this->assertEquals(true, array_key_exists_then_or($this->testAssociativeArray, 'keyOne'));
		$this->assertEquals(true, array_key_exists_then_or($this->testAssociativeArray, 'keyTwo'));
	}

	public function testArrayKeyExistsThenOrOr()
	{
		$this->assertEquals(false, array_key_exists_then_or($this->testAssociativeArray, 'keyThree'));
		$this->assertEquals(false, array_key_exists_then_or($this->testAssociativeArray, 'keyFour'));
	}

	public function testArrayKeyExistsThenOrThenCustomOrThen()
	{
		$this->assertEquals('test', array_key_exists_then_or($this->testAssociativeArray, 'keyOne', 'test', 'test2'));
		$this->assertEquals('test', array_key_exists_then_or($this->testAssociativeArray, 'keyTwo', 'test', 'test2'));


		$this->assertEquals('test2', array_key_exists_then_or($this->testAssociativeArray, 'keyThree', 'test', 'test2'));
		$this->assertEquals('test2', array_key_exists_then_or($this->testAssociativeArray, 'keyFour', 'test', 'test2'));
	}

	public function testArrayKeyExistsThenOrThenClosure()
	{
		$this->assertEquals('closure', array_key_exists_then_or($this->testAssociativeArray, 'keyOne', function()
		{
			return 'closure';
		}));
	}

	public function testArrayKeyExistsThenOrOrClosure()
	{
		$this->assertEquals('closure', array_key_exists_then_or($this->testAssociativeArray, 'keyThree', true, function()
		{
			return 'closure';
		}));
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