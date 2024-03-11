-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2023 a las 11:11:58
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `api-canciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `log` varchar(240) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canciones`
--

CREATE TABLE `canciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `artist` varchar(250) NOT NULL,
  `genre`  varchar(250) NOT NULL,
  `duration` varchar(100) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `canciones`
--

INSERT INTO `canciones` (`id`, `id_usuario`, `title`, `artist`, `genre`, `duration`, `imagen`) VALUES
(1, 3, 'yes, and?', 'Ariana Grande', 'Pop', '3:24', 'yesand_arianagrande.png' ),
(2, 4, 'Training Season', 'Dua Lipa', 'Pop', '3:02','trainingseason_dualipa.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL COMMENT 'clave principal',
  `email` varchar(150) NOT NULL,
  `password` varchar(240) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL,
  `token` varchar(240) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='tabla de usuarios';

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `nombre`, `imagen`, `disponible`, `token`) VALUES
(1, 'davidrodenasherraiz@dominio.com', '07d046d5fac12b3f82daf5035b9aae86db5adc8275ebfbf05ec83005a4a8ba3e', 'david rodenas herraiz', NULL, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjIxMzczNzIsImRhdGEiOnsiaWQiOiIxIiwiZW1haWwiOiJkYXZpZHJvZEBnbWFpbC5jb20ifX0.FLlqJO30GgMiYWFNSXFjIWunenCjb7EnZJ30PSJdAN8'),
(2, 'soniamenadelgadol@dominio.com', 'b90d33f2b12789d32691050a2083be28eb99985601a1f1a72efc9232e49306fd', 'sonia mena delgado', NULL, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzM3NzU0MDEsImRhdGEiOnsiaWQiOiIyIiwiZW1haWwiOiJzb25pYW1lbmFkZWxAZ21haWwuY29tIn19.33d9tDvm1jRJ-fzdz1-leoRQ5EMnnrxuY7BNDqatl5g'),
(3, 'srodher115@g.educaand.es', '324ca5355e9d7d5f60fb23b379f5bad7d4a12013a8b89b46ec2392c3021d3a27', 'santiago', NULL, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzQ1NDUwMjQsImRhdGEiOnsiaWQiOiIzIiwiZW1haWwiOiJzcm9kaGVyMTE1QGcuZWR1Y2FhbmQuZXMifX0.gNhn_y97brgSLHRzRMQgrO838GBRAcCuDjdF12Pu5Mw'),
(5, 'paco112@g.educaand.es', '89a0d30ea1b4cb9aff84758a418e835f06993f9f2446851ef331d30c27173828', 'pacoooooo', '63c985673e93e.PNG', 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzQzMjg0MjAsImRhdGEiOnsiaWQiOiI1IiwiZW1haWwiOiJwYWNvMTEyQGcuZWR1Y2FhbmQuZXMifX0.Rgfklsu132r4r22w7KLYJ9n2uTe9WImN-TjA7-iycOc'),
(9, 'jose@g.educaand.es', '1ec4ed037766aa181d8840ad04b9fc6e195fd37dedc04c98a5767a67d3758ece', 'jose', '63c984c24de7d.PNG', 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzQxNTExMzcsImRhdGEiOnsiaWQiOiI5IiwiZW1haWwiOiJqb3NlQGcuZWR1Y2FhbmQuZXMifX0.-lpq8EB-CTU-ir5pKGtYx0l75YpgLCOECCzyNzD8D7Q'),
(10, 'nuevo@gmail.com', '238b4429a65d9a87985b33497d8368e0847b52437595018c604af92a154f3e98', 'nuevo', NULL, 0, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzQ0OTA1MDksImRhdGEiOnsiaWQiOiIxMCIsImVtYWlsIjoibnVldm9AZ21haWwuY29tIn19.VbhXyRyqj7NHgdflN6UTDbGxRXmlFOHxZOBp7ev_bxY'),
(11, 'nuevo@gmail.com', '238b4429a65d9a87985b33497d8368e0847b52437595018c604af92a154f3e98', 'nuevo', '63ceb2782e9e2.', 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `canciones`
--
ALTER TABLE `canciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `canciones`
--
ALTER TABLE `canciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'clave principal', AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
