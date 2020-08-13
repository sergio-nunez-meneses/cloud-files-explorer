<?php

// create home directory
$home_dir = 'home';
if (!is_dir($home_dir)) mkdir('home');

// create recycle bin directory
$recycle_bin__dir = 'recycle bin';
if (!is_dir($recycle_bin__dir) && !file_exists($recycle_bin__dir)) mkdir($home_dir . DIRECTORY_SEPARATOR . 'recycle bin');
