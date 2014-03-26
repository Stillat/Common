<?php namespace Stillat\Common\Collections;

use InvalidArgumentException;
use Illuminate\Support\Collection as IlluminateCollection;
use Stillat\Common\Collections\CollectionInterface;

class Collection extends IlluminateCollection implements CollectionInterface {

	/**
	 * Inserts a new item after an existing array element.
	 * 
	 * @param  string  $afterKey
	 * @param  array   $newItem
	 * @return void
	 */
	public function insertAfter($afterKey, array $newItem)
	{
		if (!$this->has($afterKey))
		{
			throw new InvalidArgumentException("An array element with key '{$afterKey}' does not exist.");
		}

		// Get the position of the menu item we are searching for.
		$searchValue = array_search($afterKey, array_keys($this->items));
		$searchValue++;

		if ($searchValue == $this->count())
		{
			// If someone is trying to add something after the last element, lets
			// just merge the arrays and be done with it quickly.

			$this->items = $this->items + $newItem;
			return;
		}

		$this->items = array_slice($this->items, 0, $searchValue, true) +
					   $newItem +
					   array_slice($this->items, $searchValue, null, true);
		
	}

	/**
	 * Inserts an item before an existing array element.
	 * 
	 * @param  string  $beforeKey
	 * @param  array   $newItem
	 * @return void
	 */
	public function insertBefore($beforeKey, array $newItem)
	{
		if (!$this->has($beforeKey))
		{
			throw new InvalidArgumentException("An array element with key '{$beforeKey}' does not exist.");
		}

		// Get the position of the menu item we are searching for.
		$searchValue = array_search($beforeKey, array_keys($this->items));
		$searchValue++;

		if ($searchValue == 1)
		{
			// $searchValue will be 1 when someone is attempting to add a new item to the beginning
			// of an array.

			$this->items = $newItem + $this->items;
			return;
		}

		$this->items = array_slice($this->items, 0, $searchValue - 1, true) +
					   $newItem +
					   array_slice($this->items, $searchValue - 1, null, true);
	}

}