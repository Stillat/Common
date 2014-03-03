<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\Sorting\BaseSorter;

class ShellSorter extends BaseSorter {

	/**
	 * Sorts the given array using a shell sort algorithm.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$count = count($collection);
		$gap = round($count / 2);

		while ($gap > 0)
		{
			for ($i = $gap; $i < $count; $i++)
			{
				$currentKey = $i;
				$currentValue = $collection[$i];

				while (($currentKey >= $gap and $collection[$currentKey - $gap] > $currentValue) == $this->forwardSort)
				{
					$collection[$currentKey] = $collection[$currentKey - $gap];
					$currentKey = $currentKey - $gap;
				}

				$collection[$currentKey] = $currentValue;
			}

			$gap = round($gap / 2.2);
		}

		return $collection;
	}

	/**
	 * Inversely sorts the given array using a shell sort algorithm.
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