#!/usr/bin/env bash

docker compose up -d
composer install --no-interaction
npm install --force
npm run build
./bin/console d:d:c
./bin/console d:m:m --no-interaction
./bin/console d:f:l --no-interaction
cd public && php -S localhost:8080