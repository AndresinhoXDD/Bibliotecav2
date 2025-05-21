-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2025 a las 01:32:47
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
-- Base de datos: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor`
--

DROP SCHEMA IF EXISTS biblioteca ;

-- -----------------------------------------------------
-- Schema biblioteca
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS biblioteca DEFAULT CHARACTER SET utf8mb4 ;
USE biblioteca ;


CREATE TABLE `autor` (
  `autor_id` int(11) NOT NULL,
  `autor_nombre` varchar(100) NOT NULL,
  `autor_nacionalidad` varchar(50) DEFAULT NULL,
  `autor_fecha_nacimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `autor`
--

INSERT INTO `autor` (`autor_id`, `autor_nombre`, `autor_nacionalidad`, `autor_fecha_nacimiento`) VALUES
(1, 'Gabriel García Márquez', 'Colombiano', '1927-03-06'),
(2, 'Isabel Allende', 'Chilena', '1942-08-02'),
(3, 'Jorge Luis Borges', 'Argentino', '1899-08-24'),
(4, 'Octavio Paz', 'Mexicano', '1914-03-31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejemplar`
--

CREATE TABLE `ejemplar` (
  `ejemplar_id` int(11) NOT NULL,
  `ejemplar_libro_id` int(11) NOT NULL,
  `ejemplar_estado` enum('disponible','prestado','en_mora','perdido') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ejemplar`
--

INSERT INTO `ejemplar` (`ejemplar_id`, `ejemplar_libro_id`, `ejemplar_estado`) VALUES
(1, 1, 'prestado'),
(2, 1, 'prestado'),
(3, 2, 'disponible'),
(4, 3, 'en_mora'),
(5, 1, 'disponible'),
(6, 1, 'prestado'),
(7, 2, 'disponible'),
(8, 3, 'en_mora');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `libro_id` int(11) NOT NULL,
  `libro_titulo` varchar(255) NOT NULL,
  `libro_isbn` varchar(13) NOT NULL,
  `libro_copias_totales` int(11) DEFAULT 0,
  `libro_copias_disponibles` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`libro_id`, `libro_titulo`, `libro_isbn`, `libro_copias_totales`, `libro_copias_disponibles`) VALUES
(1, 'Cien años de soledad', '9780307474728', 5, 3),
(2, 'El amor en los tiempos del cólera', '9780307389732', 4, 4),
(3, 'Ficciones', '9788497596653', 6, 5),
(4, 'El laberinto de la soledad', '9789500815407', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libroautor_libroautor`
--

CREATE TABLE `libroautor_libroautor` (
  `libroautor_id` int(11) NOT NULL,
  `libroautor_libro_id` int(11) NOT NULL,
  `autor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libroautor_libroautor`
--

INSERT INTO `libroautor_libroautor` (`libroautor_id`, `libroautor_libro_id`, `autor_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 3),
(4, 4, 4),
(5, 1, 1),
(6, 2, 1),
(7, 3, 3),
(8, 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `prestamo_id` int(11) NOT NULL,
  `prestamo_prestatario_id` int(11) NOT NULL,
  `prestamo_bibliotecario_id` int(11) NOT NULL,
  `prestamo_fecha_prestamo` datetime DEFAULT current_timestamp(),
  `prestamo_fecha_devolucion_prevista` datetime NOT NULL,
  `prestamo_fecha_devolucion_real` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`prestamo_id`, `prestamo_prestatario_id`, `prestamo_bibliotecario_id`, `prestamo_fecha_prestamo`, `prestamo_fecha_devolucion_prevista`, `prestamo_fecha_devolucion_real`) VALUES
(1, 1, 2, '2025-05-18 14:09:03', '2025-05-24 00:00:00', NULL),
(2, 2, 2, '2025-05-18 14:09:03', '2025-05-25 00:00:00', NULL),
(3, 3, 2, '2025-05-18 14:09:03', '2025-05-26 00:00:00', NULL),
(4, 4, 2, '2025-05-18 14:09:03', '2025-05-27 00:00:00', NULL),
(5, 9, 2, '2025-05-18 14:09:57', '2025-05-10 00:00:00', NULL),
(6, 10, 2, '2025-05-18 14:10:10', '2025-05-21 00:00:00', '2025-05-20 18:26:26'),
(7, 11, 2, '2025-05-20 18:26:08', '2025-05-23 00:00:00', '2025-05-20 18:26:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamoejemplar`
--

CREATE TABLE `prestamoejemplar` (
  `prestamoejemplar_id` int(11) NOT NULL,
  `prestamoejemplar_prestamo_id` int(11) NOT NULL,
  `prestamoejemplar_ejemplar_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamoejemplar`
--

INSERT INTO `prestamoejemplar` (`prestamoejemplar_id`, `prestamoejemplar_prestamo_id`, `prestamoejemplar_ejemplar_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 1),
(6, 6, 3),
(7, 7, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestatario`
--

CREATE TABLE `prestatario` (
  `prestatario_id` int(11) NOT NULL,
  `prestatario_nombre` varchar(100) NOT NULL,
  `prestatario_identificacion` varchar(20) NOT NULL,
  `prestatario_fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestatario`
--

INSERT INTO `prestatario` (`prestatario_id`, `prestatario_nombre`, `prestatario_identificacion`, `prestatario_fecha_creacion`) VALUES
(1, 'Carlos Mendoza', '123456', '2025-05-18 14:04:47'),
(2, 'Ana Torres', '234567', '2025-05-18 14:04:47'),
(3, 'Luis García', '345678', '2025-05-18 14:04:47'),
(4, 'Marta Suárez', '456789', '2025-05-18 14:04:47'),
(9, 'Andrés Felipe', '11111111111', '2025-05-18 14:09:57'),
(10, 'YO', '222222222222', '2025-05-18 14:10:10'),
(11, 'SO', '54564123132135a', '2025-05-20 18:26:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL,
  `rol_nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rol_id`, `rol_nombre`) VALUES
(1, 'Administrador'),
(2, 'Bibliotecario'),
(3, 'Invitado'),
(4, 'Usuario_externo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(11) NOT NULL,
  `usuario_nombre` varchar(100) NOT NULL,
  `usuario_email` varchar(100) NOT NULL,
  `usuario_contrasena` varchar(255) NOT NULL,
  `usuario_rol_id` int(11) NOT NULL,
  `usuario_fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_nombre`, `usuario_email`, `usuario_contrasena`, `usuario_rol_id`, `usuario_fecha_registro`) VALUES
(1, 'Alejandro Martínez', 'alejandro@example.com', 'secret1', 1, '2025-05-18 14:09:03'),
(2, 'Mariana Ruiz', 'mariana@example.com', 'secret2', 2, '2025-05-18 14:09:03'),
(3, 'Santiago López', 'santiago@example.com', 'secret3', 2, '2025-05-18 14:09:03'),
(4, 'Elena Pérez', 'elena@example.com', 'secret4', 4, '2025-05-18 14:09:03');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`autor_id`),
  ADD UNIQUE KEY `nombre` (`autor_nombre`),
  ADD KEY `idx_autor_nombre` (`autor_nombre`);

--
-- Indices de la tabla `ejemplar`
--
ALTER TABLE `ejemplar`
  ADD PRIMARY KEY (`ejemplar_id`),
  ADD KEY `libro_id` (`ejemplar_libro_id`),
  ADD KEY `idx_ejemplar_estado` (`ejemplar_estado`);

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`libro_id`),
  ADD UNIQUE KEY `isbn` (`libro_isbn`),
  ADD KEY `idx_libro_titulo` (`libro_titulo`);

--
-- Indices de la tabla `libroautor_libroautor`
--
ALTER TABLE `libroautor_libroautor`
  ADD PRIMARY KEY (`libroautor_id`),
  ADD KEY `autor_id` (`autor_id`),
  ADD KEY `libroautor_ibfk_1` (`libroautor_libro_id`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`prestamo_id`),
  ADD KEY `prestatario_id` (`prestamo_prestatario_id`),
  ADD KEY `bibliotecario_id` (`prestamo_bibliotecario_id`);

--
-- Indices de la tabla `prestamoejemplar`
--
ALTER TABLE `prestamoejemplar`
  ADD PRIMARY KEY (`prestamoejemplar_id`),
  ADD KEY `ejemplar_id` (`prestamoejemplar_ejemplar_id`),
  ADD KEY `prestamoejemplar_ibfk_1` (`prestamoejemplar_prestamo_id`);

--
-- Indices de la tabla `prestatario`
--
ALTER TABLE `prestatario`
  ADD PRIMARY KEY (`prestatario_id`),
  ADD UNIQUE KEY `identificacion` (`prestatario_identificacion`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rol_id`),
  ADD UNIQUE KEY `nombre` (`rol_nombre`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`usuario_email`),
  ADD KEY `rol_id` (`usuario_rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autor`
--
ALTER TABLE `autor`
  MODIFY `autor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ejemplar`
--
ALTER TABLE `ejemplar`
  MODIFY `ejemplar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `libro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `libroautor_libroautor`
--
ALTER TABLE `libroautor_libroautor`
  MODIFY `libroautor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `prestamo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `prestamoejemplar`
--
ALTER TABLE `prestamoejemplar`
  MODIFY `prestamoejemplar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `prestatario`
--
ALTER TABLE `prestatario`
  MODIFY `prestatario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ejemplar`
--
ALTER TABLE `ejemplar`
  ADD CONSTRAINT `ejemplar_ibfk_1` FOREIGN KEY (`ejemplar_libro_id`) REFERENCES `libro` (`libro_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `libroautor_libroautor`
--
ALTER TABLE `libroautor_libroautor`
  ADD CONSTRAINT `libroautor_ibfk_1` FOREIGN KEY (`libroautor_libro_id`) REFERENCES `libro` (`libro_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `libroautor_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `autor` (`autor_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`prestamo_prestatario_id`) REFERENCES `prestatario` (`prestatario_id`),
  ADD CONSTRAINT `prestamo_ibfk_2` FOREIGN KEY (`prestamo_bibliotecario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `prestamoejemplar`
--
ALTER TABLE `prestamoejemplar`
  ADD CONSTRAINT `prestamoejemplar_ibfk_1` FOREIGN KEY (`prestamoejemplar_prestamo_id`) REFERENCES `prestamo` (`prestamo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestamoejemplar_ibfk_2` FOREIGN KEY (`prestamoejemplar_ejemplar_id`) REFERENCES `ejemplar` (`ejemplar_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`usuario_rol_id`) REFERENCES `rol` (`rol_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
