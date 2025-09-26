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
    default-mysql-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo pdo_mysql mbstring zip gd exif pcntl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /workspace

# Set Composer environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

# Copy composer files first for caching
COPY composer.json composer.lock ./

# Install PHP dependencies (including dev for PHPUnit)
RUN composer install --prefer-dist --no-interaction --optimize-autoloader

# Copy the rest of the application code
COPY . .

# Expose default web server port
EXPOSE 80

# Default command
CMD ["php", "-a"]
