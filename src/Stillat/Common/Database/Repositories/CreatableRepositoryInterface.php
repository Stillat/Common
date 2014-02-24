<?php namespace Stillat\Common\Database\Repositories;

interface CreatableRepositoryInterface {

	/**
	 * Create the repository data store.
	 *
	 * @return void
	 */
	public function createRepository();

	/**
	 * Determine if the repository exists.
	 *
	 * @return bool
	 */
	public function repositoryExists();

	/**
	 * Remove the repository data store.
	 *
	 * @return void
	 */
	public function removeRepository();

}