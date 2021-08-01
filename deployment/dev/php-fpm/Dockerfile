FROM phpdockerio/php80-fpm:latest
WORKDIR "/application/server"

# Add `vendor/bin` to $PATH
ENV PATH /application/server/vendor/bin:$PATH

# Add `tools` to $PATH
ENV PATH /application/server/tools:$PATH

# Install selected extensions and other stuff
RUN apt-get update && \
    apt-get -y --no-install-recommends install php8.0-mysql php8.0-xdebug php8.0-intl && \
    apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update && \
    apt-get -y install git && \
    apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install phive
# RUN curl -Lo phive.phar https://phar.io/releases/phive.phar && \
#     chmod +x phive.phar && \
#     mv phive.phar /usr/local/bin/phive

# Add application
COPY ./server/ ./

# Install dependencies
RUN composer install
#    phive install --copy --trust-gpg-keys 4F2AB4D11A9A65F7
