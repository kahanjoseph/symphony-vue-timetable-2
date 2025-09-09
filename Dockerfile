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
    npm \
    libpq-dev



# Force IPv4 preference for getaddrinfo (no sed tricks)
RUN printf '\n# Prefer IPv4 for getaddrinfo()\nprecedence ::ffff:0:0/96  100\n' >> /etc/gai.conf


# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get install -y symfony-cli

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Make build script executable and fix line endings
RUN sed -i 's/\r$//' build.sh && chmod +x build.sh

# Run our build script
RUN ./build.sh

# Expose port
EXPOSE 8000

ENV PORT=8000

# Create the start script with robust handling for $PORT
RUN echo '#!/bin/bash' > /app/start.sh && \
    echo 'PORT=${PORT:-8000}' >> /app/start.sh && \
    echo 'if ! [[ "$PORT" =~ ^[0-9]+$ ]]; then' >> /app/start.sh && \
    echo '  echo "Error: PORT is not a valid number. Exiting."' >> /app/start.sh && \
    echo '  exit 1' >> /app/start.sh && \
    echo 'fi' >> /app/start.sh && \
    echo 'php -S 0.0.0.0:$PORT -t public' >> /app/start.sh && \
    chmod +x /app/start.sh

# Start the application
CMD ["/app/start.sh"]
