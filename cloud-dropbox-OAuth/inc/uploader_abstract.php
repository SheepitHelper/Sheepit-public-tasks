<?php

/**
 * Class uploader_abstract
 * @package uploader
 */
abstract class uploader_abstract
{
	/**
	 * Remote Procedure Calls - Fires an endpoint API Call
	 *
	 * @param string $path
	 * @param array $api_args
	 * @return string
	 */
	protected abstract function rpc($path="", $api_args=array()): string;

	/**
	 * Checks if a resource is a file
	 *
	 * @param $filename
	 * @return bool
	 */
	protected abstract function is_file($filename): bool;

	/**
	 * Checks if a resource is a directory
	 *
	 * @param $filename
	 * @return bool
	 */
	protected abstract function is_dir($filename): bool;
}