FROM php:8.0.10-fpm-alpine

RUN apk add --update --no-cache --purge icu-dev \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install intl
