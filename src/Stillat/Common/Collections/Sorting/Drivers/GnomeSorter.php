<?php namespace Stillat\Common\Collections\Sorting\Drivers;

use Stillat\Common\Collections\Sorting\BaseSorter;

class GnomeSorter extends BaseSorter {

	/**
	 * Sorts the given array using a gnome sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{
		$count = count($collection);
		$i = 1;
		$j = 2;

		while ($i < $count)
		{
			if (($collection[$i - 1] <= $collection[$i]) == $this->forwardSort)
			{
				$i = $j;
				$j++;
			}
			else
			{
				list($collection[$i], $collection[$i - 1]) = array($collection[$i - 1], $collection[$i]);

				$i--;

				if ($i == 0)
				{
					$i = $j;
					$j++;
				}
			}
		}
		return $collection;
	}

	/**
	 * Inversely sorts the given array using a gnome sort algorithm
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