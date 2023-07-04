<?php
function ping6($host) {
  $output = shell_exec("ping6 -c3 ".$_GET["host"]);
  if ($output == "") {
    $output = "No response from ".$host;
  }
  return $output;
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
    <title>ping6 - send ICMPv6 ECHO_REQUEST to network hosts</title>
  <?php } ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LH68S6GQMZ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-LH68S6GQMZ');
    </script>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Run an Ipv6 ICMPv6 ping online to test the reachability of an Ipv6 host">
    <meta name="keywords" content="Ping6, Online, Ipv6, ICMPv6">
  </head>
  <?php
}
?>
