<?php
require_once("functions.php");
require_once("template.php");
showHeader("Stats");
?>
<body class="d-flex">
  <div class="mx-auto flex-column w-50">
    <div class="row">
      <h1 class="text-center pt-5 logo"><a href="<?php echo reconstruct_url(); ?>">ping6.network</a></h1>
    </div>
<?php if (isset($_GET["host"]) && $_GET["host"] != "") {
  $result = new PingResult("", htmlspecialchars($_GET["host"]), 0.0, true, "", true);
  ?>
    <div class="row">
      <h2 class="text-center logo">Stats for <?php echo $_GET["host"]; ?></h2>
    </div>
    <div class="row pt-5 text-center mx-auto" style="max-width: 410px;">
      <div id="chart"></div>
    </div>
    <div class="row pt-5">
      <?php statComparison($result); ?>
    </div>
    <div class="row text-center">
      <a href="<?php echo reconstruct_url(); ?>?host=<?php echo $_GET["host"];?>">Ping6 <?php echo $_GET["host"];?></a>
    </div>
    <?php last24HoursPingResults($result); ?>
<?php } else { ?>
    <div class="row">
      <h2 class="text-center logo">Stats</h2>
    </div>
    <div class="row pt-5">
      <?php globalStats(); ?>
    </div>
    <div class="row pt-5">
      <?php lastPings(10); ?>
    </div>
<?php } ?>
  </div>
</body>
