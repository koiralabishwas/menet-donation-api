FROM dunglas/frankenphp:php8.4-bookworm

# Set the working directory to /app
WORKDIR /app

# Install required dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    nano \
    build-essential \
    && apt-get clean

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    gd \
    zip \
    bcmath

# Install Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Install PHP extensions
RUN install-php-extensions @composer

# Install Node.js and npx
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash \
    && mv /root/.bun/bin/bun /usr/local/bin/bun \
    && chmod +x /usr/local/bin/bun

# Copy the current directory contents into the container at /app
COPY . /app

# Install any needed packages specified in composer.json and package.json
RUN composer install --optimize-autoloader --no-dev \
    && bun install \
    && bun run build

# Expose port 8080 for PHP Artisan
EXPOSE 8080

# Run app when the container launches
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
