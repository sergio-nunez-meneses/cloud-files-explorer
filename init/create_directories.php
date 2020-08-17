<?php
$abs_path = dirname(dirname(__FILE__));

$home_dir = 'home';
if (!in_array($home_dir, scandir($abs_path))) {
  mkdir($home_dir, 0777);
}

$recycle_bin__dir = 'recycle bin';
if (!in_array($recycle_bin__dir, scandir($abs_path . DIRECTORY_SEPARATOR . $home_dir))) {
  mkdir($home_dir . DIRECTORY_SEPARATOR . 'recycle bin', 0777);
}
