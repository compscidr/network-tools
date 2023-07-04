# adds ping4 and ping6 to the php-fpm image so php has access to them
FROM php:8-fpm
RUN apt-get update && apt-get install -y \
  iputils-ping sqlite3
