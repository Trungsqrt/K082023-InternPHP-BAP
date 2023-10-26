#!/bin/bash

# Chạy composer install
docker run --rm -v $(pwd):/app -w /app composer:2.6.3 install --ignore-platform-reqs --no-autoloader --no-dev --no-interaction --no-progress --no-suggest --no-scripts --prefer-dist

# Chạy composer dump-autoload
docker run --rm -v $(pwd):/app -w /app composer:2.6.3 dump-autoload --classmap-authoritative --no-dev --optimize
