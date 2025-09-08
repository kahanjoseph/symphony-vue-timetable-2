#!/bin/bash

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci

# Build assets
npm run build

# Clear cache
php bin/console cache:clear --env=prod

# Create database if it doesn't exist
php bin/console doctrine:database:create --if-not-exists

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction

echo "Deployment ready!"
