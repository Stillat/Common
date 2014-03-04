<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\Sorting\BaseSorter;

class HeapSorter extends BaseSorter {

	protected function heapify(&$collection, &$i, &$heapSize)
	{
		$left  = $i * 2 + 1;
		$right = $i * 2 + 2;

		$largest = null;

		if ($left < $heapSize and $collection[$i] < $collection[$left])
		{
			$largest = $left;
		}
		else
		{
			$largest = $i;
		}

		if ($right < $heapSize and $collection[$largest] < $collection[$right])
		{
			$largest = $right;
		}

		if ($largest !== $i)
		{
			$temp = $collection[$i];
			$collection[$i] = $collection[$largest];
			$collection[$largest] = $temp;

			$this->heapify($collection, $largest, $heapSize);
		}
	}

	protected function buildHeap(array &$collection, &$heapSize)
	{
		$len = floor($heapSize / 2);

		for ($i = $len; $i > -1; $i--)
		{
			$this->heapify($collection, $i, $heapSize);
		}
	}

	/**
	 * Sorts the given array using a heap sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$heapSize = count($collection);
		$this->buildHeap($collection, $heapSize);

		while ($heapSize--)
		{
			$temp = $collection[$heapSize];
			$collection[$heapSize] = $collection[0];
			$collection[0] = $temp;
			$this->buildHeap($collection, $heapSize);
		}

		return $collection;
	}

	/**
	 * Inversely sorts the given array using a heap sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function tros(array $collection)
	{
		return array_reverse($this->sort($collection));
	}

}