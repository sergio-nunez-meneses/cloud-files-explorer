  </main>

  <?php if (basename($_SERVER['SCRIPT_NAME']) === 'index.php') {?>
    <script src="<?php echo REL_PATH; ?>public/js/login.js"></script>
  <?php
  } else {
    ?>
    <script src="<?php echo REL_PATH; ?>public/js/autoLogout.js"></script>
    <script src="<?php echo REL_PATH; ?>public/js/script.js"></script>
  <?php
  }
  ?>

</body>
</html>
