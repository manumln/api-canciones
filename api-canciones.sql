SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `log` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `log` (`id`, `log`) VALUES
(5, 'recibido');

CREATE TABLE `canciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `artist` varchar(250) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `duration` varchar(250) NOT NULL,
  `imagen` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `canciones` (`id`, `id_usuario`, `title`, `artist`, `genre`, `duration`, `imagen`) VALUES
(1, 1, 'yes, and?', 'Ariana Grande', 'Pop', '3:35', 'https://upload.wikimedia.org/wikipedia/en/thumb/e/e8/Ariana_Grande_-_Yes%2C_And%3F_%28edit%29.png/220px-Ariana_Grande_-_Yes%2C_And%3F_%28edit%29.png'),
(2, 1, 'Feather', 'Sabrina', 'Pop', '3:05', 'https://m.media-amazon.com/images/I/71bZG4fuxzL._UF894,1000_QL80_.jpg'),
(3, 1, 'Movies', 'Weyes Blood', 'Indie', '5:53', 'https://www.mindies.es/wp-content/uploads/2019/04/weyes-blood.jpg'),
(4, 1, 'Doin time', 'Lana Del Rey', 'Indie', '3:22', 'https://assets.primaverasound.com/psweb/t3f5kfy1hh7sw5h4ybf0_1616069563060.jpg'),
(5, 1, 'positions', 'Ariana Grande', 'Pop', '2:52', 'https://upload.wikimedia.org/wikipedia/en/a/a0/Ariana_Grande_-_Positions.png'),
(6, 1, 'Roller Coaster', 'NMIXX', 'K-Pop', '3:34', 'https://upload.wikimedia.org/wikipedia/en/9/9b/Nmixx_-_Roller_Coaster.png'),
(7, 1, 'Kill Bill', 'SZA', 'R&B', '2:33', 'https://upload.wikimedia.org/wikipedia/en/2/2c/SZA_-_S.O.S.png'),
(8, 1, 'Drama', 'Aespa', 'K-Pop', '3:34', 'https://upload.wikimedia.org/wikipedia/commons/d/da/Aespa_-_Drama.png');

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL COMMENT 'clave principal',
  `email` varchar(150) NOT NULL,
  `password` varchar(240) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL,
  `token` varchar(240) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='tabla de usuarios';

INSERT INTO `usuarios` (`id`, `email`, `password`, `nombre`, `imagen`, `disponible`, `token`) VALUES
(1, 'mpmolinaruiz@email.com', 'dbde6e431edd7f4672f039680c58d4a0b59bff2dacfa25d63a228ba2ce392bd1', 'manuel molina', NULL, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjIxMzczNzIsImRhdGEiOnsiaWQiOiIxIiwiZW1haWwiOiJkYXZpZHJvZEBnbWFpbC5jb20ifX0.FLlqJO30GgMiYWFNSXFjIWunenCjb7EnZJ30PSJdAN8'), 

ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `canciones`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `canciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'clave principal', AUTO_INCREMENT=4;
COMMIT;

