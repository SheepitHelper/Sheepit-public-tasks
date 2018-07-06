<?php

/**
 * Class uploader
 * @todo Rename this class to UploadFile
 */
class uploader
{
	/**
	 * File name being uploaded
	 * @var bool|string
	 */
	private $filename;

	/**
	 * File size
	 * @var int
	 */
	private $filesize;

	/**
	 * Flag to see if local file was read successfully for uploading.
	 *
	 * @var bool
	 */
	private $is_ready = false;

	/**
	 * uploader constructor.
	 * @param $filename
	 */
	public function __construct($filename)
	{
		if(is_file($filename))
		{
			$this->filename = realpath($filename);
			$this->filesize = filesize($filename);

			$this->is_ready = true;
		}
	}

	/**
	 * File size of a local file that is being uploaded.
	 *
	 * @return int
	 */
	public function filesize(): int
	{
		return (int)$this->filesize;
	}

	/**
	 * Your own way of how to rename a file before uploading to the server.
	 *
	 * @return string
	 */
	public function name(): string
	{
		// lower case
		// compact
		// hashed name?
		//pp3-".$file->hash().'-'.$file->name()
		$hash = $this->hash();
		return $hash."-".strtolower(basename($this->filename));
	}

	/**
	 * @return bool|resource
	 */
	public function pointer()
	{
		$fp = fopen($this->filename, "r+");
		return $fp;
	}

	/**
	 * Some Unique ID to protect overwriting files.
	 *
	 * @return string
	 */
	public function hash(): string
	{
		return date("Ymd-His").'-'.mt_rand(10000, 99999);
	}

	/**
	 * Were we able to collect the file information?
	 * Helpful to protect API call with wrong information about a file.
	 *
	 * @return bool
	 */
	public function is_ready(): bool
	{
		return $this->is_ready;
	}
}