# =============================================================================
# Multi-stage Dockerfile for Laravel Applications
# Optimized for security, performance, and best practices
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 1: Base Image with PHP Extensions
# -----------------------------------------------------------------------------
ARG PHP_VERSION=8.2
FROM php:${PHP_VERSION}-fpm-alpine AS base

LABEL maintainer="your-email@example.com"
LABEL description="Production-ready Laravel PHP-FPM container"

# Set timezone
ENV TZ=UTC \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    # Build dependencies (will be removed later)
    $PHPIZE_DEPS \
    # Runtime dependencies
    bash \
    curl \
    freetype-dev \
    icu-dev \
    jpeg-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    zip \
    unzip \
    # For health checks
    fcgi \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        xml \
        zip \
    # Install Redis extension
    && pecl install redis \
    && docker-php-ext-enable redis \
    # Cleanup
    && apk del $PHPIZE_DEPS \
    && rm -rf /tmp/* /var/cache/apk/*

# Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create application user and group
ARG UID=1000
ARG GID=1000
RUN addgroup -g ${GID} -S appuser \
    && adduser -u ${UID} -S appuser -G appuser -h /home/appuser -s /bin/bash

# Set working directory
WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# Stage 2: Development Dependencies
# -----------------------------------------------------------------------------
FROM base AS development

# Copy PHP configuration for development
COPY docker/laravel/php/dev/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/laravel/php/dev/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/laravel/php-fpm/dev/www.conf /usr/local/etc/php-fpm.d/www.conf

# Install development tools
RUN apk add --no-cache \
    git \
    vim \
    mysql-client \
    postgresql-client

# Copy application files
COPY --chown=appuser:appuser . .

# Install composer dependencies (including dev)
RUN composer install \
    --optimize-autoloader \
    --no-scripts \
    && composer clear-cache

# Set proper permissions
RUN chown -R appuser:appuser /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Health check script
COPY docker/laravel/healthcheck.sh /usr/local/bin/healthcheck
RUN chmod +x /usr/local/bin/healthcheck

USER appuser

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD /usr/local/bin/healthcheck

CMD ["php-fpm"]

# -----------------------------------------------------------------------------
# Stage 3: Production Builder
# -----------------------------------------------------------------------------
FROM base AS builder

# Copy application files
COPY --chown=appuser:appuser . .

# Install production dependencies only
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts \
    --prefer-dist \
    && composer clear-cache \
    && rm -rf /usr/bin/composer

# -----------------------------------------------------------------------------
# Stage 4: Production Image
# -----------------------------------------------------------------------------
FROM base AS production

# Copy PHP configuration for production
COPY docker/laravel/php/prod/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/laravel/php/prod/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/laravel/php-fpm/prod/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copy application from builder
COPY --from=builder --chown=appuser:appuser /var/www/html /var/www/html

# Remove composer from production
RUN rm -f /usr/bin/composer

# Set proper permissions
RUN chown -R appuser:appuser /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    # Remove sensitive files
    && rm -f /var/www/html/.env.example \
    && rm -f /var/www/html/phpunit.xml \
    && rm -rf /var/www/html/tests

# Health check script
COPY docker/laravel/healthcheck.sh /usr/local/bin/healthcheck
RUN chmod +x /usr/local/bin/healthcheck

USER appuser

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD /usr/local/bin/healthcheck

CMD ["php-fpm"]
