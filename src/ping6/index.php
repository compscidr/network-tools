<?php
require_once("functions.php");
require_once("template.php");
if (isset($_GET["host"]) && $_GET["host"] != "") {
  showHeader("ping6 - pinging ".$_GET["host"]);
} else {
  showHeader("ping6 - send ICMPv6 ECHO_REQUEST to network hosts");
}
?>
  <body class="d-flex">
    <div class="mx-auto flex-column w-50">
      <div class="row">
        <h1 class="text-center pt-5 logo"><a href="<?php echo reconstruct_url(); ?>">ping6.network</a></h1>
        <form id="ping6" method="get" action="" class="d-flex">
          <input type="text" placeholder="IPv6 address or hostname" name="host" class="form-control me-2"/>
          <input type="button" value="Ping Ipv6 host" onclick="document.getElementById('ping6').submit();" class="btn btn-primary">
        </form>
      </div>
<?php if (isset($_GET["host"]) && $_GET["host"] != "") { ?>
      <div class="row pt-5">
        <pre><?php $result = ping6($_GET["host"]); echo $result->rawResult; ?></pre>
      </div>
      <div class="row pt-5" id="chart">
        <?php last24HoursPingResults($result); ?>
      </div>
      <div class="row pt-5">
        <?php statComparison($result); ?>
      </div>
<?php } else { ?>
      <div class="row pt-5">
        <p>Performs an ICMPv6 ping request using this online tool. For example, try <a href="?host=2001:4860:4860::8888">2001:4860:4860::8888</a> or <a href="?host=google.com"/>google.com</a> to check their ping6 response.</p>
        <p>Interested in a ping4 request instead? Try <a href="https://ping4.network">ping4.network</a> instead.</p>
        <p>Checkout <a href="stats.php">Ping6 stats</a>.</p>
      </div>
<?php } ?>
    </div>
  </body>
</html>
