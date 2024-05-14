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
    <title>Dumpers - Hexdump Buffer Decoder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Decode buffer hexdumps into a readable format">
    <meta name="keywords" content="hexdump, binary, decode, readable, ascii">
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
<?php } ?>
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
