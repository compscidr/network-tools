# network-tools
This is a collection of simple network and network related tools that are
deployed in an online fashion to make it easy for people to interact with them.

- ping4
- ping6
- hexdump
- packetdump

Eventually I'll build apis attached to them so people can use them directly for
money. At the start, I'll see if I can make it work with advertisements.

## Local dev on docker
```
docker-compose up
```

### Migrations
```
docker exec -u www-data:www-data -it network-tools_php-fpm-ping_1 php ping4/migrate.php
docker run -it --rm -p 8080:8080 -v ./data:/data -e SQLITE_DATABASE="ping4.db" coleifer/sqlite-web
```

## Getting started on a fresh server
```
apt update
apt upgrade
apt install nginx certbot python3-certbot-nginx php php-cli php-fpm php-json php-mysql php-zip php-gd  php-mbstring php-curl php-xml php-pear php-bcmath
systemctl disable --now apache2
```

```
nano /etc/nginx/sites-enabled/default
```

Add `index.php`
Add
```
location ~ \.php$ {
  include snippets/fastcgi-php.conf;
  fastcgi_pass unix:/run/php/php-fpm.sock;
}
```

```
systemctl restart nginx
```

```
certbot --nginx -d <domain> -d www.<domain>
```

### Create / Migrate db
```
php /var/www/html/migrate.php
chown www-data:www-data /var/www/ping4.db
```
