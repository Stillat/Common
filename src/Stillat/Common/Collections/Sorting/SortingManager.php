<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Exceptions\InvalidArgumentException;


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
	protected $driver = null;

	/**
	 * Driver to restore when removeSortMap() is called
	 * 
	 * @var \Stillat\Common\Collections\Sorting\ArraySortingInterface
	 */
	protected $restoredDriver = null;

	/**
	 * The collection sort map instance
	 * 
	 * @var \Stillat\Common\Collections\CollectionSortMap
	 */
	protected $collectionSortMap = null;

	/**
	 * Returns an instance of SortingManager
	 *
	 * @throws \Stillat\Common\Exceptions\InvalidArgumentException If an invalid $sortingDriver is specified
	 * @param  string $sortingDriver
	 * @param  array  $additionalDrivers An array of additional sorting drivers.
	 */
	public function __construct($sortingDriver, array $additionalDrivers = array())
	{
		if (count($additionalDrivers) > 0)
		{
			array_merge($this->driverClassMap, $additionalDrivers);
		}

		$this->makeDriver($sortingDriver);
	}

	/**
	 * Attempts to make an instance of a sorting driver
	 *
	 * @param  string $driverName The driver name
	 * @return \Stillat\Common\Collections\Sorting\ArraySortingInterface
     * @throws InvalidArgumentException
	 */
	private function makeDriver($driverName)
	{
		if (is_object($driverName) && $driverName instanceof ArraySortingInterface)
		{
			$this->driver = $driverName;
			return;
		}
		else
		{
			if (is_string($driverName) && array_key_exists($driverName, $this->driverClassMap) == false)
			{
				throw new InvalidArgumentException("Sorting driver '{$driverName}' is not supported.");
			}
			else
			{
				$this->driver = new $this->driverClassMap[$driverName];
			}
		}
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
	 * Sets the collection sort map to use
	 *
	 * Settings this property will instruct the sort manager to use
	 * the sort map on all subsequent sorts.
	 * 
	 * @param CollectionSortMap $map
	 */
	public function setSortMap(CollectionSortMap $map)
	{
		$this->collectionSortMap = $map;
	}

	/**
	 * Gets the collection sort map in use
	 * 
	 * @return \Stillat\Common\Collections\CollectionSortMap
	 * @return NULL If no sort map set
	 */
	public function getSortMap()
	{
		return $this->collectionSortMap;
	}

	/**
	 * Removes the collection sort map set, if any
	 *
	 * Calling this method will instruct the sort manager not to use
	 * a sort map on all subsequent sorts.
	 * 
	 * @return void
	 */
	public function removeSortMap()
	{
		unset($this->collectionSortMap);
		$this->collectionSortMap = null;

		unset($this->driver);
		$this->driver = $this->restoredDriver;

		unset($this->restoredDriver);
		$this->restoredDriver = null;
	}

	/**
	 * Creates a driver based on the sort map, if it is available
	 *
	 * If no sort map is set, it just returns the default driver
	 *
	 * @param  int The size of the collection
	 * @return void
	 */
	private function getDriverConsideringSortMap($collectionSize)
	{
		if ($this->collectionSortMap !== null)
		{

			if ($this->restoredDriver == null)
			{
				$this->restoredDriver = $this->driver;
			}

			$this->makeDriver($this->collectionSortMap->determineDriver($collectionSize));
		}
	}

	/**
	 * Sorts an array in ascending order.
	 * 
	 * @param  array  $collection
	 * @return array
	 */
	public function asc(array $collection)
	{
		$this->getDriverConsideringSortMap(count($collection));

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
		$this->getDriverConsideringSortMap(count($collection));

		return $this->driver->desc($collection);
	}
	
}