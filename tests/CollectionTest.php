<?php

use \Stillat\Common\Collections\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase {

	protected $testCollectionArray = array('1st' => 'Element', '2nd' => 'Element', '3rd' => 'Element');
	
	protected $testCollectionNewValue = array('new' => 'value');

	public function testAddAfter()
	{
		$collection = new Collection($this->testCollectionArray);
		$collection->addAfter('2nd', $this->testCollectionNewValue);

		$this->assertCount(4, $collection);

		$value = array_slice($collection->toArray(), 2, 1, true);
		
		$this->assertEquals(true, ($value === $this->testCollectionNewValue));
	}

	public function testAddBefore()
	{
		$collection = new Collection($this->testCollectionArray);
		$collection->addBefore('2nd', $this->testCollectionNewValue);

		$this->assertCount(4, $collection);

		$value = array_slice($collection->toArray(), 1, 1, true);

		$this->assertEquals(true, ($value === $this->testCollectionNewValue));
	}

	/**
     * @expectedException \Stillat\Common\Exceptions\InvalidArgumentException
     */
	public function testInsertAfterThrowsExceptionWhenKeyDoesntExist()
	{
		$collection = new Collection($this->testCollectionArray);
		$collection->addAfter('nonexistent', $this->testCollectionNewValue);
	}

	/**
     * @expectedException \Stillat\Common\Exceptions\InvalidArgumentException
     */
	public function testInsertBeforeThrowsExceptionWhenKeyDoesntExist()
	{
		$collection = new Collection($this->testCollectionArray);
		$collection->addBefore('nonexistent', $this->testCollectionNewValue);
	}

}