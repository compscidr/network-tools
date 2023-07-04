<?php
require_once("functions.php");
showHeader();
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
      <div class="row pt-5">
        <pre><?php $output = ping4($_GET["host"]); echo $output; ?></pre>
      </div>
    <?php } else { ?>
      <div class="row pt-5">
        <p>Performs an ICMPv4 ping request using this online tool. For example, try <a href="?host=8.8.8.8">8.8.8.8</a> or <a href="?host=google.com"/>google.com</a> to check their ping4 response.
      </div>
    <?php } ?>
    </div>
  </body>
</html>
