<?php namespace Stillat\Common\Collections;

interface CollectionInterface {

	/**
	 * Inserts a new item after an existing array element.
	 * 
	 * @param  array  $afterKey
	 * @param  array  $newItem
	 * @return void
	 */
	public function insertAfter($afterKey, array $newItem);

	/**
	 * Inserts an item before an existing array element.
	 * 
	 * @param  array  $beforeKey
	 * @param  array  $newItem
	 * @return void
	 */
	public function insertBefore($beforeKey, array $newItem);
	
}