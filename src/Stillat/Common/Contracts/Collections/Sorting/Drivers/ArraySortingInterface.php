<?php

namespace Stillat\Common\Contracts\Collections\Sorting\Drivers;

interface ArraySortingInterface {
	
	/**
	 * Sorts the given array in ascending order
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function asc(array $collection);

	/**
	 * Inversely sorts the given array in descending order
	 *
	 * @param  array $collection
	 * @return array
	 */
	public function desc(array $collection);

}