<?php

namespace Stillat\Common\Math;

use Collection\Collection;

final class OperationSequenceWriter
{

    public function write(Collection $sequences)
    {
        $sequences = clone $sequences;
        $expression = '';
        if ($sequences->count() > 0) {
            $sequences = $sequences->reverse();
            foreach ($sequences as $sequence) {
                $expression = $this->getNextPart($sequence[0], $sequence[1]).$expression;
            }
        }

        return $expression;
    }

    private function getNextPart($historyItem, $value)
    {
        if (!is_array($value)) {
            switch ($historyItem) {
                case 'add':
                    return ' + '.$value;
                case 'subtract':
                    return ' - '.$value;
                case 'multiply':
                    return ' * '.$value;
                case 'divide':
                    return ' / '.$value;
                case 'set':
                    return $value;
            }
        }
    }

}