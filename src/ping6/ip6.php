<?php
  $output = shell_exec("curl -6 https://ping6.network/ip.php");
  echo $output;
?>