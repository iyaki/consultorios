FROM php:8.0.10-fpm-alpine

RUN apk add --update --no-cache --purge icu-dev \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install intl \
# Install composer
  && EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')" \
  && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" \
  && if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then >&2 echo 'ERROR: Invalid installer checksum' && exit; fi \
  && php composer-setup.php \
  && rm composer-setup.php \
# Installs dev dependencies
  && ./composer.phar global require \
    --update-no-dev \
    --prefer-stable \
    --sort-packages \
    --optimize-autoloader \
    --classmap-authoritative \
    symfony/var-dumper \
  && mv /root/.composer /opt/composer \
  && chmod -R 0755 /opt/composer \
# Remove build dependendencies
  && rm composer.phar
