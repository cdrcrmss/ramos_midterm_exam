web: vendor/bin/heroku-php-apache2 public/
deploy: php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan route:cache && php artisan view:cache