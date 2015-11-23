<?php

namespace Stillat\Common\Collections\Sorting;

use Stillat\Common\Collections\Sorting\Drivers\NativeQuickSorter;
use Stillat\Common\Contracts\Collections\Sorting\Drivers\ArraySortingInterface;

/**
 * Class Sortable
 *
 * Classes that use the "Sortable" trait should define two methods:
 *
 * A "setSortableItems" method should be available that sets hte items in a collection after they have been sorted.
 * A "getSortableItems" method should return the items that should be sorted.
 *
 * @package Stillat\Common\Collections\Sorting
 */
trait Sortable
{

    /**
     * The ArraySortingInterface implementation.
     *
     * @var \Stillat\Common\Contracts\Collections\Sorting\Drivers\ArraySortingInterface
     */
    protected $sorter = null;

    /**
     * Sets the sorting driver implementation.
     *
     * @param \Stillat\Common\Contracts\Collections\Sorting\Drivers\ArraySortingInterface $driver
     *
     * @return mixed
     */
    public function setSortDriver(ArraySortingInterface $driver)
    {
        $this->sorter = $driver;

        return $this;
    }

    /**
     * Gets the ArraySortingInterface implementation.
     *
     * This defaults to the NativeQuickSorter if no driver has been explicitly set.
     *
     * @return \Stillat\Common\Contracts\Collections\Sorting\Drivers\ArraySortingInterface
     */
    private function getSorter()
    {
        if ($this->sorter == null) {
            $this->sorter = new NativeQuickSorter;
        }

        return $this->sorter;
    }

    /**
     * {@inheritdoc}
     */
    public function asc()
    {
        $this->setSortableItems($this->getSorter()->asc($this->getSortableItems()));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function desc()
    {
        $this->setSortableItems($this->getSorter()->desc($this->getSortableItems()));

        return $this;
    }

}