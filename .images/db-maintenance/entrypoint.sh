#! /bin/bash

cd /root

until mariadb -h "$HOST" -u "$USERNAME" --password="$PASSWORD" -e "SELECT 1" > /dev/null 2>&1; do
    echo "Database server not ready... Trying again..."
    sleep 2
done

DB_EXISTS=$(mariadb -h "$HOST" -u "$USERNAME" --password="$PASSWORD" -sse "
    SELECT SCHEMA_NAME
    FROM information_schema.schemata
    WHERE SCHEMA_NAME='$DBNAME';
")

# Check if the database is already setup, if not setup the scripts, tables and cronjob
if [ "$DB_EXISTS" != "$DBNAME" ]; then
    sed "s/\$DBNAME/${DBNAME}/" template_add_game.sql > add_game.sql
    sed "s/\$DBNAME/${DBNAME}/" template_add_starting_games.sql > add_starting_games.sql

    mariadb -h "$HOST" -u "$USERNAME" --password="$PASSWORD" -e "CREATE DATABASE $DBNAME;"
    mariadb -h "$HOST" -u "$USERNAME" --password="$PASSWORD" "$DBNAME" < setup_database.sql
    mariadb -h "$HOST" -u "$USERNAME" --password="$PASSWORD" "$DBNAME" < add_starting_games.sql

    echo "#!/bin/bash" > /etc/periodic/daily/add_games
    echo "mariadb -h mariadb -u root < /root/add_game.sql" >> /etc/periodic/daily/add_games

    chmod +x /etc/periodic/daily/add_games
fi

crond -f -l 2
