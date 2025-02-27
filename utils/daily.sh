#! /bin/bash

DBNAME=$(grep -oP '^DBNAME=\K.*' "$file")
USERNAME=$(grep -oP '^USERNAME=\K.*' "$file")
PASSWORD=$(grep -oP '^PASSWORD=\K.*' "$file")

mariadb -u $USERNAME --password=$PASSWORD $DBNAME < add_game.sql

echo "Added game for tommorow. Exiting..."
exit 0
