<?php
include '../include/header.php';

if (isset($_POST['create'])) {
  (new File())->create_file();
} elseif (isset($_POST['delete'])) {
  (new File())->delete_file();
}
