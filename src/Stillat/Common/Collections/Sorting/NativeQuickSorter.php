<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\ArraySortingInterface;

class NativeQuickSorter implements ArraySortingInterface {

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

}