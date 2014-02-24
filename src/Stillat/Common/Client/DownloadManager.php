<?php namespace Stillat\Common\Client;

use Stillat\Common\Client\DownloaderInterface;

class DownloadManager {

	/**
	 * Holds the downloader instance.
	 *
	 * @var Stillat\Common\Client\DownloaderInterface
	 */
	protected $downloaderInstance;

	/**
	 * Returns a new download manager instance.
	 *
	 * @param  Stillat\Common\Client\DownloaderInterface $downloader
	 * @return Stillat\Common\Client\DownloadManager
	 */
	public function __construct(DownloaderInterface $downloader)
	{
		$this->downloaderInstance = $downloader;
	}

	/**
	 * Downloads a given resource.
	 *
	 * @param  string $resourceName
	 * @return bool
	 *
	 * @throws Stillat\Common\Client\ClientException
	 */
	public function download($resourceName)
	{
		return $this->downloaderInstance->download($resourceName);
	}

}