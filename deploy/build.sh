#! /bin/sh

composer install -o --no-dev

fnm use

npm install

npm run build

php artisan migrate --force

php artisan optimize
