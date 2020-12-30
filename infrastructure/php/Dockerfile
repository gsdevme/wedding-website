FROM php:8.0-fpm-buster as base

COPY --from=composer /usr/bin/composer /usr/bin/composer

EXPOSE 9000
RUN mkdir -p /srv/app/var /srv/app/vendor && chown -R www-data:www-data /srv

COPY ./infrastructure/php/entrypoint.sh /usr/local/bin/docker-php-entrypoint

WORKDIR /srv/app/

# Install dependencies
RUN buildDeps=" \
        wget \
        git \
        less \
        zip \
        unzip \
        libicu-dev \
    "; \
    set -x \
    && apt-get update && apt-get install -y $buildDeps --no-install-recommends && rm -rf /var/lib/apt/lists/* && chmod +x /usr/local/bin/docker-php-entrypoint

RUN docker-php-ext-install -j$(nproc) intl opcache pdo_mysql

COPY ./infrastructure/php/php.ini /usr/local/etc/php/php.ini

FROM base as dev

COPY ./infrastructure/php/php-dev.ini /usr/local/etc/php/php.ini

USER www-data

FROM base as build

COPY ./composer.json /srv/app/composer.json
COPY ./composer.lock /srv/app/composer.lock
COPY ./symfony.lock /srv/app/symfony.lock
COPY ./.env /srv/app/.env
COPY ./src/ /srv/app/src
COPY ./bin/ /srv/app/bin
COPY ./public/ /srv/app/public
COPY ./config/ /srv/app/config
COPY ./templates/ /srv/app/templates

RUN composer install -n --no-suggest -q --no-scripts

FROM build as ci

COPY ./Makefile /srv/app/Makefile

RUN make run-ci

FROM base as prod

ENV APP_ENV=prod
ENV APP_DEBUG=0

COPY --from=build /srv/app/ /srv/app/

RUN chown -R www-data:www-data /srv/app/ && \
   composer install --no-dev -n --no-suggest -q --no-scripts && \
   composer dump-autoload --no-dev --classmap-authoritative -n

USER www-data
