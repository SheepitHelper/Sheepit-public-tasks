<?php

require_once(__DIR__.'/vendor/autoload.php');

$shared_folder = 'https://drive.google.com/drive/folders/0B0SgGQXXXXXXXXXXXXXXWmp5Qlk?usp=sharing';
$local_file = '/etc/fstab';


$cloud = new CloudFileStorageGoogleDrive($shared_folder);

$cloud->add($local_file);