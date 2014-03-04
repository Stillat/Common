<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\Sorting\BaseSorter;

class MergeSorter extends BaseSorter {

	/**
	 * Conditionally merges two arrays
	 *
	 * @param  array $left
	 * @param  array $right
	 * @return array
	 */
	protected function mergeSort(array $left, array $right)
	{
		$resultingArray = array();

		while (count($left) > 0 and count($right) > 0)
		{
			if ($left[0] > $right [0])
			{
				$resultingArray[] = $right[0];
				$right = array_slice($right, 1);
			}
			else
			{
				$resultingArray[] = $left[0];
				$left = array_slice($left, 1);
			}
		}
		while (count($left) > 0)
		{
			$resultingArray[] = $left[0];
			$left = array_slice($left, 1);
		}
		while (count($right) > 0)
		{
			$resultingArray[] = $right[0];
			$right = array_slice($right, 1);
		}

		return $resultingArray;
	}

	/**
	 * Sorts the given array using a merge sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		if (count($collection) == 1)
		{
			return $collection;
		}

		$midCount = count($collection) / 2;
		$left     = array_slice($collection, 0, $midCount);
		$right 	  = array_slice($collection, $midCount);

		$left 	  = $this->sort($left);
		$right 	  = $this->sort($right);

		return $this->mergeSort($left, $right);
	}

	/**
	 * Inversely sorts the given array using a merge sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function tros(array $collection)
	{
		return array_reverse($this->sort($collection), true);
	}

}