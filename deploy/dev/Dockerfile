FROM ubuntu:latest

SHELL ["/bin/bash", "-c"]

ARG DEBIAN_FRONTEND=noninteractive

ENV TZ=America/Argentina/Buenos_Aires

ENV LANG es_AR.UTF-8
ENV LC_ALL es_AR.UTF-8

RUN apt update \
  && apt install -y \
    bash-completion \
    curl \
    git \
    gnupg \
    mariadb-client \
    openssh-client \
    unzip \
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
# churn-php: 69132CC8829FAB5F
# composer-require-checker: 033E5F8D801A2F8D
# composer-unused: F4D32E2C9343B2AE
# phploc, phpcpd: 4AA394086372C20A
  && phive install \
    --global \
    --trust-gpg-keys 69132CC8829FAB5F,033E5F8D801A2F8D,F4D32E2C9343B2AE,4AA394086372C20A \
    churn \
    composer-require-checker \
    composer-unused \
    phpcpd \
    phploc \
# Cleanup
  && apt remove -y \
    autoconf \
    bison \
    g++ \
    gcc \
    make \
    re2c \
  && apt autoremove -y \
  && rm -rf /tmp/* \
  && rm -rf /usr/local/bin/phive \
  && rm -rf /usr/share/vim/vim81/doc/* \
  && rm -rf /var/cache/debconf/config.dat-old \
  && rm -rf /var/cache/debconf/templates.dat-old \
  && rm -rf /var/lib/apt/lists/* \
  && rm -rf /var/lib/dpkg/info/* \
  && rm -rf /var/lib/dpkg/status-old \
  && rm -rf ~/.asdf/.editorconfig \
  && rm -rf ~/.asdf/.git \
  && rm -rf ~/.asdf/.gitattributes \
  && rm -rf ~/.asdf/.github \
  && rm -rf ~/.asdf/.gitignore \
  && rm -rf ~/.asdf/ballad-of-asdf.md \
  && rm -rf ~/.asdf/CHANGELOG.md \
  && rm -rf ~/.asdf/CONTRIBUTING \
  && rm -rf ~/.asdf/docs \
  && rm -rf ~/.asdf/downloads/* \
  && rm -rf ~/.asdf/installs/nodejs/lts/CHANGELOG.md \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/.licensee.json \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/.mailmap \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/.npmignore \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/.npmrc \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/.travis.yml \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/AUTHORS \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/CHANGELOG.md \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/changelogs/* \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/configure \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/CONTRIBUTING.md \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/docs/* \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/LICENSE \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/make.bat \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/Makefile \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/man/* \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/package.json \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/README.md \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/scripts/* \
  && rm -rf ~/.asdf/installs/nodejs/lts/lib/node_modules/npm/tap-snapshots/* \
  && rm -rf ~/.asdf/installs/nodejs/lts/LICENSE \
  && rm -rf ~/.asdf/installs/nodejs/lts/README.md \
  && rm -rf ~/.asdf/installs/php/8.0.11/bin/peardev \
  && rm -rf ~/.asdf/installs/php/8.0.11/bin/php-cgi \
  && rm -rf ~/.asdf/installs/php/8.0.11/bin/phpdbg \
  && rm -rf ~/.asdf/installs/php/8.0.11/php-fpm.d/* \
  && rm -rf ~/.asdf/installs/php/8.0.11/php/* \
  && rm -rf ~/.asdf/installs/php/8.0.11/sbin/php-fpm \
  && rm -rf ~/.asdf/LICENSE \
  && rm -rf ~/.asdf/lint.sh \
  && rm -rf ~/.asdf/README.md \
  && rm -rf ~/.asdf/release/* \
  && rm -rf ~/.asdf/repository \
  && rm -rf ~/.asdf/SECURITY.md \
  && rm -rf ~/.asdf/test \
  && rm -rf ~/.asdf/tmp/* \
  && rm -rf ~/.asdf/Vagrantfile \
  && rm -rf ~/.asdf/VERSION \
  && asdf reshim

ENTRYPOINT ["tail", "-f", "/dev/null"]
