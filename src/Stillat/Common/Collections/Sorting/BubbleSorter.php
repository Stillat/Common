<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\ArraySortingInterface;

class BubbleSorter implements ArraySortingInterface {

	/**
	 * Sorts the given array using a bubble sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$count = count($collection) - 1;

		do
		{
			$swapped = false;

			for ($i = 0; $i < $count; $i++)
			{
				if ($collection[$i] > $collection[$i + 1])
				{
					// Store the current array item in a variable.
					$currentItem = $collection[$i];

					// Set the current array index to the item that appears next.
					$collection[$i] = $collection[$i + 1];

					// Set the next position in the array to the 'current item'.
					$collection[$i + 1] = $currentItem;
					$swapped = true;
				}
			}

		} while ($swapped);

		return $collection;
	}

}