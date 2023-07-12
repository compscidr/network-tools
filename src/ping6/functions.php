<?php
require_once ("db.php");
require_once ("PingResult.php");

function ping6($host): PingResult {
  $output = shell_exec("ping6 -c3 ".$_GET["host"]);

  $db = new PingDB();

  if ($output == "") {
    $result = new PingResult("", htmlspecialchars($host), 0.0, true, "$host is Unreachable");
    if ($db) {
      $db->addPingResult($result);
    }
    return $result;
  } else {
    // https://write.corbpie.com/ping-address-and-get-min-max-average-with-php/
    $output_lines = explode("\n", $output);
    //print_r($output_lines);
    $ping_line = explode(" ", $output_lines[0]);

    //print_r($ping_line);
    $ip = $ping_line[2];
    $ip = trim($ip, "()");

    // minus 2 because there is a last line with just a /n char
    $rtt_line = $output_lines[sizeof($output_lines)-2];
    if(str_contains($rtt_line, "rtt")) {
      $values = explode("/", $rtt_line);
      $min = filter_var($values[3], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $max = filter_var($values[5], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $avg = filter_var($values[4], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $result = new PingResult($ip, htmlspecialchars($host), $avg, false, $output);
      if ($db) {
        $db->addPingResult($result);
      }
      return $result;
    } else {
      $result = new PingResult($ip, htmlspecialchars($host), 0.0, true, $output);
      if ($db) {
        $db->addPingResult($result);
      }
      return $result;
    }
  }
}

function last24HoursPingResults(PingResult $result) {
  $db = new PingDB();
  if ($db) {
    $results = $db->last24HoursPingResults($result);
    ?><script>
    var chart = c3.generate({
        bindto: '#chart',
        size: {
          height: 250,
          width: 400
        },
        data: {
            x: 'x',
            xFormat: '%Y-%m-%d %H:%M:%S',
            columns: [
              ['x', '<?php echo implode("','", array_keys($results)); ?>'],
              ['<?php echo $result->ip; ?>', '<?php echo implode("','", array_values($results)); ?>']
            ],
            colors: {
              '<?php echo $result->ip; ?>': '#209CEE',
            }
        },
        axis: {
          x: {
              type: 'timeseries',
              label: {
                text: 'Last 24h',
                position: 'outer-center',
              },
              tick: {
                culling: {
                  max: 2
                }
              }
          },
          y: {
            label: {
              text: 'RTT (ms)',
              position: 'outer-middle',
            },
            min: 0,
            padding: { top:0, bottom:0 }
          }
        },
        legend: {
          show: false
        },
      });
      </script>
    <?php
  }
}

function statComparison(PingResult $result) {
  $db = new PingDB();
  if ($db) {
    $results = $db->statComparison($result);
    ?><ul class="list-unstyled text-center"><?php
    foreach ($results as $key => $value) {
      echo "<li>$key: $value</li>";
    }
    ?></ul><?php
  }
}

function globalStats() {
  $db = new PingDB();
  if ($db) {
    $results = $db->globalStats();
    ?><ul class="list-unstyled text-center"><?php
    foreach ($results as $key => $value) {
      echo "<li>$key: $value</li>";
    }
    ?></ul><?php
  }
}

function lastPings($n) {
  $db = new PingDB();
  if ($db) {
    $results = $db->lastPings($n);
    ?><ul class="list-unstyled text-center"><li><b>Recent Pings</b></li><?php
    foreach ($results as $result) {
      ?><li><a href="<?php echo reconstruct_url(); ?>stats.php?host=<?php echo $result;?>"><?php echo $result;?></a></li><?php
    }
    ?></ul><?php
  }
}

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
?>
