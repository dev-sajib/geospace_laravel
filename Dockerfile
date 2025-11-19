# Use official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache
RUN a2enmod rewrite
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port (Railway will provide PORT env var)
EXPOSE ${PORT:-8080}

# Create startup script that uses Railway's PORT
RUN echo '#!/bin/bash\n\
PORT=${PORT:-8080}\n\
echo "Starting application on port $PORT"\n\
# Update Apache port configuration\n\
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf\n\
sed -i "s/:80>/:$PORT>/" /etc/apache2/sites-available/000-default.conf\n\
# Laravel optimizations\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan storage:link || true\n\
# Set SSL environment variables for Railway MySQL\n\
export MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=false\n\
export MYSQL_ATTR_SSL_CA=""\n\
export DB_OPTIONS_SSL_VERIFY=false\n\
# Run migrations and seed with SSL bypass (continue on failure)\n\
php artisan migrate --force\n\
php artisan db:seed --force || echo "Seeding failed, continuing without seed data..."\n\
# Start Apache in foreground\n\
apache2-foreground' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]
