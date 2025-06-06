START TRANSACTION;

CREATE TABLE `games` (
  `id` int(10) NOT NULL,
  `song_id` int(3) NOT NULL,
  `title_mission_id` int(3) NOT NULL,
  `origin_mission_id` int(3) NOT NULL,
  `giver_mission_id` int(3) NOT NULL,
  `game_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `missions` (
  `id` int(3) NOT NULL,
  `title` varchar(32) NOT NULL,
  `origin_id` int(1) NOT NULL,
  `giver_id` int(2) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `objective` varchar(1024) NOT NULL,
  `reward` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE `mission_game_clues` (
  `id` int(10) NOT NULL,
  `game_id` int(10) NOT NULL,
  `attribute_to_guess` varchar(8) NOT NULL,
  `clue_order` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE `mission_givers` (
  `id` int(2) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE `mission_origins` (
  `id` int(1) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE `radio_stations` (
  `id` int(2) NOT NULL,
  `name` varchar(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `songs` (
  `id` int(3) NOT NULL,
  `radio_id` int(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `video_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_SONG_ID` (`song_id`),
  ADD KEY `FK_TITLE_MISSION_ID` (`title_mission_id`),
  ADD KEY `FK_ORIGIN_MISSION_ID` (`origin_mission_id`),
  ADD KEY `FK_GIVER_MISSION_ID` (`giver_mission_id`);

ALTER TABLE `missions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IX_TITLE_UNIQUE` (`title`),
  ADD KEY `FK_ORIGIN_ID` (`origin_id`),
  ADD KEY `FK_GIVER_ID` (`giver_id`);

ALTER TABLE `mission_game_clues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_GAME_ID` (`game_id`);

ALTER TABLE `mission_givers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IX_NAME_UNIQUE` (`name`);

ALTER TABLE `mission_origins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IX_NAME_UNIQUE` (`name`);

ALTER TABLE `radio_stations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IX_NAME_UNIQUE` (`name`);

ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IX_VIDEO_ID_UNIQUE` (`video_id`) USING BTREE,
  ADD KEY `FK_RADIO_ID` (`radio_id`);


ALTER TABLE `games`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `missions`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mission_game_clues`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mission_givers`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mission_origins`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;

ALTER TABLE `radio_stations`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

ALTER TABLE `songs`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;


ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`),
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`title_mission_id`) REFERENCES `missions` (`id`),
  ADD CONSTRAINT `games_ibfk_3` FOREIGN KEY (`origin_mission_id`) REFERENCES `missions` (`id`),
  ADD CONSTRAINT `games_ibfk_4` FOREIGN KEY (`giver_mission_id`) REFERENCES `missions` (`id`);

ALTER TABLE `missions`
  ADD CONSTRAINT `missions_ibfk_1` FOREIGN KEY (`origin_id`) REFERENCES `mission_origins` (`id`),
  ADD CONSTRAINT `missions_ibfk_2` FOREIGN KEY (`giver_id`) REFERENCES `mission_givers` (`id`);

ALTER TABLE `mission_game_clues`
  ADD CONSTRAINT `mission_game_clues_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);

ALTER TABLE `songs`
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`radio_id`) REFERENCES `radio_stations` (`id`);
COMMIT;
