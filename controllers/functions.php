<?php

function file_permissions($filename) {
  $perms = fileperms($filename);
  $info = '';

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

function formatted_size($bytes, $decimals = 2) {
  $sz = '';
  $factor = floor((strlen($bytes) - 1) / 3);

  if ($factor > 0) $sz = 'KMGT';

  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

function directorySize($path) {
  $total_bytes = 0;
  $path = realpath($path);

  if ($path !== false && $path != '' && file_exists($path)) {
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
      $total_bytes += $object->getSize();
    }
  }
  return $total_bytes;
}

function list_dir($dir) {
  if ($dir[strlen($dir)-1] !== DIRECTORY_SEPARATOR) $dir .= DIRECTORY_SEPARATOR;

  if (!is_dir($dir)) die ("failed to open directory: $dir is not a valid directory");

  $cwd  = scandir($dir);
  $dir_objects = [];

  foreach ($cwd as $files) {
    if ($files == '.' || $files == '..' || $files == '.git') continue;
    $file_path = $dir . $files;
    $file_object = [
      'name' => $files,
      'path' => $file_path,
      'size' => formatted_size(filesize($file_path), 0),
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
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        foreach ($value as $key2 => $value2) {
          if ($key2 == $by) {
            $sortable_array[$key] = $value2;
          }
        }
      } else {
        $sortable_array[$key] = $value;
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

    foreach ($sortable_array as $key => $value) {
      $new_array[$key] = $array[$key];
    }
  }
  return $new_array;
}

function base_url() {
  $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
  $domain = $_SERVER['SERVER_NAME'];
  $url = str_replace(dirname(realpath(__DIR__)), "${protocol}://${domain}", $cwd);
  return $url;
}

function update_file() {
  if (!isset($_POST['update'])) {
    return;
  } else {
    file_put_contents($_POST['path'], $_POST['file_content']);
    header('Location:../templates/home.php?updated=yes');
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
    header("Location:../templates/home.php?action=$message");
  }
}
