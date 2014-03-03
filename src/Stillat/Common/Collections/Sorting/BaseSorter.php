<?php namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\ArraySortingInterface;

abstract class BaseSorter implements ArraySortingInterface {

	protected $forwardSort = true;

	protected function changeDirection($sortDirection)
	{
		$this->forwardSort = $sortDirection;
	}

	abstract function sort(array $collection);

	abstract function tros(array $collection);
	
}