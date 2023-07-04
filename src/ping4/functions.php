<?php
require_once ("db.php");
require_once ("PingResult.php");

function ping4($host): PingResult {
  $output = shell_exec("ping4 -c3 ".$_GET["host"]);

  $db = new PingDB();

  if ($output == "") {
    $result = new PingResult("", htmlspecialchars($host), 0, true, "$host is Unreachable");
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
    $values = explode("/", $output_lines[7]);
    $min = filter_var($values[3], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $max = filter_var($values[5], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $avg = filter_var($values[4], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $result = new PingResult($ip, htmlspecialchars($host), $avg, false, $output);
    if ($db) {
      $db->addPingResult($result);
    }
    return $result;
  }
}

function last24HoursPingResults(PingResult $result) {
  $db = new PingDB();
  if ($db) {
    $results = $db->last24HoursPingResults($result);
    ?><script type="text/javascript">
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
    foreach ($results as $key => $value) {
      echo "$key: $value";
    }
  }
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

  return $constructed_url;
}

function showHeader() {
  ?>
<!doctype html>
<html lang="en">
  <head>
    <?php if (isset($_GET["host"]) && $_GET["host"] != "") { ?>
    <title>ping4 - pinging <?php echo $_GET["host"] ?></title>
  <?php } else { ?>
    <title>ping4 - send ICMPv4 ECHO_REQUEST to network hosts</title>
  <?php } ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0C1MJF4SCC"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-0C1MJF4SCC');
    </script>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Run an Ipv4 ICMPv4 ping online to test the reachability of an Ipv4 host">
    <meta name="keywords" content="Ping4, Online, Ipv4, ICMPv4">
  </head>
  <?php
}
?>
