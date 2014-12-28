<?php namespace Stillat\Common\Collections\Sorting\Drivers;

class NativeQuickSorter extends BaseSorter {

	/**
	 * Sorts the given array using a quick sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function asc(array $collection)
	{
		$temporaryCollection = $collection;
		asort($temporaryCollection);
		return $temporaryCollection;
	}

	/**
	 * Inversely sorts the given array using a quick sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function desc(array $collection)
	{
		$temporaryCollection = $collection;
		arsort($temporaryCollection);
		return $temporaryCollection;
	}
}