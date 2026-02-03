FROM php:8.2-fpm

# Install system packages, Nginx and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        default-mysql-client \
        git \
        unzip \
        pkg-config \
        libicu-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        zip \
        procps \
        nano \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring xml zip gd bcmath intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install composer from the official composer image
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copy entrypoint script and helper scripts
COPY docker/limitlessfitness/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
COPY docker/limitlessfitness/wait-for-db.sh /usr/local/bin/wait-for-db.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh /usr/local/bin/wait-for-db.sh

# Copy nginx site config
COPY docker/limitlessfitness/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

# Ensure www-data owns files
RUN mkdir -p /var/www/html && chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
