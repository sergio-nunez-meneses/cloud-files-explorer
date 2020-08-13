<?php
// define constants and declare main variables

define('ABS_HOME_PATH', getcwd() . DIRECTORY_SEPARATOR . $home_dir  . DIRECTORY_SEPARATOR);
define('REL_HOME_PATH', $home_dir  . DIRECTORY_SEPARATOR);

define('HOME', getcwd() . DIRECTORY_SEPARATOR . $home_dir);

$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$domain = $_SERVER['SERVER_NAME'];
define('BASE_URL', "${protocol}://${domain}");
