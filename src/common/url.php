<?php
/** Scheme + host + directory of the current request, e.g. https://www.ping4.network/ */
function reconstruct_url(): string
{
  $https = ($_SERVER['HTTPS'] ?? 'off') === 'on' || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
  $dir = rtrim(dirname(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/') . '/';
  return ($https ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $dir;
}
