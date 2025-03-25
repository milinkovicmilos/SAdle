USE $DBNAME;

INSERT INTO games (song_id, title_mission_id, origin_mission_id, giver_mission_id, game_date)
SELECT
    (SELECT id FROM songs ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    (SELECT id FROM missions ORDER BY RAND() LIMIT 1),
    DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY);

INSERT INTO mission_game_clues (game_id, attribute_to_guess, clue_order)
SELECT
    (
        SELECT id
        FROM games
        ORDER BY id DESC
        LIMIT 1
    ),
    'title',
    GROUP_CONCAT(words ORDER BY RAND() SEPARATOR ',')
    FROM (SELECT 'origin' as words UNION SELECT 'giver' UNION SELECT 'description' UNION SELECT 'objective' UNION SELECT 'reward') as words_table;

INSERT INTO mission_game_clues (game_id, attribute_to_guess, clue_order)
SELECT
    (
        SELECT id
        FROM games
        ORDER BY id DESC
        LIMIT 1
    ),
    'origin',
    GROUP_CONCAT(words ORDER BY RAND() SEPARATOR ',')
    FROM (SELECT 'title' as words UNION SELECT 'giver' UNION SELECT 'description' UNION SELECT 'objective' UNION SELECT 'reward') as words_table;

INSERT INTO mission_game_clues (game_id, attribute_to_guess, clue_order)
SELECT
    (
        SELECT id
        FROM games
        ORDER BY id DESC
        LIMIT 1
    ),
    'giver',
    GROUP_CONCAT(words ORDER BY RAND() SEPARATOR ',')
    FROM (SELECT 'title' as words UNION SELECT 'origin' UNION SELECT 'description' UNION SELECT 'objective' UNION SELECT 'reward') as words_table;
