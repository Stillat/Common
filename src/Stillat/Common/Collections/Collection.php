<?php namespace Stillat\Common\Collections;

use Illuminate\Support\Collection as IlluminateCollection;
use Stillat\Common\Collections\CollectionInterface;

class Collection extends IlluminateCollection implements CollectionInterface {

	/**
	 * Inserts a new item after an existing array element.
	 * 
	 * @param  array  $afterKey
	 * @param  array  $newItem
	 * @return void
	 */
	public function insertAfter($afterKey, array $newItem)
	{

	}

	/**
	 * Inserts an item before an existing array element.
	 * 
	 * @param  array  $beforeKey
	 * @param  array  $newItem
	 * @return void
	 */
	public function insertBefore($beforeKey, array $newItem)
	{
		
	}

}