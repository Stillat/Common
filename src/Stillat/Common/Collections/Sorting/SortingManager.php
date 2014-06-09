<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Exceptions\InvalidArgumentException;
use Stillat\Common\Collections\Sorting\ArraySortingInterface;

class SortingManager {

	/**
	 * Maps the sorting driver names to their class names.
	 * 
	 * @var array
	 */
	protected $driverClassMap = array(
		'native' => '\Stillat\Common\Collections\Sorting\Drivers\NativeQuickSorter',
		'quick' => '\Stillat\Common\Collections\Sorting\Drivers\QuickSorter',
	);

	/**
	 * The ArraySortingInterface implementation.
	 *
	 * @var \Stillat\Common\Collections\Sorting\ArraySortingInterface
	 */
	protected $driver;

	/**
	 * Returns an instance of SortingManager
	 *
	 * @throws Stillat\Common\Exceptions\InvalidArgumentException If an invalid $sortingDriver is specified
	 * @param  string $sortingDriver
	 */
	public function __construct($sortingDriver)
	{
		if (!array_key_exists($sortingDriver, $this->driverClassMap))
		{
			throw new InvalidArgumentException("Sorting driver '{$sortingDriver}' is not supported.");
		}
		$this->driver = new $this->driverClassMap[$sortingDriver];
	}

	/**
	 * Returns the ArraySortingInterface implementation
	 * 
	 * @return \Stillat\Common\Collections\Sorting\ArraySortingInterface
	 */
	public function getDriver()
	{
		return $this->driver;
	}

	/**
	 * Sorts an array in ascending order.
	 * 
	 * @param  array  $collection
	 * @return array
	 */
	public function asc(array $collection)
	{
		return $this->driver->asc($collection);
	}

	/**
	 * Sorts an array in descending order.
	 * 
	 * @param  array  $collection
	 * @return array
	 */
	public function desc(array $collection)
	{
		return $this->driver->desc($collection);
	}
	
}