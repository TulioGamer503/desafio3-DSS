-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 23-05-2025 a las 22:17:05
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `canciones_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canciones`
--

DROP TABLE IF EXISTS `canciones`;
CREATE TABLE IF NOT EXISTS `canciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `titulo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `artista` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `album` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ano` int NOT NULL,
  `enlace` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `canciones`
--

INSERT INTO `canciones` (`id`, `user_id`, `titulo`, `artista`, `album`, `ano`, `enlace`, `created_at`) VALUES
(2, 1, 'wazaa2', 'wazaaa2', NULL, 2020, '', '2025-05-23 21:53:46'),
(3, 1, 'wazaa', 'wazaaa2', NULL, 2020, 'https://www.youtube.com/watch?v=a1Femq4NPxs', '2025-05-23 21:53:58'),
(4, 1, 'wazaa', 'wazaaa2', NULL, 2022, 'https://www.youtube.com/watch?v=a1Femq4NPxs', '2025-05-23 21:54:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'cesar', 'cesar@gmail.com', '$2y$10$cIcLC1lIafXTGOHtk7wc.ueBpJKvCLNr7jE75PGWtaYIJBdel55RK', '2025-05-23 21:07:21');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
