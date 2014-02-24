<?php namespace Stillat\Common\Client\Mime;

interface MimeResolverInterface {

	/**
	 * Resolves a given extension to a MIME Type.
	 *
	 * @return Stillat\Common\Client\Mime\MimeType
	 */
	public function resolveMime($extension);

}