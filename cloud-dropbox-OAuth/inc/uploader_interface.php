<?php

/**
 * Interface uploader_interface
 * @package uploader
 */
interface uploader_interface
{
	/**
	 * uploader_interface constructor. You must assign the config data while instantiating.
	 * @param config $config
	 */
	function __construct(config $config);

	/**
	 * Method to upload a file by its name.
	 *
	 * @param uploader $file
	 * @return string
	 */
	function upload(uploader $file): string;

	/**
	 * Method to list out files and folders.
	 *
	 * @return string
	 */
	function files(): string;

	/**
	 * Filter files
	 * @return array
	 */
	function files_only(): array;

	/**
	 * Method to delete a filename by its name.
	 *
	 * @param string $filename
	 * @return string
	 */
	function delete($filename=""): string;

	/**
	 * Method to download a file by its name.
	 * @param $remote_filename
	 * @param $local_target
	 * @return mixed
	 */
	function download($remote_filename, $local_target);
}
