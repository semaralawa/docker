# Dockerfile
ARG PHP_VERSION=7.4
FROM php:${PHP_VERSION}-fpm

LABEL maintainer="you@example.com"
ENV TZ=UTC

# ---- System deps ----
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    curl \
    gnupg2 \
    default-mysql-client \
  && rm -rf /var/lib/apt/lists/*

# ---- PHP extensions ----
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    xml

# ---- Redis (phpredis) ----
RUN pecl install redis \
  && docker-php-ext-enable redis

# ---- Install composer (use official composer image binary) ----
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ---- Create non-root user ----
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} laravel \
  && useradd -u ${UID} -g laravel -m -s /bin/bash laravel

  
# ---- Copy app source ----
COPY . /var/www

WORKDIR /var/www

# ---- Composer install stage to leverage layer cache (dev-friendly) ----
# COPY composer.json composer.lock /var/www/
RUN composer install --no-autoloader --no-scripts --no-progress \
  --prefer-dist --no-interaction \
  && rm -rf /root/.composer/cache
  
# ---- Set permissions ----
RUN chown -R laravel:laravel /var/www

# ---- Dev-specific: disable opcache for fast reloads ----
RUN { \
    echo '[opcache]'; \
    echo 'opcache.enable=0'; \
    echo 'opcache.enable_cli=0'; \
  } > /usr/local/etc/php/conf.d/docker-php-opcache.ini

# ---- PHP settings for dev ----
RUN { \
    echo 'memory_limit=512M'; \
    echo 'display_errors=1'; \
    echo 'error_reporting=E_ALL'; \
  } > /usr/local/etc/php/conf.d/docker-php-dev.ini

# Ensure php-fpm uses the TCP listen (matching www.conf)
EXPOSE 9000

USER laravel

CMD ["php-fpm"]