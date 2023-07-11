<?php
require_once("functions.php");
require_once("template.php");
if (isset($_GET["host"]) && $_GET["host"] != "") {
  showHeader("ping4 - pinging ".$_GET["host"]);
} else {
  showHeader("ping4 - send ICMPv4 ECHO_REQUEST to network hosts");
}
?>
  <body class="d-flex">
    <div class="mx-auto flex-column w-50">
      <div class="row">
        <h1 class="text-center pt-5 logo"><a href="<?php echo reconstruct_url(); ?>">ping4.network</a></h1>
        <form id="ping4" method="get" action="" class="d-flex">
          <input type="text" placeholder="IPv4 address or hostname" name="host" class="form-control me-2"/>
          <input type="button" value="Ping Ipv4 host" onclick="document.getElementById('ping4').submit();" class="btn btn-primary">
        </form>
      </div>
<?php if (isset($_GET["host"]) && $_GET["host"] != "") { ?>
      <div class="row">
        <h2 class="text-center logo">Ping4 results for <?php echo $_GET["host"]; ?></h2>
      </div>
      <div class="row pt-5 text-center mx-auto">
        <pre><?php $result = ping4($_GET["host"]); echo $result->rawResult; ?></pre>
      </div>
      <div class="row pt-5 text-center mx-auto" style="max-width: 410px;">
        <div id="chart"></div>
      </div>
      <div class="row pt-5">
        <?php statComparison($result); ?>
      </div>
      <?php last24HoursPingResults($result); ?>
<?php } else { ?>
      <div class="row pt-5">
        <p>Performs an ICMPv4 ping request using this online tool. For example, try <a href="?host=8.8.8.8">8.8.8.8</a> or <a href="?host=google.com"/>google.com</a> to check their ping4 response.</p>
        <p>Interested in a ping6 request instead? Try <a href="https://ping6.network">ping6.network</a> instead.</p>
        <p>Checkout <a href="stats.php">Ping4 stats</a>.</p>
      </div>
<?php } ?>
    </div>
  </body>
</html>
