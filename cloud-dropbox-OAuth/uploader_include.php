<?php

include_once './inc/config.php';
include_once './inc/dropbox.php';
include_once './inc/uploader.php';

require_once './inc.config.php';

//var_dump($dropbox);

$config = new config();
$config->token = $dropbox["token"];
$config->path = $dropbox["path"];

$db = new dropbox($config);

$file = new uploader("test/muaha.gif");
$status = $db->upload($file);

//var_dump($status);

if (!$status){
    echo "There was a problem";
}