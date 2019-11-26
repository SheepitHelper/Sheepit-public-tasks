<?php

/**
 * API Credentials
 *
 * Class config
 * @package uploader
 */
class config
{
	/**
	 * API Access Token
	 *
	 * @var string Dropbox API Access Token
	 */
	public $token;

	/**
	 * The directory MUST exist already in Dropbox.
	 * Any read/write will be done here only for safety reasons.
	 *
	 * @var string Sub-Directory to work on
	 */
	public $path;
}
