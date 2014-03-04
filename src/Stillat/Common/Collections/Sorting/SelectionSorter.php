<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\Sorting\BaseSorter;

class SelectionSorter extends BaseSorter {

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
				if (($mimimumValue == null || $collection[$e] < $mimimumValue) == $this->forwardSort)
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

	/**
	 * Inversely sorts the given array using a selection sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function tros(array $collection)
	{
		return array_reverse($this->sort($collection), true);
	}

}