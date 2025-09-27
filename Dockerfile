FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install zip

# Set working directory
WORKDIR /workspace

# Copy only composer files first
COPY composer.json composer.lock ./

# Install dependencies inside container
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --no-interaction --prefer-dist

# Now copy the rest of the app
COPY . .

# Install dev dependencies (if needed for tests)
RUN composer install --no-interaction --prefer-dist

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
