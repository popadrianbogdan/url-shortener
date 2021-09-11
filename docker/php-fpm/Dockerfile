FROM php:7.2-fpm

# NodeJS Stuff
RUN apt-get update && apt-get install -y software-properties-common gnupg libgmp-dev \
    && curl -sL https://deb.nodesource.com/setup_8.x | bash \
    && apt-get install -y nodejs \
    && rm -rf /usr/local/lib/node_modules \
    && ln -s /usr/share/npm/node_modules /usr/local/lib/node_modules

#YARN
# Install yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn
RUN yarn --version

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer

RUN apt-get update
RUN apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql \
    && docker-php-ext-install zip xml
RUN pecl install redis && docker-php-ext-enable redis
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY php-ini-overrides.ini /usr/local/etc/php/conf.d/php-ini-overrides.ini


WORKDIR /app
