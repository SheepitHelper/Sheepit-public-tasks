<?php

require_once(__DIR__.'/vendor/autoload.php');

$shared_folder = 'https://www.dropbox.com/sh/7zXXXXXc/AXXXXF_mXXX?dl=0';
$local_file = '/etc/fstab';


$cloud = new CloudFileStorageDropbox($shared_folder);

$cloud->add($local_file);