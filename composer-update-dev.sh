#!/usr/bin/env bash
cd /var/www/dev/data/new/psf/public_html
composer install --working-dir=/var/www/dev/data/new/psf/public_html --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader
composer update  --working-dir=/var/www/dev/data/new/psf/public_html --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader