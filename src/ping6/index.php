<?php
require_once("functions.php");
showHeader();
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
        <pre><?php $output = ping6($_GET["host"]); echo $output; ?></pre>
      </div>
    <?php } else { ?>
      <div class="row pt-5">
        <p>Performs an ICMPv6 ping request using this online tool. For example, try <a href="?host=2001:4860:4860::8888">2001:4860:4860::8888</a> or <a href="?host=google.com"/>google.com</a> to check their ping6 response.</p>
      </div>
    <?php } ?>
    </div>
  </body>
</html>
