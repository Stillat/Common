<?php

namespace Stillat\Common\Math;

use Collection\Collection;

final class OperationSequenceContainer
{

    protected $operationHistory;

    protected $operationName;

    public $value;

    public function __construct($operationName, $initialValue)
    {
        $this->value = $initialValue;
        $this->operationName = $operationName;
        $this->operationHistory = new Collection;
    }

    public function &getHistory()
    {
        return $this->operationHistory;
    }

    public function getName()
    {
        return $this->operationName;
    }

}