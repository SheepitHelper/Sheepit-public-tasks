<?php

include 'uploader_abstract.php';
include 'uploader_interface.php';

/**
 * API Interfaces to Dropbox
 * @see https://www.dropbox.com/developers/documentation/http/documentation
 *
 * @package uploader
 */
final class dropbox extends uploader_abstract implements uploader_interface
{
	private $config;
	public function __construct(config $config)
	{
		$this->config = $config;
	}

	/**
	 * Upload a single file
	 *
	 * @param uploader $file
	 * @return string
	 */
	public function upload(uploader $file): string
	{
		if(!$file->is_ready())
		{
			//throw new Exception("File is not ready to upload.");
			echo "File is not ready to upload: ".$file->name();
			return false;
		}

		/**
		 * API Arguments are brought from API Explorer
		 * @see https://dropbox.github.io/dropbox-api-v2-explorer/#files_upload
		 */
		$api_args = array(
			"path" => "{$this->config->path}/{$file->name()}",
			"mode" => array(
				".tag" => "overwrite",
			),
			"autorename" => false,
			"mute" => false,
		);

		$headers = array(
			"Authorization: Bearer {$this->config->token}",
			"Content-Type: application/octet-stream",
			"User-Agent: api-explorer-client",
			"Dropbox-API-Arg: ".json_encode($api_args),
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_BUFFERSIZE, 128);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_INFILE, $file->pointer());
		curl_setopt($ch, CURLOPT_INFILESIZE, $file->filesize());
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
		curl_setopt($ch, CURLOPT_UPLOAD, true);
		curl_setopt($ch, CURLOPT_URL,"https://content.dropboxapi.com/2/files/upload");
		$response = curl_exec ($ch);
		curl_close ($ch);

		return $response;
	}

	/**
	 * List of all files including folder names
	 *
	 * @return string
	 */
	public function files(): string
	{
		$api_args = array(
			"path" => $this->config->path,
			"recursive" => false,
			"include_deleted" => false,
		);

		$response = $this->rpc("files/list_folder", $api_args);
		return $response;
	}

	/**
	 * Get a list of "file"s only. Filters out directories.
	 *
	 * @return array
	 */
	public function files_only(): array
	{
		$response = $this->files();
		$entries = json_decode($response , true);

		/**
		 * Extract file names only
		 */
		$files = array();
		
		if(count($entries["entries"]))
		foreach($entries["entries"] as $file)
		{
			if($file[".tag"]=="file")
			{
				$files[] = $file["name"];
			}
		}

		return $files;
	}

	/**
	 * Delete a file by its name
	 *
	 * @param string $filename
	 * @return string
	 */
	public function delete($filename=""): string
	{
		/**
		 * We always work in a sub-folder only
		 */
		$filename = basename($filename);

		if(!$this->is_file($filename))
		{
			echo "Not a valid file to remove: ", $filename;
			return false;
		}

		$api_args = array(
			"path" => "{$this->config->path}/{$filename}",
		);

		$response = $this->rpc("files/delete", $api_args);

		return $response;
	}

	/**
	 * Execute Endpoints
	 *
	 * @param string $path
	 * @param array $api_args
	 * @return string
	 */
	protected function rpc($path="", $api_args=array()): string
	{
		$headers = array(
			"Authorization: Bearer {$this->config->token}",
			"Content-Type: application/json",
			"User-Agent: api-explorer-client",
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($api_args)); // JSON Data
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 200);
		curl_setopt($ch, CURLOPT_URL,"https://api.dropboxapi.com/2/{$path}");
		$response = curl_exec($ch);
		curl_close ($ch);

		return $response;
	}

	/**
	 * Download a single file
	 *
	 * @see https://dropbox.github.io/dropbox-api-v2-explorer/#files_download
	 * @see https://github.com/dropbox/dropbox-api-content-hasher
	 * @see https://www.dropbox.com/developers/reference/content-hash
	 *
	 * @param $remote_filename
	 * @param $local_target
	 * @return bool
	 */
	public function download($remote_filename, $local_target)
	{
		/**
		 * @todo If the revision, file size or content hash of the downloaded file is already same, no need to download it
		 */
		$success = false;
		if($fp = fopen ($local_target, "w+"))
		{
			$api_args = array(
				"path" => "{$this->config->path}/{$remote_filename}",
			);
			$json = json_encode($api_args);
			$headers = array(
				"Authorization: Bearer {$this->config->token}",
				"Content-Type: application/octet-stream",
				"User-Agent: uploader.php",
				"Dropbox-API-Arg: {$json}",
			);

			$ch = curl_init("https://content.dropboxapi.com/2/files/download");
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			$response = curl_exec($ch);
			curl_close($ch);

			$success = fclose($fp);
		}

		return $success;
	}

	/**
	 * Checks that the file exists in Dropbox
	 *
	 * @param $filename
	 * @return bool
	 */
	protected function is_file($filename): bool
	{
		// [.tag] => file
		return true;
	}

	/**
	 * Checks that the directory in Dropbox
	 *
	 * @param $filename
	 * @return bool
	 */
	protected function is_dir($filename): bool
	{
		// [.tag] => folder
		return true;
	}
}
