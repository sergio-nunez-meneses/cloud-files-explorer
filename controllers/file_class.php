<?php

class File extends Database
{

  public function create_file()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
      $user_id = filter_var((new User())->user_id($_SESSION['user']), FILTER_SANITIZE_STRING);
      $file = $file_link = filter_var($_POST['file_name'], FILTER_SANITIZE_STRING);
      $path = filter_var($_POST['path'] . DIRECTORY_SEPARATOR, FILTER_SANITIZE_STRING);
      $file_name = filter_var(pathinfo($file, PATHINFO_FILENAME), FILTER_SANITIZE_STRING);

      if (strpos($file, '.') === false) {
        if ($home_size < 100) {
          mkdir($path . $file, 0755);
          chmod($path . $file, 0755);
          header('Location:../templates/home.php?created=folder');
        } else {
          header('Location:../templates/home.php?created=size');
        }
      } else {
        if ($home_size < 100) {
          $create_file = fopen($path . $file, 'a+');
          fwrite($create_file, 'write something');
          fclose($create_file);
          chmod($create_file, 0755);

          $this->run_query('INSERT INTO files VALUES (NULL, :file_name, 0, NOW(), :file_link, :user_id)', ['file_name' => $file_name, 'file_link' => $file_link, 'user_id' => $user_id]);

          header('Location:../templates/home.php?created=file');
        } else {
          header('Location:../templates/home.php?created=size');
        }
      }
    }
  }

  public function upload_file()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
      $user_id = filter_var((new User())->user_id($_SESSION['user']), FILTER_SANITIZE_STRING);
      $total_files = sizeof($_FILES['files']['name']);

      for ($i = 0; $i < $total_files; $i++) {
        $file = $file_link = filter_var($_FILES['files']['name'][$i], FILTER_SANITIZE_STRING);
        $file_name = filter_var(pathinfo($file, PATHINFO_FILENAME), FILTER_SANITIZE_STRING);
        $home_size = directorySize(HOME);

        // check if files already exist in database
        $stmt = $this->run_query('SELECT * FROM files WHERE file_name = :file_name', ['file_name' => $file_name]);
        $get_file = $stmt->fetch();

        if (!in_array($file, scandir(HOME)) && $get_file == false) {
          if ($home_size < 100 || filesize($file) < 100) {
            move_uploaded_file($_FILES['files']['tmp_name'][$i], HOME . DIRECTORY_SEPARATOR . $file);
            chmod(HOME . DIRECTORY_SEPARATOR . $file, 0755);

            $this->run_query('INSERT INTO files VALUES (NULL, :file_name, 0, NOW(), :file_link, :user_id)', ['file_name' => $file_name, 'file_link' => $file_link, 'user_id' => $user_id]);

            header('Location:../templates/home.php?uploaded=yes');
          } else {
            header('Location:../templates/home.php?uploaded=size');
          }
        } else {
          header('Location:../templates/home.php?uploaded=no');
        }
      }

      // for AJAX
      // print_r($_POST);
      // echo "file $element_image uploaded";
    }
  }

  public function update_file()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
      $user_id = filter_var((new User())->user_id($_SESSION['user']), FILTER_SANITIZE_STRING);
      $path = filter_var($_POST['path'], FILTER_SANITIZE_STRING);
      $content = filter_var($_POST['file_content'], FILTER_SANITIZE_STRING);

      file_put_contents($path, $content);

      header('Location:../templates/home.php?updated=yes');
    }
  }

  public function delete_file()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
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

  public function display_single_file()
  {
    if (!empty($_GET['file'])) {
      $file = $_GET['file'];
      $file_type = explode('/', mime_content_type($file));

      if ($file_type[0] === 'text') {
        ?>
        <form id="file-update" method="POST" action="../templates/file_update.php" enctype="application/x-www-form-urlencoded">
          <input type="hidden" name="path" value="<?php echo $file; ?>"/>
          <textarea id="text" rows="40" cols="80" name="file_content" style="width: 100%; height: auto;"><?php echo htmlentities(file_get_contents($file)); ?></textarea>
          <button type="submit" name="update">save</button>
        </form>
        <?php
      } elseif ($file_type[0] === 'image') {
        echo '
        <img src="' . base_url() . DIRECTORY_SEPARATOR . basename($file) . '" style="max-width: 100%; height: auto; padding: 1rem 0">
        <a href="home/' . basename($file) . '" download>
        <button type="button">download</button>
        </a>
        ';
      } elseif ($file_type[0] === 'audio') {
        echo '
        <audio controls autoplay="1" loop="true" style="width: 100%; padding: 1rem 0">
        <source src="' . base_url() . DIRECTORY_SEPARATOR . basename($file) . '">
        </audio>
        ';
      } elseif ($file_type[1] === 'pdf') {
        echo '<iframe src="' . base_url() . DIRECTORY_SEPARATOR . basename($file) . '" frameborder="1" style="width: 100%; height: 500px; padding: 1rem 0"></iframe>';
      } elseif ($file_type[0] === 'video') {
        echo '<iframe src="' . base_url() . DIRECTORY_SEPARATOR . basename($file) . '" frameborder="1" style="width: 100%; height: 500px; padding: 1rem 0"></iframe>';
      }
    } else {
      header("Location:../templates/home.php?displayed=no");
    }
  }
}
