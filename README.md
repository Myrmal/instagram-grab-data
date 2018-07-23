# instagram-grab-data

Для работы с БД создать таблицы

CREATE TABLE `followers` (
`username` varchar(255) NOT NULL,
  `follower` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `followers`
  ADD UNIQUE KEY `username` (`username`);
COMMIT;

CREATE TABLE `posts` (
`shortcode` varchar(255) NOT NULL,
  `sub_like` int(7) DEFAULT NULL,
  `not_sub_like` int(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `posts`
  ADD UNIQUE KEY `shortcode` (`shortcode`);
COMMIT;
