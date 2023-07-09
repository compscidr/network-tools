<?php
require_once("functions.php");
require_once("template.php");
showHeader("Stats");
?>
<body class="d-flex">
  <div class="mx-auto flex-column w-50">
    <div class="row">
      <h1 class="text-center pt-5 logo"><a href="<?php echo reconstruct_url(); ?>">ping6.network</a></h1>
      <h2 class="text-center logo">Stats</h2>
    </div>
    <div class="row pt-5">
      <?php globalStats(); ?>
    </div>
    <div class="row pt-5">
      <?php lastPings(10); ?>
    </div>
  </div>
</body>
