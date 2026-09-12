<?php
require_once ("db.php");
require_once ("PingResult.php");

function ping4($host): PingResult {

  // get ip address from hostname (if an ip address is passed, it will return ip address
  $hostname = gethostbyname($host);
  //
  $ip = ip2long($hostname);
  if ($ip == false) {
    return new PingResult($hostname, "", 0.0, false, "Invalid host", false);
  }
  $output = shell_exec("ping4 -c3 $hostname");
  $result = parsePingOutput($host, $hostname, $output);
  $db = new PingDB();
  if ($db) {
    $db->addPingResult($result);
  }
  return $result;
}

/** Turn raw `ping -c3` stdout into a PingResult. $hostname is the resolved IP. */
function parsePingOutput(string $host, string $hostname, ?string $output): PingResult {
  if (!$output) {
    return new PingResult("", htmlspecialchars($hostname), 0.0, true, "$host is Unreachable", true);
  }
  // https://write.corbpie.com/ping-address-and-get-min-max-average-with-php/
  $output_lines = explode("\n", $output);
  $ip = trim(explode(" ", $output_lines[0])[2], "()");
  // minus 2 because there is a last line with just a /n char
  $rtt_line = $output_lines[sizeof($output_lines)-2];
  if (!str_contains($rtt_line, "rtt")) {
    return new PingResult($ip, htmlspecialchars($hostname), 0.0, true, $output, true);
  }
  $values = explode("/", $rtt_line);
  $avg = filter_var($values[4], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  return new PingResult($ip, htmlspecialchars($host), (float)$avg, false, $output, true);
}

function last24HoursPingResults(PingResult $result) {
  if (!$result->valid) {
    return;
  }
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
  if (!$result->valid) {
    return;
  }
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

/** Scheme + host + directory of the current request, e.g. https://www.ping4.network/ */
function reconstruct_url(): string
{
  $https = ($_SERVER['HTTPS'] ?? 'off') === 'on' || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
  $dir = rtrim(dirname(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/') . '/';
  return ($https ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $dir;
}
?>
