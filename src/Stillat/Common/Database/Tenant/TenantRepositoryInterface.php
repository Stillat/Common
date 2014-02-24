<?php namespace Stillat\Common\Database\Tenant;

interface TenantRepositoryInterface {

	/**
	 * Returns a given tenant 
	 *
	 * @return Stillat\Common\Database\Model
	 */
	public function getTenant($accountID);

	public function log($tenant_id);

	public function getTenants();

	public function grantUserOnTenant($user_id, $tenant_id);

	public function removeUserFromTenant($user_id, $tenant_id);

	public function getUserTenants($user_id);

}