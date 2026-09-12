<?php
require_once("FieldResult.php");
require_once(__DIR__ . "/../common/url.php");
require_once(__DIR__ . "/../common/footer.php");


function formatBufferData($data, $isEthernet) {
  ?>
<div class="row mono">
  <span class="blank">00</span>&nbsp;
  <span class="blank">01</span>&nbsp;
  <span class="blank">02</span>&nbsp;
  <span class="blank">03</span>&nbsp;
  <span class="blank">04</span>&nbsp;
  <span class="blank">05</span>&nbsp;
  <span class="blank">06</span>&nbsp;
  <span class="blank">07</span>&nbsp;&nbsp;
  <span class="blank">08</span>&nbsp;
  <span class="blank">09</span>&nbsp;
  <span class="blank">0A</span>&nbsp;
  <span class="blank">0B</span>&nbsp;
  <span class="blank">0C</span>&nbsp;
  <span class="blank">0D</span>&nbsp;
  <span class="blank">0E</span>&nbsp;
  <span class="blank">0F</span>&nbsp;
</div><?php
  $byte = 0;
  $count = 0;
  $data = stripAddresses($data);
  $data = str_split($data, 2);
  $field = "";
  for ($i = 0; $i < count($data); $i++) {
    $fieldResult = isStartOfField($byte);
    if ($fieldResult->isField) {
      $field = $fieldResult->fieldName;
    }
    if ($i % 16 == 0) {
      $count = 0;
      if ($i != 0) { ?>
        </div><?php
      }
      ?><div class="row mono" id="b<?php echo sprintf('%04x', $i); ?>"><?php
      printHexByte($data[$i], $fieldResult->isField, isEndOfField($byte), $field);
    } else {
      printHexByte($data[$i], $fieldResult->isField, isEndOfField($byte), $field);
      if ($count == 6) {
        echo "&nbsp;";
      }
      $count++;
      if (isEndOfField($byte)) {
        $field = "";
      }
    }
    $byte++;
  }
  ?></div><?php
}

function isStartOfField($byte): FieldResult {
  $ethernetStart = array(0,6,12);
  if (in_array($byte, $ethernetStart)) {
    return new FieldResult(true, "ethernet");
  } else {
    return new FieldResult(false, "");
  }
}

function isEndOfField($byte): bool {
  if ($byte == 5) {
    return true;
  } else if ($byte == 11) {
    return true;
  } else if ($byte == 13) {
    return true;
  }
  return false;
}

/**
 * Determines if the data has 4 bytes (8 characters) of addresses prepended to the start of each line which should
 * be stripped from the data
 */
function hasAddresses($data): bool
{
  $data = explode(' ', $data);
  if (sizeof($data) > 0) {
    if (strlen($data[0]) == 8) {
      return true;
    }
  }
  return false;
}

/**
 * Strings the addresses from the data and returns the data without them and without spaces.
 */
function stripAddresses($data): string
{
  $newdata = "";
  if (hasAddresses($data)) {
    foreach (preg_split("/((\r?\n)|(\r\n?))/", $data) as $line) {
      $newline = substr($line, 8);
      $newdata.=$newline;
    }
  } else {
      $newdata = $data;
  }
  $newdata = preg_replace("/\s*/m", '', $newdata); // remove spaces
  //$newdata = preg_replace("/[^0-9A-Fa-f]/", '', $newdata); // remove non-hex characters
  return $newdata;
}

/**
 * Based on how much data there is, shows the offset from 0 from the start of the data
 */
function displayAddresses($data): void {
  ?><br/><?php
  $data = stripAddresses($data);
  $data = str_split($data, 2);
  for ($i = 0; $i < count($data); $i++) {
    if ($i % 16 == 0) {
      if ($i != 0) {
        ?>
        </div><?php
      }
      ?><div class="row mono" id="a<?php echo sprintf('%04x', $i);?>"><span class="blank"><?php echo sprintf('%04x', $i);?></span><?php
    }
  }
  ?></div><?php
}

/**
 * Takes a 2-byte hex data and adds leading zero's if necessary. If not a hex
 * value (ie, out of bounds), colors it red.
 */
function printHexByte($data, $start, $end, $class) {
  if ($start) {
    $class .= " start";
  }
  if ($end) {
    $class .= " end";
  }
  if (!$start && !$end) {
    $class .= " default";
  }
  ?><span class="<?php echo $class;?>"><?php
  $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $data);
  if ($hexStr != $data) {
    ?><span color="red"><?php echo "OG: $data; AFTER: $hexStr"; ?></span><?php
  } else {
    if (strlen($hexStr) != 2) {
      $hexStr = "0".$hexStr;
    }
    echo $hexStr;
  }?>&nbsp;</span><?php
}

function formatBufferDataAscii($data) {
  ?><div class="row mono">0123456789ABCDEF</div><?php
  $data = stripAddresses($data);
  $data = str_split($data, 2);
  for ($i = 0; $i < count($data); $i++) {
    if ($i % 16 == 0) {
      if ($i != 0) { ?>
        </div><?php
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
    $decimal = "&bull;";
  } else {
    $decimal = chr($decimal);
  }
  echo $decimal;
}

/**
 * If the bytes at 13 and 14 are 86dd or 0800, we can probably assume it starts
 * with an ethernet header
 */
function isEthernet($data): bool {
  if (strlen($data) < 27) {
    return false;
  }
  $data = stripAddresses($data);
  if ($data[24] == "8" && $data[25] == "6" && $data[26] == "D" && $data[27] == "D") {
    return true;
  }
  if ($data[24] == "0" && $data[25] == "8" && $data[26] == "0" && $data[27] == "0") {
    return true;
  }


  return false;
}
?>
