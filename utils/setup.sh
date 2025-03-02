#! /bin/bash

echo "Please ensure you are running PHP 8.3+"
read -p "Are you running PHP 8.3+ (Y/n)? " -n 1 ANSWER
echo

if [[ $ANSWER = "N" || $ANSWER = "n" ]]; then
    echo "Please make sure you are running appropriate version of PHP."
    exit 1
fi

echo "Please make sure you are running MySQL/MariaDB on your system"
read -p "Are you running MySQL/MariaDB (Y/n)? " -n 1 ANSWER
echo

if [[ $ANSWER = "N" || $ANSWER = "n" ]]; then
    echo "Please make sure to running MySQL/MariaDB instance."
    exit 1
fi

read -p "Enter database hostname: " HOST
echo "Please make sure to use use account with permission to create a database."
read -p "Enter username: " USERNAME
read -s -p "Enter password: " PASSWORD
echo
read -p "Enter desired database name: " DBNAME

mariadb -h $HOST -u $USERNAME --password=$PASSWORD -e "CREATE DATABASE $DBNAME;"
cp template_add_game.sql add_game.sql
cp template_add_starting_games.sql add_starting_games.sql
sed -i 's/$DBNAME/sadle_db/' add_game.sql
sed -i 's/$DBNAME/sadle_db/' add_starting_games.sql

if [ $? != 0 ]; then 
    echo "MariaDB error..."
    exit 1
fi

mariadb -u $USERNAME --password=$PASSWORD $DBNAME < setup_database.sql

if [ $? != 0 ]; then 
    echo "Error while executing setup sql script..."
    exit 1
fi

if [ ! -d ../app/Config/Envs ]; then
    echo "Making Envs directory..."
    mkdir ../app/Config/Envs
fi

echo "Generating .env file..."
echo "DBTYPE=mysql" > ../app/Config/Envs/.env
echo "DBNAME=$DBNAME" >> ../app/Config/Envs/.env
echo "HOST=$HOST" >> ../app/Config/Envs/.env
read -p "Enter MariaDB username for .env: " USERNAME
echo "USERNAME=$USERNAME" >> ../app/Config/Envs/.env
read -s -p "Enter password: " PASSWORD
echo
echo "PASSWORD=$PASSWORD" >> ../app/Config/Envs/.env

echo "Make sure to populate the tables then run start_games.sh"
echo "Exiting..."
exit 0
