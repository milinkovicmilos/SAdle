USE $DBNAME;

START TRANSACTION;

INSERT INTO games (song_id, radio_id, game_date)
SELECT id, radio_id, CURRENT_DATE()
FROM songs
ORDER BY RAND()
LIMIT 1;

INSERT INTO games (song_id, radio_id, game_date)
SELECT id, radio_id, DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY)
FROM songs
ORDER BY RAND()
LIMIT 1;

COMMIT;
