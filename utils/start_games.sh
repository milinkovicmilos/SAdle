#! /bin/bash

echo "Make sure you have populated the tables first!"
read -p "Have you populated the tables (songs) (Y/n): " ANSWER

if [[ $ANSWER = "N" || $ANSWER = "n" ]]; then
    echo "Please make sure you populate the tables first."
    exit 1
fi

FILE="../app/Config/Envs/.env"

DBNAME=$(grep -oP '^DBNAME=\K.*' "$FILE")
USERNAME=$(grep -oP '^USERNAME=\K.*' "$FILE")
PASSWORD=$(grep -oP '^PASSWORD=\K.*' "$FILE")

mariadb -u $USERNAME --password=$PASSWORD $DBNAME < add_starting_games.sql

echo "Created games for today and tommorow. Exiting..."
exit 0
