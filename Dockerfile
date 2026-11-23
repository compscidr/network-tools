# adds ping4 and ping6 to the php-fpm image so php has access to them
FROM php:8.6-fpm
# the official image ships no php.ini; without one PHP runs on dev defaults (CVE-2026-129474)
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN apt-get update && apt-get install -y \
  iputils-ping sqlite3
# /data is a bind mount that docker creates as root; php-fpm workers run as www-data
CMD ["sh", "-c", "mkdir -p /data && chown -R www-data:www-data /data && exec php-fpm"]
