#! /bin/bash

cd /var/www/html/

composer install

cp .env.docker app/Config/Envs/.env

exec "$@"
