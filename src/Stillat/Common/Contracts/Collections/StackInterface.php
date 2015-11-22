<?php

namespace Stillat\Common\Contracts\Collections;

interface StackInterface {

    /**
     * Pushes an item onto the stack.
     *
     * @param $item
     */
    public function push($item);

    /**
     * Get and remove the last item from the stack.
     *
     * @return mixed|null
     */
    public function pop();

    /**
     * Get the first item from the stack.
     *
     * @return mixed|null
     */
    public function top();

    /**
     * The number of items remaining in the stack.
     *
     * @return int
     */
    public function count();

    /**
     * Picks an item at the given depth from the top of the stack.
     *
     * @param int $depth
     * @return mixed
     */
    public function pick($depth = 1);

}