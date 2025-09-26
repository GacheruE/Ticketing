FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    default-mysql-client

# Install PHP extensions
RUN docker-php-ext-install \
    pdo pdo_mysql mbstring zip gd exif pcntl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /workspace

# Copy composer files first (for caching)
COPY composer.json composer.lock /workspace/

# Install project dependencies
RUN composer install --no-interaction --prefer-dist

# Copy the rest of your application code
COPY . /workspace

# Set composer environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

CMD ["php", "--version"]
