<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\Sorting\ArraySortingInterface;

class SortingManager {

	/**
	 * The ArraySortingInterface implementation.
	 *
	 * @var \Stillat\Common\Collections\Sorting\ArraySortingInterface
	 */
	protected $driver;

	/**
	 * Returns an instance of SortingManager
	 * 
	 * @param ArraySortingInterface $sortingDriver
	 */
	public function __construct(ArraySortingInterface $sortingDriver)
	{
		$this->driver = $sortingDriver;
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