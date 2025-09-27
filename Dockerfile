FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /workspace

# Copy only composer files first
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist

# Copy the rest of the application
COPY . /workspace
