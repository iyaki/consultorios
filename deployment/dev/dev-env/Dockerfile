FROM ubuntu:latest

SHELL ["/bin/bash", "-c"]

ARG DEBIAN_FRONTEND=noninteractive

ENV TZ=America/Argentina/Buenos_Aires

RUN apt update \
  && apt install -y \
    bash-completion \
    curl \
    git \
    gnupg \
    mariadb-client \
    openssh-client \
    vim \
    wget \
# Install asdf version manager
  && git clone https://github.com/asdf-vm/asdf.git ~/.asdf --branch v0.8.1 \
  && source ~/.asdf/asdf.sh \
# Install php
  && apt install -y \
    autoconf \
    bison \
    g++ \
    gcc \
    libcurl4-openssl-dev \
    libgd-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libreadline-dev \
    libsqlite3-dev \
    libssl-dev \
    libxml2-dev \
    libzip-dev \
    make \
    re2c \
    zlib1g-dev \
  && asdf plugin add php \
  && asdf install php 8.0.11 \
  && asdf global php 8.0.11 \
# Install nodejs
  && asdf plugin-add nodejs https://github.com/asdf-vm/asdf-nodejs.git \
  && bash -c '${ASDF_DATA_DIR:=$HOME/.asdf}/plugins/nodejs/bin/import-release-team-keyring' \
  && asdf install nodejs lts \
  && asdf global nodejs lts \
# More utilities
  && composer global require \
    --update-no-dev \
    --update-with-all-dependencies \
    --prefer-stable \
    --sort-packages \
    --optimize-autoloader \
    --classmap-authoritative \
    maglnet/composer-require-checker \
    povils/phpmnd \
    phpmetrics/phpmetrics \
    psalm/plugin-phpunit \
    psy/psysh:@stable \
    rector/rector \
    symfony/var-dumper \
    symplify/easy-coding-standard \
    vimeo/psalm \
  && asdf reshim \
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
    churn \
    composer-unused \
    phpcpd \
    phploc \
    phpmd \
# Cleanup
  && apt remove -y \
    autoconf \
    bison \
    g++ \
    gcc \
    make \
    re2c \
  && rm -rf /var/lib/apt/lists/*

ENTRYPOINT ["tail", "-f", "/dev/null"]
