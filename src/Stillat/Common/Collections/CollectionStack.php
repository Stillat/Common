<?php namespace Stillat\Common\Collections;

class CollectionStack implements StackInterface {

    /**
     * The collection instance.
     *
     * @var \Stillat\Common\Collections\Collection
     */
    protected $collection = null;

    /**
     * Returns a new instance of CollectionStack
     *
     * @return \Stillat\Common\Collections\CollectionStack
     */
    public function __construct()
    {
        $this->collection = new Collection;
    }

    /**
     * {@inheritdoc}
     */
    public function push($item)
    {
        $this->collection->push($item);
    }

    /**
     * {@inheritdoc}
     */
    public function pop()
    {
        return $this->collection->pop();
    }

    /**
     * {@inheritdoc}
     */
    public function top()
    {
        return $this->collection->first();
    }

}