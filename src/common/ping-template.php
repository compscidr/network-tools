<?php
$site = "ping" . PING_VERSION . ".network";
$description = "Ping an IPv" . PING_VERSION . " address or hostname online. Free IPv" . PING_VERSION . " ping test showing round-trip time, packet loss and 24-hour history for any host.";

function showHeader($title) {
  global $site, $description;
  ?>
<!doctype html>
<html lang="en">
  <head>
    <title><?php echo $title; ?></title>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo GA_ID; ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo GA_ID; ?>');
    </script>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<?php if (isset($_GET["host"]) && $_GET["host"] != "") { ?>
  <!-- upgrading version may cause problems: https://stackoverflow.com/a/67613182 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" integrity="sha512-cznfNokevSG7QPA5dZepud8taylLdvgr0lDqw/FEZIhluFsSwyvS81CMnRdrNSKwbsmc43LtRd2/WMQV+Z85AQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.16.0/d3.min.js" integrity="sha512-FHsFVKQ/T1KWJDGSbrUhTJyS1ph3eRrxI228ND0EGaEp6v4a/vGwPWd3Dtd/+9cI7ccofZvl/wulICEurHN1pg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js" integrity="sha512-+IpCthlNahOuERYUSnKFjzjdKXIbJ/7Dd6xvUp+7bEw0Jp2dg6tluyxLs+zq9BMzZgrLv8886T4cBSqnKiVgUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:description" content="<?php echo $description; ?>">
<?php $canonical = "https://www.$site" . str_replace("/index.php", "/", $_SERVER["SCRIPT_NAME"]); ?>
    <link rel="canonical" href="<?php echo $canonical; ?>">
    <meta property="og:url" content="<?php echo $canonical; ?>">
<?php if (isset($_GET["host"]) && $_GET["host"] != "") { ?>
    <meta name="robots" content="noindex,follow">
<?php } ?>
<?php if ($canonical == "https://www.$site/") { ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "<?php echo $site; ?> - Online IPv<?php echo PING_VERSION; ?> Ping Test",
      "url": "https://www.<?php echo $site; ?>/",
      "description": "<?php echo $description; ?>",
      "applicationCategory": "UtilitiesApplication",
      "operatingSystem": "Any",
      "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" }
    }
    </script>
<?php } ?>
  </head>
  <?php
}
?>
