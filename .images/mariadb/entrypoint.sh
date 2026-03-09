#! /bin/bash

cd /root

# Launch mariadb daemon temporarily for the setup before handing it off to its original entrypoint script
mariadbd-safe &

until mariadb -u "$USERNAME" --password="$PASSWORD" -e "SELECT 1" > /dev/null 2>&1; do
    echo "Database server not ready... Trying again..."
    sleep 2
done

DB_EXISTS=$(mariadb -u "$USERNAME" --password="$PASSWORD" -sse "
    SELECT SCHEMA_NAME
    FROM information_schema.schemata
    WHERE SCHEMA_NAME='$DBNAME';
")

# Check if the database is already setup, if not setup the scripts, tables and cronjob
if [ "$DB_EXISTS" != "$DBNAME" ]; then
    sed "s/\$DBNAME/${DBNAME}/" template_add_game.sql > add_game.sql
    sed "s/\$DBNAME/${DBNAME}/" template_add_starting_games.sql > add_starting_games.sql

    mariadb -u "$USERNAME" --password="$PASSWORD" -e "CREATE DATABASE $DBNAME;"
    mariadb -u "$USERNAME" --password="$PASSWORD" "$DBNAME" < setup_database.sql
    mariadb -u "$USERNAME" --password="$PASSWORD" "$DBNAME" < add_starting_games.sql

    echo "0 0 * * * root mariadb -u $USERNAME --password=$PASSWORD $DBNAME < /root/add_game.sql" > /etc/cron.d/add_games_daily
fi

mariadb-admin shutdown

service cron start

exec docker-entrypoint.sh "$@"
