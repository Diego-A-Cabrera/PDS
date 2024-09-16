-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-09-2024 a las 01:24:12
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pds`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('usuario','administrador') DEFAULT 'usuario',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `failed_attempts` int(11) DEFAULT 0,
  `last_attempt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `is_active`, `failed_attempts`, `last_attempt`) VALUES
(1, 'Admin', 'admin@admin.com', '$2y$10$dQlMFVp5/1usndlIlnitXuZuIqGvbP1GT8Yx9tVPy1BwEyiSebpXy', 'administrador', '2024-09-14 21:59:00', 1, 0, NULL),
(2, 'Diego', 'diego@diego.com', '$2y$10$amsOyDbpLZ665dF3WiMQQexCuKMEPmAh6dk/289BuubC2XrMiIufO', 'administrador', '2024-09-15 16:42:29', 1, 0, NULL),
(3, 'User', 'user@user.com', '$2y$10$DlGatdMWDJNW1r/Stp16vOrJWIQFMk1j0yDYMAQixDYvxjLZOxagG', 'usuario', '2024-09-15 16:43:44', 1, 0, NULL),
(4, 'Ema', 'ema@ema.com', '$2y$10$dzCbex43D0fucIUSyHXzSe7P7QFinav5jYZ4SbXwKt2z6lz3nshAG', 'usuario', '2024-09-16 22:49:16', 1, 0, NULL),
(5, 'Fabri', 'Fabri@123.com', '$2y$10$OswG5NCwF//dsXJAzbKAluqbCVvRO4mjfuE8OSrBZ1leeAZdaEhfy', 'usuario', '2024-09-16 23:01:56', 1, 0, NULL),
(6, 'Marce', 'marce@123.com', '$2y$10$WDURy9mjgbrvAKdEeU70F.tMGKMSXcwyPPZsdxRZLxFlsEdSuoxYO', 'usuario', '2024-09-16 23:05:24', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `action`, `timestamp`) VALUES
(1, 1, 'create', '2024-09-14 21:59:00'),
(2, 1, 'login', '2024-09-15 13:42:05'),
(3, 1, 'logout', '2024-09-15 13:42:11'),
(4, 2, 'create', '2024-09-15 13:42:29'),
(5, 2, 'login', '2024-09-15 13:42:37'),
(6, 2, 'logout', '2024-09-15 13:43:20'),
(7, 3, 'create', '2024-09-15 13:43:44'),
(8, 3, 'login', '2024-09-15 13:43:53'),
(9, 3, 'logout', '2024-09-15 13:44:02'),
(10, 1, 'login', '2024-09-15 13:49:47'),
(11, 1, 'logout', '2024-09-15 14:10:54'),
(12, 3, 'login', '2024-09-15 14:11:31'),
(13, 3, 'logout', '2024-09-15 14:13:19'),
(14, 1, 'login', '2024-09-15 14:13:26'),
(15, 3, 'block', '2024-09-15 14:14:13'),
(16, 3, 'unblock', '2024-09-15 14:14:14'),
(17, 1, 'logout', '2024-09-15 14:15:01'),
(18, 3, 'login', '2024-09-15 14:15:07'),
(19, 1, 'login', '2024-09-15 14:19:17'),
(20, 3, 'block', '2024-09-15 14:24:38'),
(21, 3, 'unblock', '2024-09-15 14:24:51'),
(22, 3, 'login', '2024-09-16 19:07:54'),
(23, 3, 'logout', '2024-09-16 19:08:42'),
(24, 1, 'login', '2024-09-16 19:13:46'),
(25, 1, 'logout', '2024-09-16 19:18:22'),
(26, 1, 'login', '2024-09-16 19:18:33'),
(27, 1, 'logout', '2024-09-16 19:19:15'),
(28, 1, 'login', '2024-09-16 19:19:22'),
(29, 1, 'logout', '2024-09-16 19:19:25'),
(30, 3, 'login', '2024-09-16 19:31:26'),
(31, 3, 'logout', '2024-09-16 19:31:29'),
(32, 1, 'login', '2024-09-16 19:31:46'),
(33, 1, 'logout', '2024-09-16 19:38:54'),
(34, 3, 'login', '2024-09-16 19:39:03'),
(35, 3, 'logout', '2024-09-16 19:39:05'),
(36, 3, 'failed login attempt', '2024-09-16 19:39:23'),
(37, 1, 'login', '2024-09-16 19:39:33'),
(38, 1, 'logout', '2024-09-16 19:48:29'),
(39, 4, 'create', '2024-09-16 19:49:16'),
(40, 4, 'login', '2024-09-16 19:49:25'),
(41, 4, 'logout', '2024-09-16 19:49:29'),
(42, 4, 'failed login attempt', '2024-09-16 19:49:36'),
(43, 4, 'failed login attempt', '2024-09-16 19:49:52'),
(44, 4, 'too many failed attempts', '2024-09-16 19:50:02'),
(45, 1, 'failed login attempt', '2024-09-16 19:50:35'),
(46, 1, 'login', '2024-09-16 19:50:47'),
(47, 1, 'logout', '2024-09-16 19:51:17'),
(48, 1, 'login', '2024-09-16 19:51:25'),
(49, 4, 'unblock', '2024-09-16 19:51:28'),
(50, 1, 'logout', '2024-09-16 19:51:35'),
(51, 4, 'login', '2024-09-16 19:51:41'),
(52, 4, 'logout', '2024-09-16 19:51:46'),
(53, 6, 'login', '2024-09-16 20:05:46'),
(54, 6, 'logout', '2024-09-16 20:05:48'),
(55, 6, 'failed login attempt', '2024-09-16 20:05:52'),
(56, 6, 'failed login attempt', '2024-09-16 20:05:57'),
(57, 6, 'too many failed attempts', '2024-09-16 20:06:01'),
(58, 1, 'login', '2024-09-16 20:06:13'),
(59, 1, 'logout', '2024-09-16 20:06:32'),
(60, 1, 'login', '2024-09-16 20:06:47'),
(61, 6, 'unblock', '2024-09-16 20:06:49'),
(62, 1, 'logout', '2024-09-16 20:06:57'),
(63, 6, 'login', '2024-09-16 20:07:03'),
(64, 6, 'logout', '2024-09-16 20:07:13'),
(65, 1, 'login', '2024-09-16 20:10:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
