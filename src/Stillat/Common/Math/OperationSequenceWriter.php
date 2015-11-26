<?php

namespace Stillat\Common\Math;

use Collection\Collection;

final class OperationSequenceWriter
{

    public function write(Collection $sequences)
    {
        $sequences = clone $sequences;
        if ($sequences->count() > 0) {
            $initialValue = 0;
            if ($sequences[0][0] == 'set') {
                $initialValue = $sequences[0][1];
                $sequences->shift();
            }

            $expression = $initialValue;

            foreach ($sequences as $value) {
                $expression .= $this->getNextPart($value[0], $value[1]);
            }

            return $expression;
        }
    }

    private function getNextPart($operation, $value)
    {
        switch ($operation)
        {
            case 'add':
                return ' + '.$value;
            case 'subtract':
                return ' - '.$value;
            case 'multiply':
                return ' * '.$value;
            case 'divide':
                return ' / '.$value;
            default:
                return '';
        }
    }

}