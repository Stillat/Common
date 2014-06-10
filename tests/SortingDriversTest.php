<?php

use \Stillat\Common\Collections\Sorting\CollectionSortMap;
use \Stillat\Common\Collections\Sorting\SortingManager;

class SortingDriversTest extends PHPUnit_Framework_TestCase {

	const SORT_MAP_NAME = '\Stillat\Common\Collections\Sorting\CollectionSortMap';

	const DRIVER_QUICK = '\Stillat\Common\Collections\Sorting\Drivers\QuickSorter';

	const DRIVER_NATIVE = '\Stillat\Common\Collections\Sorting\Drivers\NativeQuickSorter';

	protected $initialArray = ['3rd' => 'Jane', '1st' => 'Jack', '2nd' => 'Jim', '5th' => 'Alice', '4th' => 'Bob'];

	protected $ascendingArray = ['1st' => 'Jack', '2nd' => 'Jim', '3rd' => 'Jane', '4th' => 'Bob', '5th' => 'Alice'];

	protected $descendingArray = ['5th' => 'Alice', '4th' => 'Bob', '3rd' => 'Jane', '2nd' => 'Jim', '1st' => 'Jack'];
	
	public function testNativeSortingDriver()
	{
		$manager = new SortingManager('native');

		$this->assertEquals($this->ascendingArray,  $manager->asc($this->initialArray));
		$this->assertEquals($this->descendingArray, $manager->desc($this->initialArray));

		unset($manager);
	}

	public function testQuickSortingDriver()
	{
		$manager = new SortingManager('quick');

		$this->assertEquals($this->ascendingArray,  $manager->asc($this->initialArray));
		$this->assertEquals($this->descendingArray, $manager->desc($this->initialArray));

		unset($manager);
	}


	public function testManagerSetSortMapsWork()
	{
		$manager = new SortingManager('native');

		$map = new CollectionSortMap;
		$map->addThreshold(5, 'quick');

		$manager->setSortMap($map);
		$this->assertInstanceOf(self::SORT_MAP_NAME, $manager->getSortMap());

		$manager->removeSortMap();
		$this->assertEquals(null, $manager->getSortMap());
	}

	/**
     * @expectedException \Stillat\Common\Exceptions\InvalidArgumentException
     */
	public function testManagerDoesntAcceptInvalidSortMapDrivers()
	{
		$manager = new SortingManager('native');

		$map = new CollectionSortMap;
		$map->addThreshold(5, 'invalid');

		$manager->setSortMap($map);

		$this->assertEquals($this->ascendingArray,  $manager->asc($this->initialArray));
	}

	public function testManagerSortsCorrectlyWithSortMapDrivers()
	{
		$manager = new SortingManager('native');

		$map = new CollectionSortMap;
		$map->addThreshold(3, 'quick');

		$manager->setSortMap($map);

		$this->assertEquals($this->ascendingArray,  $manager->asc($this->initialArray));
	}

	public function testManagerIsApplyingCorrectSortMapDrivers()
	{
		$manager = new SortingManager('native');

		$map = new CollectionSortMap;
		$map->addThreshold(5, 'native');
		$map->addThreshold(10, 'quick');

		$manager->setSortMap($map);
		$manager->asc($this->initialArray);

		$this->assertInstanceOf(self::DRIVER_NATIVE, $manager->getDriver());

		$manager->asc($this->initialArray + ['6th' => 'test']);
		$this->assertInstanceOf(self::DRIVER_QUICK, $manager->getDriver());

		$manager->removeSortMap();
		
		$this->assertInstanceOf(self::DRIVER_NATIVE, $manager->getDriver());


	}

}