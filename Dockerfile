ARG COMPOSER_VERSION=latest

###########################################
# PHP package dependencies
###########################################
FROM composer:${COMPOSER_VERSION} AS vendor
WORKDIR /var/www/html
COPY composer* ./
RUN composer install \
#  --no-dev \
  --no-interaction --prefer-dist --ignore-platform-reqs \
  --optimize-autoloader --apcu-autoloader --ansi --no-scripts

###########################################
FROM openswoole/swoole:latest-alpine

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Create a group and user
RUN addgroup -S octane && adduser -S octane -G octane

COPY . .
COPY --from=vendor /var/www/html/vendor vendor
COPY docker/start.sh /usr/local/bin/start

RUN chmod u+x /usr/local/bin/start

RUN mkdir -p \
  storage/framework/{sessions,views,cache} \
  storage/logs \
  bootstrap/cache \
  && chown -R octane:octane \
  storage \
  bootstrap/cache \
  && chmod -R ug+rwx storage bootstrap/cache

# Artisan Commands
RUN php artisan optimize:clear; \
  php artisan config:clear; \
  php artisan view:clear; \
  php artisan package:discover --ansi; \
  php artisan event:cache; \
  php artisan config:cache; \
  php artisan view:cache; \
  php artisan route:cache;

CMD ["/usr/local/bin/start"]

EXPOSE 8080

HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD php artisan octane:status || exit 1