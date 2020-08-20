<?php
$home_dir = basename(HOME);
if (!in_array($home_dir, scandir(ABS_PATH))) {
  mkdir($home_dir, 0755);
  chmod($home_dir, 0755);
}

$recycle_bin__dir = 'recycle bin';
if (!in_array($recycle_bin__dir, scandir(ABS_PATH . $home_dir))) {
  mkdir($home_dir . DIRECTORY_SEPARATOR . 'recycle bin', 0755);
  chmod($home_dir . DIRECTORY_SEPARATOR . 'recycle bin', 0755);
}
