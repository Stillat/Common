<?php namespace Stillat\Common\Validation;

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\PresenceVerifierInterface;

trait ValidatableTrait {

	/**
	 * The validator instance.
	 *
	 * @var \Illuminate\Validation\Validator
	 */
	protected $validatorInstance = null;

	/**
	 * The validation errors.
	 *
	 * @var \Illuminate\Support\MessageBag
	 */
	protected $validationErrorMessages = null;

	/**
	 * The Presence Verifier implementation.
	 *
	 * @var \Illuminate\Validation\PresenceVerifierInterface
	 */
	protected $validationPresenceVerifier = null;

	/**
	 * Sets the Validator instance.
	 *
	 * @param  \Illuminate\Validation\Validator $validator
	 * @return void
	 */
	public function setValidator(Validator $validator)
	{
		$this->validatorInstance = $validator;
	}

	/**
	 * Gets the Validator instance.
	 *
	 * @return \Illuminate\Validation\Validator
	 */
	public function getValidator()
	{
		return $this->validatorInstance;
	}

	/**
	 * Sets the Precense Verifier implementation.
	 * 
	 * @param  \Illuminate\Validation\PresenceVerifierInterface $presenceVerifier
	 * @return void
	 */
	public function setPresenceVerifier(PresenceVerifierInterface $presenceVerifier)
	{
		$this->validationPresenceVerifier = $presenceVerifier;
	}

	/**
	 * Determine if the data passes the validation rules.
	 * 
	 * @return bool
	 */
	public function isValid()
	{
		if ($this->getValidator() == null)
		{
			return $this->performValidation(ValidatorFacade::make($this->getValidationData(), $this->getValidationRules(), $this->getValidationMessages()));
		}

		return $this->performValidation($this->validatorInstance);
	}

	/**
	 * Performs the actual validation process.
	 * 
	 * @param  \Illuminate\Validation\Validator $validator
	 * @return bool
	 */
	private function performValidation(Validator $validator)
	{

		if ($this->validationPresenceVerifier !== null)
		{
			$validator->setPresenceVerifier($this->validationPresenceVerifier);
		}

		$passed = $validator->passes();

		if (!$passed)
		{
			$this->validationErrorMessages = $validator->errors();
		}

		return $passed;
	}

	/**
	 * Returns the validation errors.
	 * 
	 * @return \Illuminate\Support\MessageBag
	 */
	public function errors()
	{
		return $this->validationErrorMessages;
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
	abstract function getValidationRules();

	/**
	 * Gets the validation data
	 *
	 * @return array
	 */
	abstract function getValidationData();

}