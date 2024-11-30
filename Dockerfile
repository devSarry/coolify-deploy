# Base Image: PHP + NGINX optimized for Laravel
FROM serversideup/php:8.3-fpm-nginx

# Enable PHP Opcache for performance
ENV PHP_OPCACHE_ENABLE=1

# Set working directory
WORKDIR /var/www/html

# Install required system dependencies
USER root
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get update && apt-get install -y \
    nodejs \
    unzip \
    git && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user-specific permissions early
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Switch to www-data user
USER www-data

# Install PHP and Node.js dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction && \
    npm install && npm run build

# Set file permissions for storage and cache
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Expose the default HTTP port
EXPOSE 80

# Start Nginx and PHP-FPM using S6 overlay
CMD ["init"]
