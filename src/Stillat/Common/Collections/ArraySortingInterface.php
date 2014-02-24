<?php namespace Stillat\Common\Collections;

interface ArraySortingInterface {
	
	/**
	 * Sorts the given array.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection);

	/**
	 * Inversely sorts the given array.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function tros(array $collection);

}