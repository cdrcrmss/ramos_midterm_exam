#!/bin/bash

echo "Starting deployment..."

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run database migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed successfully!"