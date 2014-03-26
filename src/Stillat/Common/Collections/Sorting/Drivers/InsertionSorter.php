<?php namespace Stillat\Common\Collections\Sorting\Drivers;

use Stillat\Common\Collections\Sorting\BaseSorter;

class InsertionSorter extends BaseSorter {

	/**
	 * Sorts the given array using an insertion sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$count = count($collection);

		for ($i = 1; $i < $count; $i++)
		{
			$currentElement = $collection[$i];
			$currentIndex   = $i;

			while ($currentIndex > 0 and $collection[$currentIndex - 1] > $currentElement)
			{
				$collection[$currentIndex] = $collection[$currentIndex - 1];
				$currentIndex = $currentIndex - 1;
			}

			$collection[$currentIndex] = $currentElement;
		}

		return $collection;
	}

	/**
	 * Inversely sorts the given array using an insertion sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function tros(array $collection)
	{
		return array_reverse($this->sort($collection), true);
	}

}