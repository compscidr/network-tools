<?php
// https://stackoverflow.com/a/31503474
function remove_filename($url)
{
    $file_info = pathinfo($url);
    return isset($file_info['extension'])
        ? str_replace($file_info['filename'] . "." . $file_info['extension'], "", $url)
        : $url;
}

// https://stackoverflow.com/questions/6969645/how-to-remove-the-querystring-and-get-only-the-url
function reconstruct_url(){
  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
  else
    $url = "http://";

  // Append the host(domain name, ip) to the URL.
  $url.= $_SERVER['HTTP_HOST'];

  if ($_SERVER['SERVER_PORT'] != '443') {
    $url.= ":".$_SERVER['SERVER_PORT'];
  }

  // Append the requested resource location to the URL
  $url.= $_SERVER['REQUEST_URI'];

  $url_parts = parse_url($url);
  $constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];

  $remove_file = remove_filename($constructed_url);
  if ($remove_file == "https:///" || $remove_file == "http://") {
    return $constructed_url;
  } else {
    return $remove_file;
  }
}

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
