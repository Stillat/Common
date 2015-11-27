<?php

namespace Stillat\Common\Traits;

use Stillat\Common\Exceptions\Argument\MissingArgumentException;
use Stillat\Common\Exceptions\Arithmetic\DivideByZeroException;
use Stillat\Common\Exceptions\Arithmetic\NotFiniteNumberException;

/**
 * Class Expectations
 *
 * The Expectations trait can be used to help simplify exception throwing in your
 * code base. Even though Expectations are available, their use is not a hard
 * requirement when using the Sillat exceptions hierarchy.
 *
 * @package Stillat\Common\Traits
 */
trait Expectations
{

    /**
     * Expect the values in the $values array to not be null when the $checkValue
     * is not null.
     *
     * @throws \Stillat\Common\Exceptions\Argument\MissingArgumentException
     *
     * @param        $checkValue
     * @param array  $values
     * @param string $message
     *
     * @throws \Stillat\Common\Exceptions\Argument\MissingArgumentException
     */
    protected function expectValuesWhenNotNull($checkValue, array $values, $message = '')
    {
        if ($checkValue !== null) {
            foreach ($values as $value) {
                if ($value === null) {
                    throw new MissingArgumentException($message);
                }
            }
        }
    }

    /**
     * Expects the number to be a number and not infinite.
     *
     * @param        $number
     * @param string $message
     *
     * @throws \Stillat\Common\Exceptions\Arithmetic\NotFiniteNumberException
     */
    protected function expectValidNumber($number, $message = '')
    {
        if (is_nan($number) || is_infinite($number)) {
            throw new NotFiniteNumberException($message);
        }
    }

    /**
     * Expects a number not to be zero when using for division.
     *
     * @param        $number
     * @param string $message
     *
     * @throws \Stillat\Common\Exceptions\Arithmetic\DivideByZeroException
     * @throws \Stillat\Common\Exceptions\Arithmetic\NotFiniteNumberException
     */
    protected function expectNumberNotZeroForDivision($number, $message = '')
    {
        $this->expectValidNumber($number, $message);
        if ($number == 0) {
            throw new DivideByZeroException($message);
        }
    }

}