<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\Sorting\BaseSorter;

class CocktailSorter extends BaseSorter {

	/**
	 * Sorts the given array using a cocktail sort algorithm
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection)
	{

		$count = count($collection);

		do
		{
			$swapped = false;

			for ($i = 0; $i < $count; $i++)
			{
				if (isset($collection[$i + 1]))
				{
					if (($collection[$i] > $collection[$i + 1]) == $this->forwardSort)
					{
						list($collection[$i], $collection[$i + 1]) = array($collection[$i + 1], $collection[$i]);
						$swapped = true;
					}
				}
			}

			if ($swapped == false)
			{
				break;
			}

			$swapped = false;

			for ($i = $count - 1; $i >= 0; $i--)
			{
				if (isset($collection[$i - 1]))
				{
					if (($collection[$i] < $collection[$i -1 ]) == $this->forwardSort)
					{
						list($collection[$i], $collection[$i - 1]) = array($collection[$i - 1], $collection[$i]);
						$swapped = true;
					}
				}
			}

		}
		while ($swapped == true);

		return $collection;
	}

	/**
	 * Inversely sorts the given array using a cocktail sort algorithm
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