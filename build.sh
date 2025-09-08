#!/bin/bash

echo "Creating .env file for build..."
echo "APP_ENV=dev" > .env
echo "APP_SECRET=placeholder-secret-key" >> .env
echo "DATABASE_URL=\"sqlite:///%kernel.project_dir%/var/data.db\"" >> .env

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "Installing Node.js dependencies..."
npm ci

echo "Building Vue.js assets..."
npm run build

echo "Clearing Symfony cache..."
php bin/console cache:clear --env=prod

echo "Build complete!"
