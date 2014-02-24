<?php namespace Stillat\Common\Collections;

interface ArraySortingInterface {
	
	/**
	 * Sorts the given array.
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function sort(array $collection);

}