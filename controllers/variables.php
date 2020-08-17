<?php
define('HOME_ABS_PATH', getcwd() . DIRECTORY_SEPARATOR . $home_dir);
define('HOME_REL_PATH', DIRECTORY_SEPARATOR . basename(ABS_PATH) . DIRECTORY_SEPARATOR . $home_dir . DIRECTORY_SEPARATOR);
?>

<!-- <p>project's absolute path: <?php // echo ABS_PATH; ?> </p>
<p>project's relative path: <?php // echo REL_PATH; ?> </p>
<p>home's absolute path: <?php // echo HOME_ABS_PATH; ?> </p>
<p>home's relative path: <?php // echo HOME_REL_PATH; ?> </p> -->
