<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\Sorting\BaseSorter;

class NativeQuickSorter extends BaseSorter {

	/**
	 * Sorts the given array using a quick sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$temporaryCollection = $collection;
		sort($temporaryCollection);
		return $temporaryCollection;
	}

	/**
	 * Inversely sorts the given array using a quick sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function tros(array $collection)
	{
		$temporaryCollection = $collection;
		rsort($temporaryCollection);
		return $temporaryCollection;
	}
}