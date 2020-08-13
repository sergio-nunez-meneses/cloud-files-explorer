<?php
require_once './init/create_directories.php';
require_once './controllers/variables.php';
require_once './controllers/functions.php';

// change current working directory
if (!empty($_GET['dir'])) {
  $cwd = $_GET['dir'];
} else {
  $cwd = HOME;
}
chdir($cwd);

// sort files by
if (!isset($_POST['sort'])) {
  $by = 'name';
} else {
  $by = $_POST['sort'];
}

// order files by
if (!isset($_POST['order'])) {
  $order = SORT_ASC;
} else {
  if ($_POST['order'] == SORT_ASC) {
    $order = SORT_DESC;
  } else {
    $order = SORT_ASC;
  }
}

// show hidden files
if (!isset($_POST['show_hide'])) {
  $show_hide = 'show';
} else {
  if ($_POST['show_hide'] === 'show') {
    $show_hide = 'hide';
  } else {
    $show_hide = 'show';
  }
}

// display single file
if (!empty($_GET['file'])) {
  $file = $_GET['file'];
  $display_content = 'none';
  $display_file = 'block';
} else {
  $file = '';
  $display_content = 'block';
  $display_file = 'none';
}

// display action informations
if (!empty($_GET['uploaded'])) echo '<script> alert("file uploaded"); </script>';
if (!empty($_GET['updated'])) echo '<script> alert("file updated"); </script>';
if (!empty($_GET['created']) && $_GET['created'] === 'folder') echo '<script> alert("folder created"); </script>';
if (!empty($_GET['created']) && $_GET['created'] === 'file') echo '<script> alert("file created"); </script>';
if (!empty($_GET['action'])) echo '<script> alert("' . $_GET['action'] . '"); </script>';

session_start();
// $user = new User();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sergio Núñez Meneses" name="author">
    <title>Files Manager</title>
  </head>
  <body>

    <?php
    // DISPLAY NAVBAR
    $breadcrumb_menu = explode(DIRECTORY_SEPARATOR, getcwd());
    $path_builder = '';
    $is_home = false;

    echo '
    <header>
    <nav class="" style="display: flex; justify-content: space-between">
    ';
    foreach ($breadcrumb_menu as $item) {
      $path_builder .= $item . DIRECTORY_SEPARATOR;
      if ($item === $home_dir) $is_home = true;
      if ($is_home) {
        echo
        '
        <button type="button">
        <a href="?dir=' . $path_builder . '" style="color: #000; text-decoration: none">' . $item . '</a>
        </button>
        ';
      }
    }
    echo '
    <button type="submit" name="show_hide" form="show_hide" value="' . $show_hide . '">';
    if ($show_hide === 'show') echo 'show';
    else echo 'hide';

    echo '
    </button>
    <button type="button">login</button>
    </nav>
    </header>
    ';
