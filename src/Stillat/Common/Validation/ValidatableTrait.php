<?php namespace Stillat\Common\Validation;

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;

trait ValidatableTrait {

	/**
	 * The validator implementation
	 */
	protected $validatorInstance = null;

	protected $validationErrors = null;

	public function setValidator(Validator $validator)
	{
		$this->validatorInstance = $validator;
	}

	public function getValidator()
	{
		return $this->validatorInstance;
	}

	public function validate()
	{
		if ($this->getValidator() == null)
		{
			return $this->performValidation(Validator::make($this->getValidationData(), $this->getValidationRules(), $this->getValidationMessages()));
		}

		return $this->performValidation($this->validatorInstance);
	}

	private function performValidation(Validator $validator)
	{
		$passed = $validator->passes();

		if (!$passed)
		{
			$this->validationErrors = $validator->errors();
		}

		return $passed;
	}

	public function errors()
	{
		return $this->validationErrors;
	}

	/**
	 * Gets the validation messages
	 *
	 * @return array
	 */
	private function getValidationMessages()
	{
		return array();
	}

	/**
	 * Gets the validation rules
	 *
	 * @return array
	 */
	abstract private function getValidationRules();

	/**
	 * Gets the validation data
	 *
	 * @return array
	 */
	abstract private function getValidationData();

}