<?php namespace Stillat\Common\Validation;

use Stillat\Common\Validation\ValidationObjectInterface;

interface ValidationServiceInterface {

	public function validate(ValidationObjectInterface $object);

}