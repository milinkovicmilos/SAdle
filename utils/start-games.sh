#! /bin/bash

echo "Make sure you have populated the tables first!"
read -p "Have you populated the tables (songs) (Y/n): " ANSWER

if [[ $ANSWER = "N" || $ANSWER = "n" ]]; then
    echo "Please make sure you populate the tables first."
    exit 1
fi

DBNAME=$(grep -oP '^DBNAME=\K.*' "$file")
USERNAME=$(grep -oP '^USERNAME=\K.*' "$file")
PASSWORD=$(grep -oP '^PASSWORD=\K.*' "$file")

mariadb -u $USERNAME --password=$PASSWORD $DBNAME < add_starting_games.sql

echo "Created games for today and tommorow. Exiting..."
exit 0
