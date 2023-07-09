<?php
function formatBufferData($data) {
  $count = 0;
  $data = preg_replace("/\s*/m", '', $data);
  $data = str_split($data, 2);
  for ($i = 0; $i < count($data); $i++) {
    if ($i % 16 == 0) {
      $count = 0;
      if ($i != 0) {
        ?></div><?php
      }
      ?><div class="row mono" id="b<?php echo sprintf('%04x', $i); ?>"><?php printHexByte($data[$i]); echo " ";
    } else {
      printHexByte($data[$i]); echo " ";
      if ($count == 6) {
        echo "&nbsp;";
      }
      $count++;
    }
  }
  ?></div><?php
}

function displayAddresses($data) {
  $data = preg_replace("/\s*/m", '', $data);
  $data = str_split($data, 2);
  for ($i = 0; $i < count($data); $i++) {
    if ($i % 16 == 0) {
      if ($i != 0) {
        ?></div><?php
      }
      ?><div class="row mono" id="a<?php echo sprintf('%04x', $i);?>"><?php echo sprintf('%04x', $i);?><?php
    }
  }
  ?></div><?php
}

/**
 * Takes a 2-byte hex data and adds leading zero's if necessary. If not a hex
 * value (ie, out of bounds), colors it red.
 */
function printHexByte($data) {
  $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $data);
  if ($hexStr != $data) {
    ?><span color="red"><?php echo "OG: $data; AFTER: $hexStr"; ?></span><?php
  } else {
    if (strlen($hexStr) != 2) {
      $hexStr = "0".$hexStr;
    }
    echo $hexStr;
  }
}

function formatBufferDataAscii($data) {
  $data = preg_replace("/\s*/m", '', $data);
  $data = str_split($data, 2);
  for ($i = 0; $i < count($data); $i++) {
    if ($i % 16 == 0) {
      if ($i != 0) {
        ?></div><?php
      }
      ?><div class="row mono"><?php printAsciiByte($data[$i]);
    } else {
      printAsciiByte($data[$i]);
    }
  }
}

function printAsciiByte($data) {
  $decimal = hexdec($data);
  if ($decimal < 32 or $decimal > 126) {
    //$decimal = '<div class="col text-secondary">&bull;</span>';
    $decimal = "&bull;";
  } else {
    $decimal = chr($decimal);
  }
  echo $decimal;
}

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
  </head>
  <body class="d-flex">
    <div class="mx-auto flex-column w-75">
      <div class="row">
        <h1 class="text-center pt-5 logo">Hexdump Buffer Decoder</h1>
      </div>
      <div class="row">
        <form method="get" action="">
          <textarea class="form-control" id="bufferData" name="bufferData" rows="3" placeholder="Paste buffer data here in hex format"></textarea>
          <input class="form-control" type="submit" value="Decode"/>
        </form>
      </div>
      <div class="row pt-2">
        <h2>Ascii Decode</h2>
        <div class="col-auto" id="addresses" style="min-width: 62px;">
          <?php displayAddresses($_GET["bufferData"]); ?>
        </div>
        <div class="col-auto" id="formattedBufferData" style="min-width: 480px;">
          <?php formatBufferData($_GET["bufferData"]); ?>
        </div>
        <div class="col-auto ps-5" id="formattedAsciiData" style="min-width: 154px;">
          <?php formatBufferDataAscii($_GET["bufferData"]); ?>
        </div>
      </div>
    </div>
  </body>
</html>
