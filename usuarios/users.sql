-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 20-04-2024 a las 10:34:33
-- Versión del servidor: 10.6.16-MariaDB-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `invoicing`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `rol` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `area`, `rol`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Cristian Restrepo', 'analistacontable@losretales.co', NULL, '$2y$12$7vc.BchUe4RQ0.IoquC43..N2ctq6s.lzBepgAnyg64EMcGLud1Fy', 'DIRFINANCIERO', 'Admin', NULL, '2024-04-20 18:52:01', '2024-04-20 18:52:01'),
(2, 'Brian Castaño', 'coordinadorlogistico@losretales.co', NULL, '$2y$12$5wSB92aLA837VviVMmnYFe1fuC4Xm7YwFDMEeAwXRWub6kyBxiKHG', 'AREA DE SERVICIOS', 'Digitador-Ap', NULL, '2024-04-20 19:19:35', '2024-04-20 19:19:35'),
(3, 'Fernando Muñoz', 'coordinadorinventarios@losretales.co', NULL, '$2y$12$kxP0S260lE.fEvhQRUMPUupe6j.WZvIShYwD9Ozvm2jKEKdafoLxW', 'COORDINACION DE INVENTARIOS', 'Digitador-Ap', NULL, '2024-04-20 19:21:04', '2024-04-20 19:21:04'),
(4, 'Jazmin Daza', 'asiscomercial1@losretales.co', NULL, '$2y$12$x9yRzpffE6mx/FBDeICIN.3VTPZZ6TZi923/qDFuNimQ3KpSoy4oO', 'DIRCOMERCIAL', 'Digitador-Ap', NULL, '2024-04-20 19:21:51', '2024-04-20 19:21:51'),
(5, 'Fabian Enriquez', 'dircompras@losretales.co', NULL, '$2y$12$scO39We7IEJCBWJt83ihLOYsNq8xfxRuTPrtxZRBui6f3v467tHOm', 'DIRCOMPRAS NACIONALES', 'Digitador-Ap', NULL, '2024-04-20 19:22:52', '2024-04-20 19:22:52'),
(6, 'Carlos Gil', 'analistacompras2@losretales.co', NULL, '$2y$12$mGsar26z9l58ZvMkDT0bKOxoQjBcY5m91xPfvYGX/ZnuUI./waRZ6', 'DIRCOMPRAS NACIONALES', 'Digitador', NULL, '2024-04-20 19:23:45', '2024-04-20 19:23:45'),
(7, 'Paola Jaramillo', 'asistesoreria@losretales.co', NULL, '$2y$12$LxSajqEQFNRArBhlFA8B2Odc0xYSicHhlvrrxlKOhqkeM.tmfKWni', 'DIRFINANCIERO', 'Pagador', NULL, '2024-04-20 19:24:44', '2024-04-20 19:24:44'),
(8, 'Andrea Ramirez', 'auxiliarcontable1@losretales.co', NULL, '$2y$12$6LxWUZ1I7javo.12JPDKS.OIfXUuC9DcCHaT2CSq01xcUfFWZml4K', 'DIRFINANCIERO', 'Cau-Pag', NULL, '2024-04-20 19:25:47', '2024-04-20 19:25:47'),
(9, 'Ronaldo Viteri', 'coordinadorgestionhumana@losretales.co', NULL, '$2y$12$GG.n3.c2Tn2.aOyjz3vojOF7omYSf7AjqefVCOLqGUnVWNqK6CmrW', 'DIRGESTION HUMANA', 'Digitador-Ap', NULL, '2024-04-20 19:27:07', '2024-04-20 19:27:07'),
(10, 'Brian Castaño', 'dirlogistica@losretales.co', NULL, '$2y$12$UvYd14E.2J.bwyC.zyulheUAFlv5WZqp2liVIJfFwQ9sQHABmnOFC', 'DIRLOGISTICA', 'Digitador-Ap', NULL, '2024-04-20 19:27:50', '2024-04-20 19:27:50'),
(11, 'Brayan Ordoñez', 'auxiliarsistemas@losretales.co', NULL, '$2y$12$hL0NlCHsmKl8u9oIzq3wFe0ZiBawlZ6DGpO0kwXNO3Dwi7EOj9xHS', 'INFORMATICA Y MANTENIMIENTO', 'Digitador-Ap', NULL, '2024-04-20 19:28:37', '2024-04-20 19:28:37'),
(12, 'Principal', 'centro@losretales.co', NULL, '$2y$12$//VzUbNOaPMl.LVzFjJ7Fuxt3JOZDo/UA5ET3hi4txRkkjyIzYxaG', 'PUNTOS DE VENTA', 'Digitador-Ap', NULL, '2024-04-20 19:29:17', '2024-04-20 19:29:17'),
(13, 'Alameda', 'alameda@losretales.co', NULL, '$2y$12$3T2RSkafMbbiT/HsSlaGaejjAJ372LNYDNdf96JyqK8fNIo3zk1zO', 'PUNTOS DE VENTA', 'Digitador-Ap', NULL, '2024-04-20 19:30:16', '2024-04-20 19:30:16'),
(14, 'Lopez', 'lopez@losretales.co', NULL, '$2y$12$lcVI.cGcCILguYYWInbHZerrv4B1izPNfCR9KQpdrrMqDdPtWc7I6', 'PUNTOS DE VENTA', 'Digitador-Ap', NULL, '2024-04-20 19:31:45', '2024-04-20 19:31:45'),
(15, 'Jamundi', 'jamudi@losretales.co', NULL, '$2y$12$n0nMtG1/eSLHnoU7x6kQSuzPpjqTl5WR6TJLkkOAvFj5rEql12XBG', 'PUNTOS DE VENTA', 'Digitador-Ap', NULL, '2024-04-20 19:32:32', '2024-04-20 19:32:32');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
