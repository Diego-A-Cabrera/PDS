-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-09-2024 a las 01:06:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
-- Estructura de tabla para la tabla `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `login_time`) VALUES
(1, 1, '2024-08-29 20:33:35'),
(2, 3, '2024-08-29 20:36:57'),
(3, 6, '2024-08-29 20:43:18'),
(4, 9, '2024-08-29 20:51:50'),
(5, 10, '2024-08-31 09:48:12'),
(6, 12, '2024-08-31 09:56:28'),
(7, 6, '2024-08-31 09:57:10'),
(8, 1, '2024-08-31 10:03:44'),
(9, 1, '2024-08-31 10:10:30'),
(10, 1, '2024-08-31 10:11:19'),
(11, 1, '2024-08-31 10:11:26'),
(12, 13, '2024-08-31 10:15:58'),
(13, 1, '2024-08-31 10:20:06'),
(14, 13, '2024-08-31 10:23:41'),
(15, 1, '2024-08-31 10:24:23'),
(16, 13, '2024-08-31 10:25:04'),
(17, 1, '2024-08-31 10:32:25'),
(18, 13, '2024-08-31 10:53:45'),
(19, 13, '2024-08-31 10:58:33'),
(20, 1, '2024-08-31 11:02:37'),
(21, 13, '2024-08-31 11:05:14'),
(22, 14, '2024-08-31 11:05:48'),
(23, 13, '2024-08-31 11:27:03'),
(24, 15, '2024-08-31 11:27:42'),
(25, 13, '2024-08-31 11:29:43'),
(26, 1, '2024-08-31 11:31:37'),
(27, 13, '2024-08-31 11:31:43'),
(28, 13, '2024-08-31 15:13:46'),
(29, 1, '2024-08-31 15:14:21'),
(30, 14, '2024-08-31 15:18:43'),
(31, 1, '2024-08-31 15:51:12'),
(32, 13, '2024-08-31 15:52:08'),
(33, 16, '2024-08-31 16:37:31'),
(34, 1, '2024-08-31 16:38:12'),
(35, 13, '2024-08-31 17:00:34'),
(36, 14, '2024-08-31 17:02:02'),
(37, 14, '2024-08-31 17:04:11'),
(38, 17, '2024-08-31 17:04:39'),
(39, 17, '2024-08-31 17:05:34'),
(40, 13, '2024-08-31 17:10:30'),
(41, 17, '2024-08-31 17:13:39'),
(42, 13, '2024-09-01 15:30:01'),
(43, 18, '2024-09-01 15:31:53'),
(44, 13, '2024-09-01 15:39:43'),
(45, 1, '2024-09-01 15:39:51'),
(46, 13, '2024-09-01 15:40:04'),
(47, 1, '2024-09-01 15:40:19'),
(48, 13, '2024-09-01 15:40:42'),
(49, 15, '2024-09-01 15:47:28'),
(50, 15, '2024-09-01 15:47:38'),
(51, 1, '2024-09-01 15:47:44'),
(52, 13, '2024-09-01 15:47:56'),
(53, 1, '2024-09-01 15:48:27'),
(54, 12, '2024-09-01 15:48:45'),
(55, 12, '2024-09-01 15:49:03'),
(56, 12, '2024-09-01 15:51:45'),
(57, 1, '2024-09-01 15:54:45'),
(58, 13, '2024-09-01 15:54:54'),
(59, 1, '2024-09-01 15:55:19'),
(60, 13, '2024-09-01 15:55:34'),
(61, 15, '2024-09-01 15:56:14'),
(62, 14, '2024-09-01 16:02:09'),
(63, 19, '2024-09-01 18:07:40'),
(64, 20, '2024-09-01 18:08:46'),
(65, 19, '2024-09-01 18:09:03'),
(66, 19, '2024-09-01 18:15:35'),
(67, 19, '2024-09-01 18:17:47'),
(68, 19, '2024-09-01 18:33:44'),
(69, 19, '2024-09-01 18:35:17'),
(70, 19, '2024-09-01 18:41:10'),
(71, 19, '2024-09-01 18:42:37'),
(72, 19, '2024-09-03 22:15:03'),
(73, 19, '2024-09-03 22:22:39'),
(74, 19, '2024-09-05 19:26:52');

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
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `is_active`) VALUES
(1, 'Boca', 'boca@boca.com', '$2y$10$HU3icrAzjMi2OV5OwZY/i.oBzena7qQTa0P9TOnnUU6S7Ty8C6qrm', 'usuario', '2024-08-29 23:10:47', 0),
(2, 'Argentina', 'asd@asd.com', '$2y$10$wJubCWTqwjLTpRNbrtSVo.OtjgrosKiJeXiy6UBnGU3VeXbpuTtz6', '', '2024-08-29 23:35:19', 1),
(3, 'Fabri', 'fabri@fabri.com', '$2y$10$QplAibiyo4efGrYvo1UcNedW2m5WWUEod1GuVH1iciPeB7j1KJINS', '', '2024-08-29 23:36:49', 1),
(4, 'Diego', '123123@123123.com', '$2y$10$blISCW1cvs0iFj6G1o06UeI8RZDV.K38Nz/Fs/lOlKUtaAorYvd.i', '', '2024-08-29 23:37:38', 1),
(5, 'Manu', 'manu@manu.com', '$2y$10$e28PeFjJ4so0R0AGD55rpeMqIWyundSbysXV1/.4xCtH9K5tXkDMS', '', '2024-08-29 23:40:45', 1),
(6, 'Gonza', 'gonza@gonza.com', '$2y$10$XLz/y2YoGHBSFaXPB9Xyu.II9M333JSKW7c7PKBiu6TQZdQYFuqYa', '', '2024-08-29 23:43:08', 1),
(7, 'caro', 'caro@caro.com', '$2y$10$qYGZV3T9eFZS9tLQfJSFvesJG/UoOdzZjj2fDXrFh/uEeD.u56dqW', '', '2024-08-29 23:45:35', 1),
(8, '123', '123@123.com', '$2y$10$XDoDRMpfvm453Zl47Ednke0XHKXtB.UzYliE4jXOufsef.NI0EA7C', '', '2024-08-29 23:46:59', 1),
(9, 'Emmanuel123', 'asdasd@asdasd.com', '$2y$10$EzSsNlSJyiyC5J46FeRSte.fPLJ.6Q/I5VBtn0FzBAvfBYY4P0b2W', '', '2024-08-29 23:51:40', 1),
(10, 'emmaa', '123123@hotmail.com', '$2y$10$UwsLCK653BT17/jpQA1MbeoGquqkyoEVi/HdqOJmr9CBkspu/FZI.', '', '2024-08-31 12:47:46', 1),
(12, 'emmaa1', '1231232@hotmail.com', '$2y$10$wmmQfeEjjq40NA5WbD3w0OmlKepmIMeY.uL1J0ulCDRLevoaPU.Xm', 'usuario', '2024-08-31 12:53:56', 0),
(13, 'River', 'river@river.com', '$2y$10$c58Uspu0El4Jl4yfKq5zXe1GFUc8WzcB5fKIIUsjcDksP9ncLQrqq', 'administrador', '2024-08-31 13:15:52', 1),
(14, 'Marce', 'marce@marce.com', '$2y$10$x7smWCx53e7Ro36Fi6xyXuwGqKFytxLHFr6YqYG7JrZ2O.6PFkgNO', 'administrador', '2024-08-31 14:05:42', 1),
(15, 'Facu', 'facu@facu.com', '$2y$10$YQaM7WPgudinfOnJ9MfKneSYRSz2yket3JPGQwZUPBVCmRLA11avO', 'usuario', '2024-08-31 14:27:27', 0),
(16, 'Winkler', 'winkler@winkler.com', '$2y$10$jHy5ID4kIDOk.7.4EA3Xw.dVdE5ZYeYGI8g8pe5X72m3Wpib5g9ai', 'administrador', '2024-08-31 19:37:15', 1),
(17, 'Pepe', 'pepe@pepe.com', '$2y$10$WyGbuoInz6DQCI1HPByEyeEgb2Fj9WzC.A80oWUvCGvFciobMUVkq', 'administrador', '2024-08-31 20:04:32', 1),
(18, 'Hugo', 'hugo@hugo.com', '$2y$10$m4Dtp95.MVuT4146MQWjL.mRj7iUpoEGBCuVCKA/ZoWWMLs.lilVS', 'administrador', '2024-09-01 18:31:48', 1),
(19, 'Diego1', 'diego1@diego1.com', '$2y$10$wJxRc22w3hJyll3.lL/MhO2vwERW.nJrO56E58bcKOSvQzINCahtG', 'administrador', '2024-09-01 21:07:32', 1),
(20, 'diego2', 'diego2@diego2.com', '$2y$10$ADGvIARTmsFzDsE6Y8yQhumIMzO.vrvX1.QIqnZg2Z./6WWiof33e', 'usuario', '2024-09-01 21:08:38', 0);

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
(1, 7, 'block', '2024-09-03 22:22:42'),
(2, 7, 'block', '2024-09-03 22:22:53'),
(3, 5, 'block', '2024-09-03 22:23:18'),
(4, 5, 'block', '2024-09-03 22:23:26'),
(5, 3, 'block', '2024-09-03 22:25:47'),
(6, 3, 'block', '2024-09-03 22:26:15'),
(7, 7, 'block', '2024-09-03 22:30:23'),
(8, 7, 'unblock', '2024-09-03 22:30:31'),
(9, 16, 'block', '2024-09-05 19:26:59'),
(10, 16, 'unblock', '2024-09-05 19:27:06'),
(11, 19, 'login', '2024-09-05 19:31:25'),
(12, 19, 'login', '2024-09-05 19:32:54'),
(13, 19, 'logout', '2024-09-05 19:34:13'),
(14, 19, 'login', '2024-09-05 19:35:17'),
(15, 19, 'logout', '2024-09-05 19:35:37'),
(16, 19, 'login', '2024-09-05 19:36:04'),
(17, 19, 'logout', '2024-09-05 19:40:34'),
(18, 19, 'login', '2024-09-05 19:40:44'),
(19, 19, 'logout', '2024-09-05 19:41:36'),
(20, 19, 'login', '2024-09-05 19:41:57'),
(21, 19, 'logout', '2024-09-05 19:45:27'),
(22, 19, 'login', '2024-09-05 19:45:33'),
(23, 19, 'login', '2024-09-05 19:46:16'),
(24, 19, 'logout', '2024-09-05 19:50:10'),
(25, 19, 'login', '2024-09-05 19:50:23'),
(26, 19, 'logout', '2024-09-05 19:53:23'),
(27, 19, 'login', '2024-09-05 19:53:35');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
