<?php

use Stillat\Common\Collections\CollectionStack;

class StackTest extends PHPUnit_Framework_TestCase {

    public function testPushingAndPoppingWorks()
    {
        $stack = new CollectionStack;
        $stack->push(42);

        $this->assertEquals(42, $stack->pop());
        $this->assertEquals(null, $stack->pop());

        $stack->push(42);
        $stack->push(84);
        $this->assertEquals(84, $stack->pop());
        $this->assertEquals(42, $stack->pop());
    }

    public function testToppingDoesNotRemoveFirstElement()
    {
        $stack = new CollectionStack;
        $stack->push(42);
        $this->assertNotEquals(null, $stack->top());
        $this->assertEquals(42, $stack->top());
        $this->assertEquals(42, $stack->pop());
        $this->assertEquals(null, $stack->pop());
    }

}