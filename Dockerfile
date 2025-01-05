FROM php:8.2-apache

LABEL maintainer="Yamazaki Rajan Valencia <yamazaki.rajan.valencia@gmail.com>"

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

# Install Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash \
    && mv /root/.bun/bin/bun /usr/local/bin/bun \
    && chmod +x /usr/local/bin/bun

# Copy the current directory contents into the container at /app
COPY . /app

# Dynamically select the appropriate .env file based on the branch
ARG APP_ENV=dev
RUN if [ "$APP_ENV" = "prd" ]; then \
        cp /app/.env.prd /app/.env; \
    elif [ "$APP_ENV" = "dev" ]; then \
        cp /app/.env.dev /app/.env; \
    else \
        cp /app/.env /app/.env; \
    fi

# Install any needed packages specified in composer.json and package.json
RUN composer install --optimize-autoloader --no-dev \
    && bun install \
    && bun run build

# Expose port 8000 for PHP Artisan
EXPOSE 8000

# Run app when the container launches
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
