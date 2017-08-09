<?php
 
class CloudFileStorageDropbox {
	public static $type = 'GoogleDrive';
	
	public function __construct($shared_folder_url) {
		// the shared_folder id should extracted from the url
	}
	
	public function add($local_path) {
		// first upload the file to the shared_folder
		// second change the ownership of the file shared folder owner (if it was already part of option on step 1)
		
	}
}
