USE $DBNAME;

START TRANSACTION;

INSERT INTO games (song_id, title_mission_id, origin_mission_id, giver_mission_id, game_date)
SELECT
	(SELECT id FROM songs ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    CURRENT_DATE();

INSERT INTO games (song_id, title_mission_id, origin_mission_id, giver_mission_id, game_date)
SELECT
	(SELECT id FROM songs ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY)

COMMIT;
