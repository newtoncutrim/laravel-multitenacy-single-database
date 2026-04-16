FROM php:8.2-fpm

# set your user name, ex: user=newton
ARG user=appuser
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install PECL extensions
RUN pecl install -o -f redis xdebug \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis xdebug

# Default Xdebug settings for local Docker development.
ENV XDEBUG_MODE=debug,develop \
    XDEBUG_START_WITH_REQUEST=yes \
    XDEBUG_CLIENT_HOST=host.docker.internal \
    XDEBUG_CLIENT_PORT=9003 \
    XDEBUG_IDEKEY=VSCODE

# Set working directory
WORKDIR /var/www

# Copy custom configurations PHP
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/entrypoint.sh /usr/local/bin/laravel-entrypoint
RUN chmod +x /usr/local/bin/laravel-entrypoint

USER $user

ENTRYPOINT ["laravel-entrypoint"]
CMD ["php-fpm"]
