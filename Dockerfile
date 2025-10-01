FROM php:8.4-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    oniguruma-dev \
    nodejs \
    npm \
    icu-dev

# Install PHP extensions (added intl)
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application code
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm install && npm run build

# Set permissions (use 'nobody' for Alpine)
RUN chown -R nobody:nobody storage bootstrap/cache

# Expose dynamic port
EXPOSE 8080

# Start application (use $PORT variable)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
