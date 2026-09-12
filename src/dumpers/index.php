<?php
require_once("functions.php");
if (isset($_GET["bufferData"]) && $_GET["bufferData"] != "") {
  $data = $_GET["bufferData"];
} else {
  $data = "";
}
$isEthernet = isEthernet($data);
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Hexdump Decoder - Online Hex Dump to ASCII Tool | dumpers.xyz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free online hexdump decoder. Paste a hex dump from tcpdump, Wireshark, xxd or a debugger and get an offset-aligned hex view with ASCII decode and Ethernet frame detection.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Hexdump Decoder - Online Hex Dump to ASCII Tool">
    <meta property="og:description" content="Free online hexdump decoder. Paste a hex dump from tcpdump, Wireshark, xxd or a debugger and get an offset-aligned hex view with ASCII decode and Ethernet frame detection.">
    <meta property="og:url" content="https://www.dumpers.xyz/">
    <link rel="canonical" href="https://www.dumpers.xyz/">
<?php if ($data != "") { ?>
    <meta name="robots" content="noindex,follow">
<?php } ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "dumpers.xyz - Hexdump Decoder",
      "url": "https://www.dumpers.xyz/",
      "description": "Free online hexdump decoder. Paste a hex dump from tcpdump, Wireshark, xxd or a debugger and get an offset-aligned hex view with ASCII decode and Ethernet frame detection.",
      "applicationCategory": "DeveloperApplication",
      "operatingSystem": "Any",
      "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" }
    }
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-N2EK8QVKH4"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-N2EK8QVKH4');
    </script>
  </head>
  <body class="d-flex">
    <div class="mx-auto flex-column w-75">
      <div class="row">
        <h1 class="text-center pt-5 logo"><a href="<?php echo reconstruct_url(); ?>">Hexdump Buffer Decoder</a></h1>
      </div>
      <div class="row">
        <form method="get" action="">
          <textarea class="form-control" id="bufferData" name="bufferData" rows="3" placeholder="Paste buffer data here in hex format"><?php echo $data; ?></textarea>
          <input class="form-control mt-2" type="submit" value="Decode"/>
        </form>
      </div>
<?php if ($data != "") { ?>
  <?php if (hasAddresses($data)) { ?>
      <div class="alert alert-warning mt-2">Detected Addresses in Dump - removing</div>
  <?php } ?>
      <div class="row pt-2">
        <div class="col-auto" id="addresses" style="min-width: 62px;">
          <h2 class="text-center logo">&nbsp;</h2>
          <?php displayAddresses($data); ?>
        </div>
        <div class="col-auto" id="formattedBufferData" style="min-width: 480px;">
          <h2 class="text-center logo">Hex Dump</h2>
          <?php formatBufferData($data, $isEthernet); ?>
        </div>
        <div class="col-auto ps-5" id="formattedAsciiData" style="min-width: 154px;">
          <h2 class="text-center logo">Ascii Decode</h2>
          <?php formatBufferDataAscii($data); ?>
        </div>
      </div>
<?php } else { ?>
      <div class="row pt-5">
        <h2 class="logo">What is a hexdump?</h2>
        <p>A hexdump is a byte-by-byte view of binary data written as hexadecimal pairs, usually 16 bytes per line with an
            offset column on the left. Tools like <code>tcpdump -X</code>, Wireshark, <code>xxd</code>, <code>hexdump -C</code>
            and most debuggers produce one when you ask to see raw packet or memory contents.</p>
        <h2 class="logo">How to use this decoder</h2>
        <p>Paste the raw hex into the box above and click Decode. Leading offset columns are detected and removed. The result shows
            the bytes re-aligned in an offset-labelled hex view next to their ASCII decode, with non-printable bytes shown as dots.</p>
        <p>The first 14 bytes are marked as the Ethernet destination MAC, source MAC and EtherType fields, so packet captures
            can be read without reaching for a protocol reference.</p>
        <p>Need to check reachability instead of decode bytes? Try <a href="https://www.ping4.network">ping4.network</a> or
            <a href="https://www.ping6.network">ping6.network</a>.</p>
      </div>
<?php } ?>
      <footer class="row pt-5 pb-3 text-center small text-muted">
        <p>Open source on <a href="https://github.com/compscidr/network-tools">GitHub</a> &middot;
          <a href="https://www.ping4.network">ping4.network</a> &middot;
          <a href="https://www.ping6.network">ping6.network</a> &middot;
          <a href="https://www.dumpers.xyz">dumpers.xyz</a></p>
      </footer>
    </div>
    <div>
    <?php
      if($isEthernet) {
        ?>Ethernet<?php
      }
    ?>
    </div>
  </body>
</html>
