FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get install -y symfony-cli

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Make build script executable
RUN chmod +x build.sh

# Run our build script
RUN ./build.sh

# Expose port
EXPOSE 8000

# Create start script
RUN echo '#!/bin/bash' > /app/start.sh && \
    echo 'PORT=${PORT:-8000}' >> /app/start.sh && \
    echo 'php -S 0.0.0.0:$PORT -t public' >> /app/start.sh && \
    chmod +x /app/start.sh

# Start command
CMD ["/app/start.sh"]
