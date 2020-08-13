<?php

include '../controllers/functions.php';

if (isset($_POST['create'])) {
  create_file();
} elseif (isset($_POST['delete'])) {
  delete_file();
}
