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
        <form id="ping4" method="get" action="<?php echo reconstruct_url(); ?>index.php" class="d-flex">
          <input type="text" placeholder="IPv4 address or hostname" name="host" class="form-control me-2">
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
        <p>Performs an ICMPv4 ping request using this online tool. For example, try <a href="?host=8.8.8.8">8.8.8.8</a> or <a href="?host=google.com">google.com</a> to check their ping4 response.</p>
        <p>Interested in a ping6 request instead? Try <a href="https://ping6.network">ping6.network</a> instead.</p>
        <p>Checkout <a href="stats.php">Ping4 stats</a>.</p>
      </div>

      <div class="row pt-5">
        <h2 class="logo">How does ping4 work?</h2>
        <p>When ping4 is called, an ICMPv4 packet is generated at the host calling the ping4 program. Most people are
            connected to the Internet by a router at their home, so the packet is sent there next, and then from the router
            to the ISP. If the ISP can find a route to the end host, it will forward the packet (perhaps across many more hops)
            until it reaches the ISP of the destination device (or perhaps some hosting provider network like AWS, GCP, Azure, etc).</p>
        <p>At each hop along the way the Time-To-Live (TTL) field in the IPv4 packet is decreased. If the TTL field reaches 0,
            the ICMPv4 packet is dropped. An ICMP reply may also be sent back to the source indicating that the host is not
            reachable. This may also occur if a device along the path cannot locate a route to the destination.</p>
        <p>If the packet does successfully reach the intended destination, the destination generates an ICMP-reply
            packet with a new TTL, which is then decreased for every hop on the route back to the source. When the
            ICMP-reply is received at the source, the ping program outputs a line of text with the round trip time (RTT)
            which is the time between when the packet was sent and when the reply was received.</p>
        <p>If ping4 is called with a hostname instead of an Ipv4 address, it may kick off a DNS request to resolve
            the Ipv4 address of the hostname prior to generating the ICMPv4 packet.</p>
        <img src="ping4.png" alt="ping4 sequence diagram">
      </div>
<?php } ?>
    </div>
  </body>
</html>
