<?php

use \Stillat\Common\Collections\Sorting\SortingManager;

class SortingManagerTest extends PHPUnit_Framework_TestCase {

	protected $supportedDrivers = ['native', 'quick'];

	const SORTING_INTERFACE = '\Stillat\Common\Collections\Sorting\ArraySortingInterface';

	public function testSettingDriverReturnsInstance()
	{
		foreach ($this->supportedDrivers as $driverName)
		{
			$manager = new SortingManager($driverName);

			$this->assertInstanceOf(self::SORTING_INTERFACE, $manager->getDriver());

			unset($manager);
		}
	}

	/**
     * @expectedException \Stillat\Common\Exceptions\InvalidArgumentException
     */
	public function testSettingInvalidDriverThrowsException()
	{
		$manager = new SortingManager('Any invalid name will work here');
	}

}