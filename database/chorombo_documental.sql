-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-09-2026 a las 03:50:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `chorombo_documental`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `tipo_documento_id` int(10) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id`, `titulo`, `tipo_documento_id`, `fecha`, `descripcion`, `archivo`, `created_at`, `updated_at`) VALUES
(1, 'Memo reunión de profesores', 1, '2026-09-01', 'Memo correspondiente a una reunión de profesores.', 'uploads/documentos/ARCHIVO1.pdf', '2026-09-01 16:00:00', '2026-09-01 16:00:00'),
(2, 'Oficio administrativo', 2, '2026-09-01', 'Oficio administrativo de ejemplo.', 'uploads/documentos/ARCHIVO2.pdf', '2026-09-01 16:05:00', '2026-09-01 16:05:00'),
(3, 'Citación de apoderado', 3, '2026-09-02', 'Citación de apoderado utilizada como registro de ejemplo.', 'uploads/documentos/ARCHIVO3.pdf', '2026-09-02 14:00:00', '2026-09-02 14:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_documento`
--

CREATE TABLE `tipos_documento` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tipos_documento`
--

INSERT INTO `tipos_documento` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Memo', '2026-08-30 18:54:40', '2026-08-30 18:54:40'),
(2, 'Oficio', '2026-08-30 18:54:40', '2026-08-30 18:54:40'),
(3, 'Citación de apoderado', '2026-08-30 18:54:40', '2026-08-30 18:54:40'),
(4, 'Acuerdo de apoderados', '2026-08-30 18:54:40', '2026-08-30 18:54:40'),
(5, 'Reunión comunal', '2026-08-30 18:54:40', '2026-08-30 18:54:40'),
(6, 'Permiso administrativo', '2026-08-30 18:54:40', '2026-08-30 18:54:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documentos_tipo` (`tipo_documento_id`),
  ADD KEY `idx_documentos_fecha` (`fecha`);

--
-- Indices de la tabla `tipos_documento`
--
ALTER TABLE `tipos_documento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipos_documento`
--
ALTER TABLE `tipos_documento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `fk_documentos_tipo` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
