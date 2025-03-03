#! /bin/bash

DIR=$(dirname "$(realpath $0)")
ENVFILE=$DIR/../app/Config/Envs/.env

DBNAME=$(grep -oP '^DBNAME=\K.*' "$ENVFILE")
USERNAME=$(grep -oP '^USERNAME=\K.*' "$ENVFILE")
PASSWORD=$(grep -oP '^PASSWORD=\K.*' "$ENVFILE")

mariadb -u $USERNAME --password=$PASSWORD $DBNAME < $DIR/add_game.sql
echo "Added game for tommorow."

SCRIPT=$DIR/sitemap_generator.php
php $SCRIPT

echo "Generated sitemap. Exiting..."
exit 0
