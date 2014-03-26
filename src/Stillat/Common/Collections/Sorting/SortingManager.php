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
		'bogo' => '\Stillat\Common\Collections\Sorting\Drivers\BogoSorter',
		'bubble' => '\Stillat\Common\Collections\Sorting\Drivers\BubbleSorter',
		'cocktail' => '\Stillat\Common\Collections\Sorting\Drivers\CocktailSorter',
		'gnome' => '\Stillat\Common\Collections\Sorting\Drivers\GnomeSorter',
		'heap' => '\Stillat\Common\Collections\Sorting\Drivers\HeapSorter',
		'insertion' => '\Stillat\Common\Collections\Sorting\Drivers\InsertionSorter',
		'merge' => '\Stillat\Common\Collections\Sorting\Drivers\MergeSorter',
		'native' => '\Stillat\Common\Collections\Sorting\Drivers\NativeQuickSorter',
		'quick' => '\Stillat\Common\Collections\Sorting\Drivers\QuickSorter',
		'selection' => '\Stillat\Common\Collections\Sorting\Drivers\SelectionSorter',
		'shell' => '\Stillat\Common\Collections\Sorting\Drivers\ShellSorter',
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
	 * @param string $sortingDriver
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
	 * Sorts an array in ascending order.
	 * 
	 * @param  array  $collection
	 * @return array
	 */
	public function asc(array $collection)
	{
		return $this->driver->sort($collection);
	}

	/**
	 * Sorts an array in descending order.
	 * 
	 * @param  array  $collection
	 * @return array
	 */
	public function desc(array $collection)
	{
		return $this->driver->tros($collection);
	}
	
}