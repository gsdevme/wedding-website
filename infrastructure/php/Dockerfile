FROM php:8.0-fpm-buster as base

COPY --from=composer /usr/bin/composer /usr/bin/composer

EXPOSE 9000
RUN mkdir -p /srv/app/var /srv/app/vendor && chown -R www-data:www-data /srv

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
    && apt-get update && apt-get install -y $buildDeps --no-install-recommends && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) intl opcache

FROM base as dev

# nothing

FROM base as build

COPY ./composer.json /srv/app/composer.json
COPY ./composer.lock /srv/app/composer.lock
COPY ./symfony.lock /srv/app/symfony.lock

RUN composer install --no-dev -n --no-suggest -q --no-scripts

FROM base as prod

ENV APP_ENV=prod

COPY ./src/ /srv/app/src
COPY ./bin/ /srv/app/bin
COPY ./public/ /srv/app/public
COPY ./vendor/ /srv/app/vendor
COPY ./config/ /srv/app/config
COPY ./templates/ /srv/app/templates

COPY --from=build /srv/app/vendor /srv/app/vendor
COPY --from=build /srv/app/composer.json /srv/app/composer.json
COPY --from=build /srv/app/composer.lock /srv/app/composer.lock

COPY .env /srv/app/.env

RUN chown -R www-data /srv/app/ && \
   composer dump-autoload -n
