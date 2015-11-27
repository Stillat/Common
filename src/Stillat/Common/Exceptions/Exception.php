<?php

namespace Stillat\Common\Exceptions;

use Exception as PHPException;
use Stillat\Common\Contracts\Exceptions\StillatException;

class Exception extends PHPException implements StillatException
{

}