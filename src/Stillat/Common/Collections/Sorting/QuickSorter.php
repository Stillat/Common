<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\ArraySortingInterface;

class QuickSorter implements ArraySortingInterface {

	/**
	 * Sorts the given array using a quick sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$count = count($collection);

		if ($count < 2)
		{
			return $collection;
		}

		$leftCollection  = array();
		$rightCollection = array();

		reset($collection);

		$sortPivot = key($collection);
		$pivot     = array_shift($collection);

		foreach ($collection as $key => $value)
		{
			if ($value < $pivot)
			{
				$leftCollection[$key] = $value;
			}
			else
			{
				$rightCollection[$key] = $value;
			}
		}

		return array_merge($this->sort($leftCollection),
						   array($sortPivot => $pivot),
						   $this->sort($rightCollection));
	}

}