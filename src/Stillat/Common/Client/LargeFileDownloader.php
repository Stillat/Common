<?php namespace Stillat\Common\Client;

use Stillat\Common\Client\DownloaderInterface;
use Stillat\Common\Client\ClientException;

class LargeFileDownloader implements DownloaderInterface {

	/**
	 * The amount of bytes to read/write at a time.
	 *
	 * @var integer
	 */
	protected $downloadChunkSize = 1048576;

	/**
	 * Returns an large file down-loader instance.
	 *
	 * @return Stillat\Common\Client\LargeFileDownloader
	 */
	public function __construct()
	{
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
		// First check to see if someone even entered anything.
		if (strlen($resourceName) == 0)
		{
			throw new ClientException('A resource name cannot be empty or blank');
		}

		// Let's check to see if the file actually exists on the local
		// file system.
		if (file_exists($resourceName))
		{
			$outputBuffer = '';

			$fileHandle = fopen($resourceName, 'rb');

			if ($fileHandle === false)
			{
				return false;	
			}

			while(feof($fileHandle) !== false)
			{
				$outputBuffer = fread($fileHandle, $this->downloadChunkSize);
				echo $buffer;
				ob_flush();
				flush();
			}

			return fclose($fileHandle);
		}
		else
		{
			// The file does not exist.
			throw new ClientException("The resource:'{$resourceName}' does not exist.");
		}
	}

}