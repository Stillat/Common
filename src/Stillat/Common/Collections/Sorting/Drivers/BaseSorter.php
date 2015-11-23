<?php

namespace Stillat\Common\Collections\Sorting\Drivers;

use Stillat\Common\Collections\Sorting\ArraySortingInterface;

abstract class BaseSorter implements ArraySortingInterface
{

    protected $forwardSort = true;

    protected function changeDirection($sortDirection)
    {
        $this->forwardSort = $sortDirection;
    }

    abstract function asc(array $collection);

    abstract function desc(array $collection);

}