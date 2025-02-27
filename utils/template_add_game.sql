USE $DBNAME;

INSERT INTO games (song_id, radio_id, game_date)
SELECT id, radio_id, DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY)
FROM songs
ORDER BY RAND()
LIMIT 1;
