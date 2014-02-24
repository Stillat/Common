<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\ArraySortingInterface;

class ShellSorter implements ArraySortingInterface {

	/**
	 * Sorts the given array using a shell sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$count = count($collection);
		$gap = round($count / 2);

		while ($gap > 0)
		{
			for ($i = $gap; $i < $count; $i++)
			{
				$currentKey = $i;
				$currentValue = $collection[$i];

				while ($currentKey >= $gap and $collection[$currentKey - $gap] > $currentValue)
				{
					$collection[$currentKey] = $collection[$currentKey - $gap];
					$currentKey = $currentKey - $gap;
				}

				$collection[$currentKey] = $currentValue;
			}

			$gap = round($gap / 2.2);
		}

		return $collection;
	}

}