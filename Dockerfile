# Build stage
FROM node:20-alpine AS builder

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Production stage
FROM php:8.3-apline

# Install system dependencies
RUN apk add --no-cache \
    nodejs \
    npm \
    git \
    unzip \
    libzip-dev \
    mysql-client \
    && docker-php-ext-install zip pdo pdo_mysql

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Copy built assets from builder
COPY --from=builder /app/public/build public/build

# Install PHP dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chmod -R 755 storage bootstrap/cache

# Expose port
EXPOSE 8080

# Start server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
