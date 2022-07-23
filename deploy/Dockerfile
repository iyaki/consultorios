# syntax=docker/dockerfile:1
FROM alpine:latest as asdf-vm-base

RUN apk add --no-cache git \
  && git clone https://github.com/asdf-vm/asdf.git ~/.asdf --branch v0.10.2 \
  && rm -f ~/.asdf/.editorconfig \
  && rm -rf ~/.asdf/.git \
  && rm -f ~/.asdf/.gitattributes \
  && rm -rf ~/.asdf/.github \
  && rm -f ~/.asdf/.gitignore \
  && rm -f ~/.asdf/ballad-of-asdf.md \
  && rm -f ~/.asdf/CHANGELOG.md \
  && rm -f ~/.asdf/CONTRIBUTING \
  && rm -rf ~/.asdf/docs \
  && rm -f ~/.asdf/LICENSE \
  && rm -f ~/.asdf/lint.sh \
  && rm -f ~/.asdf/README.md \
  && rm -rf ~/.asdf/release \
  && rm -rf ~/.asdf/repository \
  && rm -f ~/.asdf/SECURITY.md \
  && rm -rf ~/.asdf/test \
  && rm -rf ~/.asdf/tmp/* \
  && rm -f ~/.asdf/Vagrantfile \
  && rm -f ~/.asdf/VERSION

FROM ubuntu:latest as php-dependecies

ARG DEBIAN_FRONTEND=noninteractive

ENV TZ=America/Argentina/Buenos_Aires

RUN apt update \
  && apt install -y \
    autoconf \
    bison \
    build-essential \
    curl \
    gettext \
    git \
    libcurl4-openssl-dev \
    libedit-dev \
    libgd-dev \
    libicu-dev \
    libjpeg-dev \
    libmysqlclient-dev \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libreadline-dev \
    libsqlite3-dev \
    libssl-dev \
    libxml2-dev \
    libzip-dev \
    openssl \
    pkg-config \
    re2c \
    unzip \
    zlib1g-dev \
  && rm -rf /var/lib/apt/lists/* \
  && rm -rf /var/lib/dpkg/info/* \
  && rm -rf /var/lib/dpkg/status-old

FROM php-dependecies as php-build

ARG PHP_VERSION=8.1.8

COPY --from=asdf-vm-base /root/.asdf /root/.asdf

SHELL ["/bin/bash", "-c"]

RUN apt update \
  && apt install -y \
    git \
    autoconf \
    bison \
    g++ \
    gcc \
    make \
    re2c \
  && source ~/.asdf/asdf.sh \
  && asdf plugin add php https://github.com/asdf-community/asdf-php.git \
  && asdf install php ${PHP_VERSION} \
  && cp -r ~/.asdf/installs/php/${PHP_VERSION} ~/php \
  && rm -rf ~/.asdf \
  && rm -rf ~/php/bin/peardev \
  && rm -rf ~/php/bin/php-cgi \
  && rm -rf ~/php/bin/phpdbg \
  && rm -rf ~/php/lib/* \
  && rm -rf ~/php/php/*

FROM php-dependecies as php-runtime

COPY --from=php-build /root/php /root/php

ENV PATH="/root/php/bin:/root/.config/composer/vendor/bin:${PATH}"

FROM alpine:latest as php-phive

WORKDIR /root

RUN apk add --no-cache \
    curl \
    gnupg \
  && curl -Lo phive.phar https://phar.io/releases/phive.phar \
  && curl -Lo phive.phar.asc https://phar.io/releases/phive.phar.asc \
  && gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79 \
  && gpg --verify phive.phar.asc phive.phar \
  && chmod +x phive.phar

FROM php-runtime as php-ci

LABEL org.opencontainers.image.source="https://github.com/iyaki/consultorios"

COPY --from=php-phive /root/phive.phar /root/php/bin/phive

RUN apt update \
  && apt install -y gnupg \
  && composer global require \
    --update-no-dev \
    --update-with-all-dependencies \
    --prefer-stable \
    --sort-packages \
    --optimize-autoloader \
    --classmap-authoritative \
    povils/phpmnd \
    psalm/plugin-phpunit \
    symplify/easy-coding-standard \
    vimeo/psalm \
# Even more utilities
#
# Gpg keys:
# composer-require-checker: 033E5F8D801A2F8D
# composer-unused: F4D32E2C9343B2AE
# phpcpd: 4AA394086372C20A
  && phive install \
    --global \
    --trust-gpg-keys 033E5F8D801A2F8D,F4D32E2C9343B2AE,4AA394086372C20A \
    composer-require-checker \
    composer-unused \
    phpcpd \
  && apt purge -y \
    gnupg \
  && apt autoremove -y \
  && rm -rf /tmp/* \
  && rm -rf /root/php/bin/phive \
  && rm -rf /var/lib/apt/lists/* \
  && rm -rf /var/lib/dpkg/info/* \
  && rm -rf /var/lib/dpkg/status-old \
  && rm -rf ~/php/php-fpm.d/* \
  && rm -rf ~/php/sbin/php-fpm

FROM ubuntu:latest as node-build

ARG NODE_VERSION=14.18.1

COPY --from=asdf-vm-base /root/.asdf /root/.asdf

ENV PATH="/root/nodejs/bin:${PATH}"

SHELL ["/bin/bash", "-c"]

RUN apt update \
  && apt install -y \
    curl \
    git \
    gnupg \
  && source ~/.asdf/asdf.sh \
  && asdf plugin-add nodejs https://github.com/asdf-vm/asdf-nodejs.git \
  && asdf install nodejs ${NODE_VERSION} \
  && cp -r ~/.asdf/installs/nodejs/${NODE_VERSION} ~/nodejs \
  && rm -rf ~/.asdf \
  && rm -r ~/nodejs/lib/node_modules/npm/.licensee.json \
  && rm -rf ~/nodejs/lib/node_modules/npm/.mailmap \
  && rm -rf ~/nodejs/lib/node_modules/npm/.npmignore \
  && rm -rf ~/nodejs/lib/node_modules/npm/.npmrc \
  && rm -r ~/nodejs/lib/node_modules/npm/.travis.yml \
  && rm -rf ~/nodejs/lib/node_modules/npm/AUTHORS \
  && rm -r ~/nodejs/lib/node_modules/npm/CHANGELOG.md \
  && rm -rf ~/nodejs/lib/node_modules/npm/changelogs/* \
  && rm -rf ~/nodejs/lib/node_modules/npm/configure \
  && rm -r ~/nodejs/lib/node_modules/npm/CONTRIBUTING.md \
  && rm -rf ~/nodejs/lib/node_modules/npm/docs/* \
  && rm -r ~/nodejs/lib/node_modules/npm/LICENSE \
  && rm -rf ~/nodejs/lib/node_modules/npm/make.bat \
  && rm -r ~/nodejs/lib/node_modules/npm/Makefile \
  && rm -rf ~/nodejs/lib/node_modules/npm/man/* \
  && rm -r ~/nodejs/lib/node_modules/npm/package.json \
  && rm -r ~/nodejs/lib/node_modules/npm/README.md \
  && rm -rf ~/nodejs/lib/node_modules/npm/scripts/* \
  && rm -rf ~/nodejs/lib/node_modules/npm/tap-snapshots/* \
  && rm -r ~/nodejs/LICENSE \
  && rm -r ~/nodejs/README.md

FROM php-ci as dev-env

LABEL org.opencontainers.image.source="https://github.com/iyaki/consultorios"

ENV LANG C.UTF-8
ENV LC_ALL C.UTF-8

COPY --from=php-phive /root/phive.phar /root/php/bin/phive

RUN apt update \
  && apt install -y \
    bash-completion \
    git \
    gnupg \
    mariadb-client \
    openssh-client \
    vim \
# More utilities
  && composer global require \
    --update-no-dev \
    --update-with-all-dependencies \
    --prefer-stable \
    --sort-packages \
    --optimize-autoloader \
    --classmap-authoritative \
    phpmetrics/phpmetrics \
    psy/psysh:@stable \
    rector/rector \
    symfony/var-dumper \
# Even more utilities
#
# Gpg keys:
# churn-php: 96141E4421A9B0D5
# phploc: 4AA394086372C20A
  && phive install \
    --global \
    --trust-gpg-keys 96141E4421A9B0D5,4AA394086372C20A \
    churn \
    phploc \
# Cleanup
  && apt purge -y \
    alsa-topology-conf \
    alsa-ucm-conf \
    fontconfig-config \
    fonts-dejavu-core \
    gpg \
    gpg-agent \
    manpages \
    manpages-dev \
  && apt update \
  && apt autoremove -y \
  && rm -rf /tmp/* \
  && rm -rf /usr/local/bin/phive \
  && rm -rf /root/.phive \
  && rm -rf /usr/share/vim/vim81/doc/* \
  && rm -rf /var/cache/debconf/config.dat-old \
  && rm -rf /var/cache/debconf/templates.dat-old \
  && rm -rf /var/lib/apt/lists/* \
  && rm -rf /var/lib/dpkg/info/* \
  && rm -rf /usr/lib/python3.8 \
  && rm -rf /usr/share/doc/* \
  && rm -rf /usr/share/fonts/* \
  && rm -rf /usr/share/X11 \
  && rm -rf /usr/share/alsa \
  && rm -rf /usr/share/sounds \
  && rm -rf /var/lib/dpkg/status-old

  COPY --from=node-build /root/nodejs /root/nodejs

  ENV PATH="/root/nodejs/bin:${PATH}"

ENTRYPOINT ["tail", "-f", "/dev/null"]
