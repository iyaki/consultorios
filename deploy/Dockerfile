# syntax=docker/dockerfile:1

FROM alpine:3 as base-alpine

WORKDIR /root

FROM ubuntu:jammy as base-ubuntu

ARG DEBIAN_FRONTEND=noninteractive

ENV TZ=America/Argentina/Buenos_Aires

ENV LANG C.UTF-8
ENV LC_ALL C.UTF-8

SHELL ["/bin/bash", "-c"]

WORKDIR /root

FROM base-alpine as asdf-vm-base

RUN apk add --no-cache git \
  && git clone https://github.com/asdf-vm/asdf.git ~/.asdf --branch v0.10.2 \
  && rm -rf \
    ~/.asdf/.editorconfig \
    ~/.asdf/.git \
    ~/.asdf/.gitattributes \
    ~/.asdf/.github \
    ~/.asdf/.gitignore \
    ~/.asdf/ballad-of-asdf.md \
    ~/.asdf/CHANGELOG.md \
    ~/.asdf/CONTRIBUTING \
    ~/.asdf/docs \
    ~/.asdf/LICENSE \
    ~/.asdf/lint.sh \
    ~/.asdf/README.md \
    ~/.asdf/release \
    ~/.asdf/repository \
    ~/.asdf/SECURITY.md \
    ~/.asdf/test \
    ~/.asdf/tmp/* \
    ~/.asdf/Vagrantfile \
    ~/.asdf/VERSION

FROM base-ubuntu as php-dependecies

RUN apt-get update \
  && apt-get install \
    --assume-yes \
    --quiet \
    --no-install-recommends \
    --purge \
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
  && rm -rf \
    /var/lib/apt/lists/* \
    /var/lib/dpkg/info/* \
    /var/lib/dpkg/status-old

FROM php-dependecies as php-build

ARG PHP_VERSION

COPY --from=asdf-vm-base /root/.asdf /root/.asdf

RUN apt-get update \
  && apt-get install \
    --assume-yes \
    --quiet \
    --no-install-recommends \
    --purge \
    ca-certificates \
    g++ \
    gcc \
    make \
  && . ~/.asdf/asdf.sh \
  && asdf plugin add php https://github.com/asdf-community/asdf-php.git \
  && asdf install php ${PHP_VERSION} \
  && cp -r ~/.asdf/installs/php/${PHP_VERSION} ~/php \
  && apt-get purge \
    --assume-yes \
    --quiet \
    autoconf \
    bison \
    g++ \
    gcc \
    make \
    pkg-config \
    re2c \
  && rm -rf \
    ~/.asdf \
    ~/php/bin/peardev \
    ~/php/bin/php-cgi \
    ~/php/bin/phpdbg \
    ~/php/php/* \
  && mkdir -p ~/.asdf/installs/php \
  && mv ~/php ~/.asdf/installs/php/${PHP_VERSION}

FROM php-dependecies as php-runtime

ARG PHP_VERSION

COPY --from=php-build /root/.asdf/installs/php/${PHP_VERSION} /root/.asdf/installs/php/${PHP_VERSION}

ENV PATH="/root/.asdf/installs/php/${PHP_VERSION}/bin:/root/.composer/vendor/bin:${PATH}"

# Set php.ini path
# ENV PHPRC="/root/.asdf/installs/php/${PHP_VERSION}/conf.d/php.ini" El auto_prepend_file del php.ini rompe rector

FROM base-alpine as php-phive

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

ARG PHP_VERSION

COPY --from=php-phive /root/phive.phar /root/.asdf/installs/php/${PHP_VERSION}/bin/phive

COPY ./deploy/dev/packages/lefthook_1.1.1_amd64.deb ./lefthook.deb

RUN apt-get update \
  && apt-get install \
    --assume-yes \
    --quiet \
    --no-install-recommends \
    --purge \
    ./lefthook.deb \
    ca-certificates \
    gnupg \
  && composer global require \
    --update-no-dev \
    --update-with-all-dependencies \
    --prefer-stable \
    --sort-packages \
    --classmap-authoritative \
    brianium/paratest \
    php-parallel-lint/php-console-highlighter \
    php-parallel-lint/php-var-dump-check \
    phparkitect/phparkitect \
    phpunit/phpunit ^9 \
    povils/phpmnd \
    psalm/plugin-phpunit \
    symplify/easy-ci \
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
  && apt-get purge \
    --assume-yes \
    --quiet \
    alsa-topology-conf \
    alsa-ucm-conf \
    fontconfig-config \
    fonts-dejavu-core \
    gnupg \
    manpages \
    manpages-dev \
  && apt-get autoremove \
    --assume-yes \
    --quiet \
    --purge \
  && rm -rf \
    ./lefthook.deb \
    /tmp/* \
    /root/.asdf/installs/php/${PHP_VERSION}/bin/phive \
    /var/lib/apt/lists/* \
    /var/lib/dpkg/info/* \
    /var/lib/dpkg/status-old \
    ~/php/php-fpm.d/* \
    ~/php/sbin/php-fpm

FROM base-ubuntu as node-build

ARG NODE_VERSION=14.18.1

COPY --from=asdf-vm-base /root/.asdf /root/.asdf

RUN apt-get update \
  && apt-get install \
    --assume-yes \
    --reinstall \
    --quiet \
    --no-install-recommends \
    --purge \
    ca-certificates \
    curl \
    git \
    gnupg \
  && . ~/.asdf/asdf.sh \
  && asdf plugin-add nodejs https://github.com/asdf-vm/asdf-nodejs.git \
  && asdf install nodejs ${NODE_VERSION} \
  && cp -r ~/.asdf/installs/nodejs/${NODE_VERSION} ~/nodejs \
  && apt-get purge \
    --assume-yes \
    --quiet \
    alsa-topology-conf \
    alsa-ucm-conf \
    fontconfig-config \
    fonts-dejavu-core \
    gnupg \
    manpages \
    manpages-dev \
  && apt-get autoremove \
    --assume-yes \
    --quiet \
    --purge \
  && rm -rf \
    ~/.asdf \
    ~/nodejs/lib/node_modules/npm/.licensee.json \
    ~/nodejs/lib/node_modules/npm/.mailmap \
    ~/nodejs/lib/node_modules/npm/.npmignore \
    ~/nodejs/lib/node_modules/npm/.npmrc \
    ~/nodejs/lib/node_modules/npm/.travis.yml \
    ~/nodejs/lib/node_modules/npm/AUTHORS \
    ~/nodejs/lib/node_modules/npm/CHANGELOG.md \
    ~/nodejs/lib/node_modules/npm/changelogs/* \
    ~/nodejs/lib/node_modules/npm/configure \
    ~/nodejs/lib/node_modules/npm/CONTRIBUTING.md \
    ~/nodejs/lib/node_modules/npm/docs/* \
    ~/nodejs/lib/node_modules/npm/LICENSE \
    ~/nodejs/lib/node_modules/npm/make.bat \
    ~/nodejs/lib/node_modules/npm/Makefile \
    ~/nodejs/lib/node_modules/npm/man/* \
    ~/nodejs/lib/node_modules/npm/README.md \
    ~/nodejs/lib/node_modules/npm/scripts/* \
    ~/nodejs/lib/node_modules/npm/tap-snapshots/* \
    ~/nodejs/LICENSE \
    ~/nodejs/README.md

FROM node-build as node-runtime

LABEL org.opencontainers.image.source="https://github.com/iyaki/consultorios"

ENV PATH="/root/nodejs/bin:${PATH}"

FROM php-ci as dev-env

LABEL org.opencontainers.image.source="https://github.com/iyaki/consultorios"

ARG PHP_VERSION

COPY --from=node-build /root/nodejs /root/nodejs

ENV PATH="/root/nodejs/bin:${PATH}"

COPY --from=php-phive /root/phive.phar /root/.asdf/installs/php/${PHP_VERSION}/bin/phive

COPY ./deploy/dev/packages/git-delta_0.13.0_amd64.deb ./git-delta.deb

RUN apt-get update \
  && apt-get upgrade \
    --assume-yes \
    --quiet \
    --no-install-recommends \
    --purge \
  && apt-get install \
    --assume-yes \
    --quiet \
    --no-install-recommends \
    --purge \
    ./git-delta.deb \
    bash-completion \
    bat \
    git \
    gnupg \
    mariadb-client \
    openssh-client \
    shellcheck \
    vim \
# More utilities
  && composer global require \
    --update-no-dev \
    --update-with-all-dependencies \
    --prefer-stable \
    --sort-packages \
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
  && pecl install xdebug \
  && bash <(curl https://raw.githubusercontent.com/rhysd/actionlint/main/scripts/download-actionlint.bash) \
  && mv actionlint /usr/local/bin/actionlint \
  && npm install --global markdownlint-cli2@^0.5.1 \
  && apt-get update \
  && apt-get autoremove \
    --assume-yes \
    --quiet \
    --purge \
  && rm -rf \
    /tmp/* \
    /usr/local/bin/phive \
    /root/.phive \
    /usr/share/vim/vim81/doc/* \
    /var/cache/debconf/config.dat-old \
    /var/cache/debconf/templates.dat-old \
    /var/lib/apt/lists/* \
    /var/lib/dpkg/info/* \
    /usr/lib/python3.8 \
    /usr/share/doc/* \
    /usr/share/fonts/* \
    /usr/share/X11 \
    /usr/share/alsa \
    /usr/share/sounds \
    /var/lib/dpkg/status-old \
    ./git-delta.deb \
  # Bash completions for composer
  && composer completion bash > composer_completion.bash \
  && mv composer_completion.bash /etc/bash_completion.d/composer \
  # Bash completions for lefthook
  && lefthook completion bash > lefthook_completion.bash \
  && mv lefthook_completion.bash /etc/bash_completion.d/lefthook

COPY ./deploy/dev/bin/jq /usr/local/bin/jq
COPY ./deploy/dev/bin/hadolint /usr/local/bin/hadolint

# Path para ejecutar binarios por proyecto
ENV PATH="./node_modules/.bin:${PATH}"
ENV PATH="./vendor/bin:${PATH}"
ENV PATH="./scripts:${PATH}"

ENTRYPOINT ["tail", "-f", "/dev/null"]
