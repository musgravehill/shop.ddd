#!/bin/bash

echo "Hello from ENTRYPOINT"
#cd /var/www/sd.bn && composer update 
php-fpm -F
