# Dockerfile

FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl

# Install PHP extensions required for Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Expose port 8000 to access the Laravel development server
EXPOSE 8000

# Command to keep the container running, allowing you to install Laravel or run commands
CMD ["bash"]