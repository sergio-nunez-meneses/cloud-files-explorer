<?php
session_start();

define('ABS_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('REL_PATH', DIRECTORY_SEPARATOR . basename(ABS_PATH) . DIRECTORY_SEPARATOR);
define('HOME', ABS_PATH . 'home');

require_once ABS_PATH . 'init/create_directories.php';
require_once ABS_PATH . 'controllers/functions.php';
require_once ABS_PATH . 'controllers/db.php';
require_once ABS_PATH . 'controllers/user_class.php';
require_once ABS_PATH . 'controllers/directory_class.php';
require_once ABS_PATH . 'controllers/file_class.php';

define('HOME_SIZE', directorySize(HOME));

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
if (!empty($_GET['uploaded'])) {
  if ($_GET['uploaded'] === 'yes') {
    echo '<script> alert("file uploaded"); </script>';
  } elseif ($_GET['uploaded'] === 'no') {
    echo '<script> alert("file already exists"); </script>';
  } elseif ($_GET['uploaded'] === 'size') {
    echo '<script> alert("you have reach the max capacity of your cloud"); </script>';
  }
}

if (!empty($_GET['created'])) {
  if ($_GET['created'] === 'folder') {
    echo '<script> alert("folder created"); </script>';
  } elseif ($_GET['created'] === 'file') {
    echo '<script> alert("file created"); </script>';
  } elseif ($_GET['created'] === 'size') {
    echo '<script> alert("you have reach the max capacity of your cloud"); </script>';
  }
}

if (!empty($_GET['updated'])) {
  if ($_GET['updated'] === 'yes') {
    echo '<script> alert("file updated"); </script>';
  } elseif ($_GET['updated'] === 'no') {
    echo '<script> alert("file' . $_GET['file'] . 'does not exist"); </script>';
  } elseif ($_GET['updated'] === 'size') {
    echo '<script> alert("you have reach the max capacity of your cloud"); </script>';
  }
}

if (!empty($_GET['displayed'])) echo '<script> alert("file cannot be displayed"); </script>';

if (!empty($_GET['action'])) echo '<script> alert("' . $_GET['action'] . '"); </script>';

$user = new User();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sergio Núñez Meneses" name="author">
    <link rel="icon" type="image/png" href="<?php echo REL_PATH;?>public/img/favicon3.png">
    <link rel="stylesheet" href="<?php echo REL_PATH;?>public/less/style.css">
    <script src="<?php echo REL_PATH;?>public/js/functionsDOM.js"></script>
    <title><?php echo $title; ?></title>
  </head>

  <body>

    <header>
      <section class="logo-container">
        <a href="index.php">
          <img class="logo" src="<?php echo REL_PATH; ?>public/img/favicon.png" alt="logo">
        </a>
      </section>
      <section>
        <nav class="nav-container">

          <?php
          if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
            $breadcrumb_menu = explode(DIRECTORY_SEPARATOR, getcwd());
            $path_builder = '';
            $is_home = false;

            foreach ($breadcrumb_menu as $item) {
              $path_builder .= $item . DIRECTORY_SEPARATOR;
              if ($item === basename(HOME)) $is_home = true;
              if ($is_home) {
                ?>
                <button type="button">
                  <a href="?dir=<?php echo $path_builder; ?>">
                    <?php echo $item; ?>
                  </a>
                </button>
                <?php
              }
            }
            ?>
            <button type="submit" name="show_hide" form="show_hide" value="<?php echo $show_hide; ?>">
              <?php
              if ($show_hide === 'show') echo 'show';
              else echo 'hide';
              ?>
            </button>
            <?php
            $user->is_logged();
          }
          ?>

        </nav>
      </section>
    </header>

    <main>
