<?php

namespace Stillat\Common\Exceptions\Argument;

use Stillat\Common\Contracts\Exceptions\ArgumentException as ArgumentExceptionInterface;
use Stillat\Common\Exceptions\Exception;

class ArgumentException extends Exception implements ArgumentExceptionInterface
{
}