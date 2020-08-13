<?php

function file_permissions($filename) {
  $perms = fileperms($filename);

  if (($perms & 0xC000) == 0xC000) $info = 's';
  elseif (($perms & 0xA000) == 0xA000) $info = 'l';
  elseif (($perms & 0x8000) == 0x8000) $info = '-';
  elseif (($perms & 0x6000) == 0x6000) $info = 'b';
  elseif (($perms & 0x4000) == 0x4000) $info = 'd';
  elseif (($perms & 0x2000) == 0x2000) $info = 'c';
  elseif (($perms & 0x1000) == 0x1000) $info = 'p';
  else $info = 'u';

  $info .= (($perms & 0x0100) ? 'r' : '-');
  $info .= (($perms & 0x0080) ? 'w' : '-');
  $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

  $info .= (($perms & 0x0020) ? 'r' : '-');
  $info .= (($perms & 0x0010) ? 'w' : '-');
  $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

  $info .= (($perms & 0x0004) ? 'r' : '-');
  $info .= (($perms & 0x0002) ? 'w' : '-');
  $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

  return $info;
}

function list_dir($dir) {
  if ($dir[strlen($dir)-1] != DIRECTORY_SEPARATOR) $dir .= DIRECTORY_SEPARATOR;

  if (!is_dir($dir)) die ("failed to open directory: $dir");

  $cwd  = scandir($dir);
  $dir_objects = [];

  foreach ($cwd as $files) {
    if ($files == '.' || $files == '..' || $files == '.git') continue;
    $file_path = $dir . $files;
    $file_object = [
      'name' => $files,
      'path' => $file_path,
      'size' => filesize($file_path),
      'perm' => file_permissions($file_path),
      'type' => mime_content_type($file_path),
      'date' => date("d F Y H:i", filemtime($file_path))
    ];
    $dir_objects[] = $file_object;
  }
  return $dir_objects;
}

function sort_files($array, $by, $order = SORT_ASC) {
  $new_array = [];
  $sortable_array = [];

  if (count($array) > 0) {
    foreach ($array as $k => $v) {
      if (is_array($v)) {
        foreach ($v as $k2 => $v2) {
          if ($k2 == $by) {
            $sortable_array[$k] = $v2;
          }
        }
      } else {
        $sortable_array[$k] = $v;
      }
    }

    switch ($order) {
      case SORT_ASC:
      asort($sortable_array);
      break;
      case SORT_DESC:
      arsort($sortable_array);
      break;
    }

    foreach ($sortable_array as $k => $v) {
      $new_array[$k] = $array[$k];
    }
  }
  return $new_array;
}

function upload_file() {
  if (!isset($_POST['upload'])) {
    return;
  } else {
    $image_dir = getcwd() . DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR;
    $element_image = filter_var($_FILES['files']['name'][0], FILTER_SANITIZE_STRING);

    move_uploaded_file($_FILES['files']['tmp_name'][0], $image_dir . $element_image);
    header('Location:../index.php?uploaded=yes');

    // for AJAX
    // print_r($_POST);
    // echo "file $element_image uploaded";
  }
}

function update_file() {
  if (!isset($_POST['update'])) {
    return;
  } else {
    file_put_contents($_POST['path'], $_POST['file_content']);
    header('Location:../index.php?updated=yes');
  }
}

function create_file() {
  if (!isset($_POST['create'])) {
    return;
  } else {
    $file_name = $_POST['file_name'];
    $path = $_POST['path'] . DIRECTORY_SEPARATOR;

    if (strpos($file_name, '.') === false) {
      mkdir($path . $file_name, 0777);
      header('Location:../index.php?created=folder');
    } else {
      $new_file = fopen($path . $file_name, 'a+');
      fwrite($new_file, 'write something');
      fclose($new_file);
      header('Location:../index.php?created=file');
    }
  }
}

function delete_file() {
  if (!isset($_POST['delete'])) {
    return;
  } else {
    $delete_file = $_POST['select'];
    $message = 'file ' . basename($_POST['select']) . ' deleted';

    if (is_dir($delete_file)) {
      rmdir($delete_file);
    } else {
      unlink($delete_file);
    }
    header("Location: ../index.php?action=$message");
  }
}
