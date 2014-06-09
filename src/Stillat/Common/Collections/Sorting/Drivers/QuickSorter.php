<?php namespace Stillat\Common\Collections\Sorting\Drivers;

use Stillat\Common\Collections\Sorting\Drivers\BaseSorter;

class QuickSorter extends BaseSorter  {

	/**
	 * Sorts the given array using a quick sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function asc(array $collection)
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
			if ($value < $pivot == $this->forwardSort)
			{
				$leftCollection[$key] = $value;
			}
			else
			{
				$rightCollection[$key] = $value;
			}
		}

		return array_merge($this->asc($leftCollection),
						   array($sortPivot => $pivot),
						   $this->asc($rightCollection));
	}

	/**
	 * Inversely sorts the given array using a quick sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function desc(array $collection)
	{
		$this->changeDirection(false);
		$collection = $this->asc($collection);
		$this->changeDirection(true);
		return $collection;
	}

}