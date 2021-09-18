FROM alpine:3.14.2
WORKDIR "/"

# Install dependencies, tools and configure environment
RUN apk add --update --no-cache --purge \
    bash \
    bash-completion \
    curl \
    git \
    gnupg \
    htop \
    mariadb-client \
    nginx \
    nodejs \
    npm \
    openssh \
    php8 \
    php8-ctype \
    php8-curl \
    php8-dom \
    php8-fpm \
    php8-iconv \
    php8-intl \
    php8-mbstring \
    php8-openssl \
    php8-pdo \
    php8-pdo_mysql \
    php8-phar \
    php8-simplexml \
    php8-tokenizer \
    php8-xdebug \
    php8-xml \
    php8-xmlwriter \
    supervisor \
    the_silver_searcher \
    vim \
# Alias PHP
  && ln -s /usr/bin/php8 /usr/bin/php \
# Set bash as default shell
  && sed -i 's#root:x:0:0:root:/root:/bin/ash#root:x:0:0:root:/root:/bin/bash#g' /etc/passwd \
# Install composer
  && EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')" \
  && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" \
  && if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then >&2 echo 'ERROR: Invalid installer checksum' && exit; fi \
  && php composer-setup.php \
  && rm composer-setup.php \
  && mv composer.phar /usr/local/bin/composer \
# More utilities
  && composer global require \
    psy/psysh:@stable \
    phpmetrics/phpmetrics \
    povils/phpmnd \
    jakub-onderka/php-parallel-lint \
    jakub-onderka/php-console-highlighter \
    maglnet/composer-require-checker \
# Install phive
  && wget -O phive.phar https://phar.io/releases/phive.phar \
  && wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc \
  && gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79 \
  && gpg --verify phive.phar.asc phive.phar \
  && chmod +x phive.phar \
  && mv phive.phar /usr/local/bin/phive \
  && rm -f phive.phar.asc \
# Even more utilities
#
# Gpg keys:
# phploc, phpcpd: 4AA394086372C20A
# dephpend: 76835C9464877BDD
# phpmd: 0F9684B8B16B7AB0
# churn-php: 69132CC8829FAB5F
# composer-unused: F4D32E2C9343B2AE
  && phive install \
    --global \
    --trust-gpg-keys 4AA394086372C20A,76835C9464877BDD,0F9684B8B16B7AB0,69132CC8829FAB5F,F4D32E2C9343B2AE \
    phploc \
    phpcpd \
    phpmd \
    churn \
    composer-unused

ENV PATH /application/server/vendor/bin:/application/server/tools:/application/client/node_modules/.bin:/root/.composer/vendor/bin:$PATH

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
