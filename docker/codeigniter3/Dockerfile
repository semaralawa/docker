# =============================================================================
# Multi-stage Dockerfile for CodeIgniter 3 Applications
# Optimized for security, performance, and best practices
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 1: Base Image with PHP Extensions
# -----------------------------------------------------------------------------
ARG PHP_VERSION=7.4
FROM php:${PHP_VERSION}-fpm-alpine AS base

LABEL maintainer="your-email@example.com"
LABEL description="Production-ready CodeIgniter 3 PHP-FPM container"

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
        mysqli \
        opcache \
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
COPY docker/codeigniter3/php/dev/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/codeigniter3/php/dev/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/codeigniter3/php-fpm/dev/www.conf /usr/local/etc/php-fpm.d/www.conf

# Install development tools
RUN apk add --no-cache \
    git \
    vim \
    mysql-client \
    postgresql-client

# Copy application files
COPY --chown=appuser:appuser . .

# Install composer dependencies if composer.json exists
RUN if [ -f "composer.json" ]; then \
        composer install --optimize-autoloader --no-scripts; \
        composer clear-cache; \
    fi

# Set proper permissions for CodeIgniter
RUN chown -R appuser:appuser /var/www/html \
    && chmod -R 755 /var/www/html/application/cache \
    && chmod -R 755 /var/www/html/application/logs

# Health check script
COPY docker/codeigniter3/healthcheck.sh /usr/local/bin/healthcheck
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

# Install production dependencies only if composer.json exists
RUN if [ -f "composer.json" ]; then \
        composer install \
            --no-dev \
            --optimize-autoloader \
            --no-scripts \
            --prefer-dist; \
        composer clear-cache; \
    fi \
    && rm -rf /usr/bin/composer

# -----------------------------------------------------------------------------
# Stage 4: Production Image
# -----------------------------------------------------------------------------
FROM base AS production

# Copy PHP configuration for production
COPY docker/codeigniter3/php/prod/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/codeigniter3/php/prod/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/codeigniter3/php-fpm/prod/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copy application from builder
COPY --from=builder --chown=appuser:appuser /var/www/html /var/www/html

# Remove composer from production
RUN rm -f /usr/bin/composer

# Set proper permissions for CodeIgniter
RUN chown -R appuser:appuser /var/www/html \
    && chmod -R 755 /var/www/html/application/cache \
    && chmod -R 755 /var/www/html/application/logs \
    # Remove sensitive files
    && rm -f /var/www/html/.env.example \
    && rm -rf /var/www/html/tests

# Health check script
COPY docker/codeigniter3/healthcheck.sh /usr/local/bin/healthcheck
RUN chmod +x /usr/local/bin/healthcheck

USER appuser

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD /usr/local/bin/healthcheck

CMD ["php-fpm"]
