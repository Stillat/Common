<?php

use Stillat\Common\Collections\Sorting\CollectionSortMap;

class CollectionSortMapThresholdTest extends PHPUnit_Framework_TestCase {

	/**
     * @expectedException \Stillat\Common\Exceptions\Argument\InvalidArgumentException
     */
	public function testAddThresholdThrowsExcpetionOnInvalidInput()
	{
		$map = new CollectionSortMap;
		$map->addThreshold(0, '');
		$map->addThreshold(-1, '');
		$map->addThreshold(true, '');
	}

	public function testAddingThresholdAndGetThresholds()
	{
		$map = new CollectionSortMap;
		$map->addThreshold(10, 'first');
		$map->addThreshold(15, 'second');
		$map->addThreshold(100, 'third');
		$map->addThreshold(2, 'curve');

		$this->assertCount(4, $map->getThresholds());
	}

	public function testDetermineDriverReturnsCorrectDriver()
	{
		$map = new CollectionSortMap;
		$map->addThreshold(10, 'first');
		$map->addThreshold(15, 'second');
		$map->addThreshold(100, 'third');
		$map->addThreshold(2, 'curve');

		$this->assertEquals('first', $map->determineDriver(3));
		$this->assertEquals('first', $map->determineDriver(10));
		$this->assertEquals('second', $map->determineDriver(12));
		$this->assertEquals('second', $map->determineDriver(11));
		$this->assertEquals('second', $map->determineDriver(15));
		$this->assertEquals('third', $map->determineDriver(50));
		$this->assertEquals('curve', $map->determineDriver(2));
	}

}