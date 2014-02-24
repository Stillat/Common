<?php namespace Stillat\Common\Client;

interface DownloaderInterface {

	/**
	 * Downloads a given resource.
	 *
	 * @param  string $resourceName
	 * @return bool
	 *
	 * @throws Stillat\Common\Client\ClientException
	 */
	public function download($resourceName);

}