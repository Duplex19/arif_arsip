FROM dunglas/frankenphp:1.4-php8.4-alpine

# Argument defaults
ARG APP_ENV=production
ARG WWWUSER=1000
ARG WWWGROUP=1000

ENV APP_ENV=${APP_ENV} \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PHP_INI_SCAN_DIR=/etc/php8/conf.d:/usr/local/etc/php/conf.d

# System dependencies
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    linux-headers \
    $([ "$APP_ENV" = "local" ] && echo "vim") \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        intl \
        gd \
        bcmath \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && apk del --purge linux-headers
RUN pecl install redis && docker-php-ext-enable redis
# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure PHP for production
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini \
    && echo "max_execution_time = 300" > /usr/local/etc/php/conf.d/execution.ini \
    && echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/upload.ini \
    && echo "post_max_size = 12M" > /usr/local/etc/php/conf.d/post.ini \
    && echo "max_input_time = 300" > /usr/local/etc/php/conf.d/input.ini

# Create system user
RUN set -eux; \
    addgroup -g ${WWWGROUP} appgroup; \
    adduser -u ${WWWUSER} -G appgroup -s /bin/sh -D appuser; \
    mkdir -p /app /data /config /app/storage /app/bootstrap/cache /app/public/storage; \
    chown -R appuser:appgroup /app /data /config

WORKDIR /app

# Copy application files
COPY --chown=appuser:appgroup . .

# Install PHP dependencies (production optimized)
RUN if [ "$APP_ENV" = "production" ]; then \
        composer install --no-dev --optimize-autoloader --no-interaction --no-progress; \
    else \
        composer install --optimize-autoloader --no-interaction --no-progress; \
    fi

# Build frontend assets (skip if vite/node not available)
RUN if [ -f "package.json" ]; then \
        npm install --ignore-scripts --no-audit --no-fund 2>/dev/null || true; \
        npm run build 2>/dev/null || true; \
    fi

# Optimize Laravel for production
RUN if [ "$APP_ENV" = "production" ]; then \
        php artisan optimize; \
        php artisan view:cache; \
        php artisan config:cache; \
        php artisan route:cache; \
        php artisan event:cache; \
    fi

# Set permissions
RUN chmod -R 775 storage bootstrap/cache public/storage; \
    chown -R appuser:appgroup storage bootstrap/cache public/storage

# Copy FrankenPHP Caddyfile
COPY .docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

ENV CADDY_GLOBAL_OPTIONS=""

# Expose ports
EXPOSE 80 443 2019

# Start with FrankenPHP + Octane
CMD ["sh", "-c", "\
    php artisan storage:link --force && \
    php artisan octane:start \
        --server=frankenphp \
        --host=0.0.0.0 \
        --port=80 \
        --workers=${OCTANE_WORKERS:-auto} \
        --max-requests=${OCTANE_MAX_REQUESTS:-500} \
        --task-workers=${OCTANE_TASK_WORKERS:-auto} \
"]
