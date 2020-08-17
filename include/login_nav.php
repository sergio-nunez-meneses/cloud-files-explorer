<button type="button">
  welcome, <?php echo $_SESSION['user']; ?>
</button>
<button type="button">
  <a href=" <?php echo '../actions/logout.php?logout=yes'; ?> ">
    logout
  </a>
</button>
