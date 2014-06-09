<?php

include_once 'SortingManagerTest.php';

use \Stillat\Common\Collections\Sorting\SortingManager;

class SortingDriversTest extends PHPUnit_Framework_TestCase {

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

}