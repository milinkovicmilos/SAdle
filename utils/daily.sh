#! /bin/bash

FILE="../app/Config/Envs/.env"

DBNAME=$(grep -oP '^DBNAME=\K.*' "$FILE")
USERNAME=$(grep -oP '^USERNAME=\K.*' "$FILE")
PASSWORD=$(grep -oP '^PASSWORD=\K.*' "$FILE")

mariadb -u $USERNAME --password=$PASSWORD $DBNAME < add_game.sql

echo "Added game for tommorow. Exiting..."
exit 0
