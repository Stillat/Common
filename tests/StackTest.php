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

    public function testStackCountingActuallyWorks()
    {
        $stack = new CollectionStack;
        $this->assertEquals(0, $stack->count());
        $stack->push(12);
        $this->assertEquals(1, $stack->count());
        $stack->push(12);
        $stack->push(12);
        $this->assertEquals(3, $stack->count());
        $stack->pop();
        $this->assertEquals(2, $stack->count());
    }

    public function testStackPickReturnsCorrectValues()
    {
        $stack = new CollectionStack;
        $stack->push(10);
        $this->assertEquals(10, $stack->pick());
        $stack->push(12);
        $this->assertEquals(12, $stack->pick());
        $this->assertEquals(10, $stack->pick(2));
        $stack->pop();
        $this->assertEquals(10, $stack->pick());
        $stack->pop();
        $this->assertEquals(null, $stack->pick());
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