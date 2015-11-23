<?php

namespace Stillat\Common\Contracts\Collections;

/**
 * Interface ExtendedCollectionInterface
 *
 * Extended collections are similar to collection found in the "stillat/collection"
 * and "illuminate/support" packages, except they have "extended" features, which
 * are defined below.
 *
 * @package Stillat\Common\Contracts\Collections
 */
interface ExtendedCollectionInterface
{

    /**
     * Adds a new item after an existing array element.
     *
     * @throws \Stillat\Common\Exceptions\InvalidArgumentException If an invalid $afterKey is specified
     *
     * @param  array $afterKey
     * @param  array $newItem
     *
     * @return void
     */
    public function addAfter($afterKey, array $newItem);

    /**
     * Adds an item before an existing array element.
     *
     * @throws \Stillat\Common\Exceptions\InvalidArgumentException If an invalid $beforeKey is specified
     *
     * @param  array $beforeKey
     * @param  array $newItem
     *
     * @return void
     */
    public function addBefore($beforeKey, array $newItem);

    /**
     * Sort the collection in ascending order
     *
     * @return \Stillat\Common\Contracts\Collections\ExtendedCollectionInterface
     */
    public function asc();

    /**
     * Sort the collection in descending order
     *
     * @return \Stillat\Common\Contracts\Collections\ExtendedCollectionInterface
     */
    public function desc();

}