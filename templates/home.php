<?php
$title = 'files cloud';
include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'include/header.php';
?>

<section class="main-container" style="display: <?php echo $display_content; ?>">
  <div class="forms-btns-container" style="display: flex; padding: 1rem 0;">
    <div class="forms-container" style="display: flex; justify-content: space-between; align-content: center; align-items: center;">
      <form id="chdir" method="POST" enctype="application/x-www-form-urlencoded"></form>
      <form id="show_hide" method="POST" enctype="application/x-www-form-urlencoded"></form>
      <form id="sort" method="POST" enctype="application/x-www-form-urlencoded">
      <input type="hidden" name="order" value="<?php echo $order; ?>">
      </form>
      <form id="file_actions_test" method="POST" action="../templates/file_actions_test.php" enctype="application/x-www-form-urlencoded"></form>
    </div>
    <div class="sort-btns-container" style="display: flex; justify-content: flex-start; align-content: flex-start; align-items: flex-start;">
      <button type="submit" name="sort" form="sort" value="name" style="">name</button>
      <button type="submit" name="sort" form="sort" value="path" style="margin-left: 0.625rem">path</button>
      <button type="submit" name="sort" form="sort" value="size" style="margin-left: 0.625rem">size</button>
      <button type="submit" name="sort" form="sort" value="perm" style="margin-left: 0.625rem">permission</button>
      <button type="submit" name="sort" form="sort" value="type" style="margin-left: 0.625rem">type</button>
      <button type="submit" name="sort" form="sort" value="date" style="margin-left: 0.625rem">date</button>
    </div>
  </div>
</section>

<section style="display:<?php echo $display_content; ?>;">
  <?php
  // get current directory's content
  $cwd_content = sort_files(list_dir($cwd), $by, $order);

  foreach ($cwd_content as $key => $value) {
    if (!($cwd_content[$key]['name'][0] === '.' && $show_hide === 'show')) {
      if (is_dir($cwd_content[$key]['path'])) {
        ?>
        <div style="display: flex; justify-content: space-between; align-content: center; align-items: center; padding: 0.25rem 0;">
          <button type="button">
            <a href="?dir=<?php echo urlencode($cwd_content[$key]['path']); ?>" style="color: #000; text-decoration: none">
              <?php echo $cwd_content[$key]['name']; ?>
            </a>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['path']; ?>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['size']; ?>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['perm']; ?>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['type']; ?>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['date']; ?>
          </button>
          <a href="<?php echo basename(HOME) . DIRECTORY_SEPARATOR . $cwd_content[$key]['name']; ?>" download>
            <button type="button">download</button>
          </a>
          <input type="checkbox" name="select" form="file_actions_test" value="<?php echo $cwd_content[$key]['path']; ?>">
        </div>
        <?php
      } else {
        ?>
        <div style="display: flex; justify-content: space-between; align-content: center; align-items: center; padding: 0.25rem 0;">
          <button type="button">
            <a href="?file=<?php echo urlencode($cwd_content[$key]['path']); ?>" style="color: #000; text-decoration: none">
              <?php echo $cwd_content[$key]['name']; ?>
            </a>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['path'] ?>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['size'] ?>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['perm'] ?>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['type'] ?>
          </button>
          <button type="button">
            <?php echo $cwd_content[$key]['date'] ?>
          </button>
          <a href="<?php echo basename(HOME) . DIRECTORY_SEPARATOR . $cwd_content[$key]['name']; ?>" download>
            <button type="button">download</button>
          </a>
          <input type="checkbox" name="select" form="file_actions_test" value="<?php echo $cwd_content[$key]['path']; ?>">
        </div>
        <?php
      }
    }
  }
  ?>
</section>

<!-- DISPLAY SINGLE FILE -->
<section class="" style="display:<?php echo $display_file; ?>;">
  <div class="" style="display: flex; flex-direction: column; justify-content: center; align-content: center; align-items: center; padding: 1rem;">
    <?php (new File())->display_single_file(); ?>
  </div>
</section>

<!-- UPLOAD FILES -->
<!-- onsubmit="ajaxSend(this); return false;" -->
<section>
  <form id="file-upload" class="" name="" action="../templates/files_upload.php" method="POST" enctype="multipart/form-data">
    <input id="" class="" type="file" multiple name="files[]" value="">
    <button id="" class="" type="submit" name="upload">upload</button>
  </form>
</section>
<!-- FILE HANDLE -->
<section>
  <input type="hidden" name="path" form="file_actions_test" value="<?php echo $cwd; ?>"/>
  <input type="text" name="file_name" form="file_actions_test" placeholder="file name"/>
  <button type="submit" name="create" form="file_actions_test">create</button>
  <button type="submit" name="delete" form="file_actions_test">delete</button>
</section>

<?php include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'include/footer.php'; ?>
