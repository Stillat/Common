<?php namespace Stillat\Common\Collections;

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

}