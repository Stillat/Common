<?php namespace Stillat\Common\Collections\Sorting\Drivers;

use Stillat\Common\Collections\Sorting\Drivers\BaseSorter;

class BogoSorter extends BaseSorter  {

	/**
	 * Determines if a collection is already sorted
	 *
	 * @param  array $collection
	 * @return bool
	 */
	protected function isSorted(array $collection)
	{
		$count = count($collection);

		for ($i = 1; $i < $count; $i++)
		{
			if (($collection[$i - 1] > $collection[$i]) == $this->forwardSort)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Sorts the given array using a bogo sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		do
		{
			shuffle($collection);
		}
		while ($this->isSorted($collection) == false);

		return $collection;
	}

	/**
	 * Inversely sorts the given array using a bogo sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function tros(array $collection)
	{
		$this->changeDirection(false);
		$collection = $this->sort($collection);
		$this->changeDirection(true);
		return $collection;
	}

}