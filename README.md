# Instagram-grab-data

### БД
Для работы с БД создать таблицы

```
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
```

### Параметры доступа

В <b>InstagramCONST.php</b> проставить доступы к БД и логин с паролем от Instagram.

### Запуск скрипта

В <b>getFollowers.php</b> передается параметр username, например:

```
путь_к_интерпретатору -f путь_к_скрипту/getFollowers.php username
```

В <b>getPostLikes.php</b> передается 2 параметра  - username и количество_постов, например:

```
путь_к_интерпретатору -f путь_к_скрипту/getFollowers.php username count
```
