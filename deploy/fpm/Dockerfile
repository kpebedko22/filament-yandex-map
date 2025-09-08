FROM php:8.4-fpm

# Arguments defined in docker-compose
ARG user
ARG uid

COPY "./deploy/fpm/conf.d/php.ini" "$PHP_INI_DIR/php.ini"
COPY "./deploy/fpm/conf.d/www.conf" "/usr/local/etc/php-fpm.d/www.conf"

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libjpeg-dev \
    libwebp-dev \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    sudo

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip opcache sockets intl > /dev/null

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user
