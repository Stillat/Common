<?php namespace Stillat\Common\Database\Tenant;

use Stillat\Common\Database\Model as StillatModel;
use Stillat\Common\Database\Tenant\TenantManager as Manager;
use Illuminate\Support\Facades\Validator;

abstract class Model extends StillatModel {

	/**
	 * Returns a new instance of the tenant model.
	 *
	 * @return Stillat\Common\Database\Tenant\Model
	 */
	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);

		$manager = Manager::instance();
		$this->setConnection($manager->getCurrentConnection());
		unset($manager);
	}

	/**
     * Instatiates the validator used by the validation process, depending if the class is being used inside or
     * outside of Laravel.
     *
     * @param $data
     * @param $rules
     * @param $customMessages
     * @return \Illuminate\Validation\Validator
     * @see Ardent::$externalValidator
     */
	protected static function makeValidator($data, $rules, $customMessages)
	{

		$manager = Manager::instance();
		$tenantConnection = $manager->getCurrentConnection();
		unset($manager);

		if (self::$externalValidator)
		{
			$validator = self::$validationFactory->make($data, $rules, $customMessages);
			$validator->getPresenceVerifier()->setConnection($tenantConnection);
			return $validator;
		}
		else
		{
			$validator = Validator::make($data, $rules, $customMessages);
			$validator->getPresenceVerifier()->setConnection($tenantConnection);
			return $validator;
		}
	}

}