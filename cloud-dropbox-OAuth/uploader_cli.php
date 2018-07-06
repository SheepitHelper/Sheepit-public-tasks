<?php

include_once './inc/config.php';
include_once './inc/dropbox.php';
include_once './inc/uploader.php';

if($argc == 1 || $argc > 4){
    echo "Usage: uploader_cli.php <sourcefile> <target_dir> <token> \n"
    . "target_dir syntax is \"/path/to/folder\" \n ";
    exit;
} else {
    $sourcefile = $argv[1];
    $target_path = $argv[2];
    $token = $argv[3];
}

$config = new config();
$config->token = $token;
$config->path = $target_path;

$db = new dropbox($config);

$file = new uploader($sourcefile);
$status = $db->upload($file);

if (!$status){
    echo "There was a problem";
} else {
    var_dump($status);
}
