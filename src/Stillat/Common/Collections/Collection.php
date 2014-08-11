<?php namespace Stillat\Common\Collections;

use Stillat\Common\Support\Facades\Sort;
use Stillat\Common\Collections\CollectionInterface;
use Illuminate\Support\Collection as IlluminateCollection;
use Stillat\Common\Exceptions\InvalidArgumentException as InvalidArgumentException;

class Collection extends IlluminateCollection implements CollectionInterface {

	/**
	 * {@inheritdoc}
	 */
	public function asc()
	{
		$this->items = Sort::asc($this->items);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function desc()
	{
		$this->items = Sort::desc($this->items);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addAfter($afterKey, array $newItem)
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
	 * {@inheritdoc}
	 */
	public function addBefore($beforeKey, array $newItem)
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