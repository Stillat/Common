<?php

namespace Stillat\Common\Math;

use Collection\Collection;

final class OperationSequenceWriter
{

    protected $writingGroup = false;

    public function write(Collection $sequences)
    {
        $sequences = clone $sequences;
        $expression = '';
        if ($sequences->count() > 0) {
            $sequences = $sequences->reverse();

            $length = $sequences->count();
            for ($i = 0; $i < $length; $i++) {
                $sequence = $sequences[$i];
                if (isset($sequences[$i + 1])) {
                    $next = $sequences[$i + 1];
                } else {
                    $next = null;
                }
                $expression = $this->getNextPart($sequence[0], $sequence[1], $next) . $expression;
            }
        }

        return $expression;
    }

    protected $singleParameterFunctions = [
        'abs',
        'acos',
        'asin',
        'atan',
        'cos',
        'cosh',
        'exp',
        'sin',
        'sinh',
        'sqrt',
        'tan',
        'tanh',
        'floor',
        'log10',
        'ceiling',
        'truncate'
    ];

    protected $twoParameterFunctions = [
        'atan2',
        'log',
        'pow',
        'mod'
    ];

    protected $threeParametersFunctions = [
        'round'
    ];

    protected $arrayParameterFunctions = [
        'max',
        'min'
    ];

    private function getNextPart($historyItem, $value, $next)
    {
        $neighboringGroupOperation = ($next[0] == null);

        if (in_array($historyItem, $this->singleParameterFunctions)) {
            return $this->getFunctionSymbol($historyItem, $value);
        }

        switch ($historyItem) {
            case 'add':
                return ($neighboringGroupOperation) ? $value : ' + ' . $value;
            case 'subtract':
                return ($neighboringGroupOperation) ? $value : ' - ' . $value;
            case 'multiply':
                return ($neighboringGroupOperation) ? $value : ' * ' . $value;
            case 'divide':
                return ($neighboringGroupOperation) ? $value : ' / ' . $value;
            case 'set':
                return $value;
            case 'group':
                $this->writingGroup = true;
                return '(' . $this->write($value) . ')';
            case 'group_operation':
                return ($neighboringGroupOperation) ? '' : ' ' . $this->getOperatorSymbol($value, $next) . ' ';
            case 'factorial':
                return $value.'!';
            default:
                return '';
        }
    }

    private function getFunctionSymbol()
    {
        $params = func_get_args();
        $func = array_shift($params);
        return $func . '(' . implode(',', $params) . ')';
    }

    private function getOperatorSymbol($operator, $next)
    {
        switch ($operator) {
            case 'add':
                return '+';
            case 'subtract':
                return '-';
            case 'multiply':
                return '*';
            case 'divide':
                return '/';
        }
    }

}