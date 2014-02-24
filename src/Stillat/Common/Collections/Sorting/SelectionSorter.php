<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\ArraySortingInterface;

class SelectionSorter implements ArraySortingInterface {

	/**
	 * Sorts the given array using a selection sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$count = count($collection);

		for ($i = 0; $i < $count; $i++)
		{
			$mimimumValue = null;
			$mimimumKey   = null;

			for ($e = $i; $e < $count; $e++)
			{
				if ($mimimumValue == null || $collection[$e] < $mimimumValue)
				{
					$mimimumKey   = $e;
					$mimimumValue = $collection[$e];
				}
			}

			$collection[$mimimumKey] = $collection[$i];
			$collection[$i] = $mimimumValue;
		}

		return $collection;
	}

}