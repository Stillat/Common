<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Exceptions\InvalidArgumentException;

class CollectionSortMap {

	protected $sortThresholds = array();

	/**
	 * Adds a new sorting algorithm with the specified threshold
	 *
	 * @throws InvalidArgumentException if the $threshold is not an integer, or is not greater than 0
	 * @param  int    $threshold The collection count threshold
	 * @param  string $driver    The sorting driver to use at the threshold
	 */
	public function addThreshold($threshold, $driver)
	{
		if (!is_int($threshold) || $threshold <= 0)
		{
			throw new InvalidArgumentException("The threshold '{$threshold}' is invalid. The threshold must be an integer and greater than '0'.");
		}

		$this->sortThresholds[$threshold] = $driver;
	}

	/**
	 * Returns the collection map sort thresholds
	 * 
	 * @return array
	 */
	public function getThresholds()
	{
		return $this->sortThresholds;
	}

	/**
	 * Determines the driver to use based on a collection's size
	 * 
	 * @param  int    $collectionCount The size of the collection
	 * @return string The driver to use
	 */
	public function determineDriver($collectionCount)
	{

		$determinedDriver = 'native';
		
		ksort($this->sortThresholds, SORT_NUMERIC);

		foreach ($this->sortThresholds as $threshold => $driver)
		{
			if ($collectionCount <= $threshold)
			{
				$determinedDriver = $driver;
				break;
			}
		}
		
		return $determinedDriver;
	}

}