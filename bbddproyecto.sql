-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2024 a las 12:35:23
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
-- Base de datos: `bbddproyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instalaciones`
--

CREATE TABLE `instalaciones` (
  `id_instalacion` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `instalaciones`
--

INSERT INTO `instalaciones` (`id_instalacion`, `nombre`) VALUES
(1, 'Pista de Padel'),
(2, 'Cuarto Comunitario'),
(18, 'Cuarto Basura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `estado` varchar(50) DEFAULT 'disponible',
  `id_usuario` int(11) DEFAULT NULL,
  `id_instalacion` int(11) DEFAULT NULL,
  `id_turno` int(11) DEFAULT NULL,
  `fechaReserva` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `estado`, `id_usuario`, `id_instalacion`, `id_turno`, `fechaReserva`) VALUES
(9, 'reservado', 2, 1, 1, '2024-04-12'),
(10, 'reservado', 2, 1, 6, '2024-04-12'),
(11, 'reservado', 2, 1, 6, '2024-04-09'),
(12, 'reservado', 2, 1, 1, '2024-04-08'),
(13, 'reservado', 2, 1, 7, '2024-04-05'),
(14, 'reservado', 2, 1, 4, '2024-04-02'),
(15, 'cancelado', 2, 1, 1, '2024-04-17'),
(16, 'cancelado', 2, 1, 2, '2024-04-17'),
(17, 'cancelado', 2, 1, 7, '2024-04-18'),
(18, 'cancelado', 2, 1, 6, '2024-04-18'),
(19, 'reservado', 4, 1, 7, '2024-04-18'),
(20, 'cancelado', 2, 1, 1, '2024-04-19'),
(21, 'reservado', 2, 1, 1, '2024-04-19'),
(22, 'reservado', 2, 1, 2, '2024-04-19'),
(23, 'reservado', 3, 1, 1, '2024-04-25'),
(24, 'cancelado', 3, 1, 5, '2024-04-30'),
(25, 'cancelado', 3, 1, 1, '2024-04-29'),
(26, 'reservado', 3, 1, 4, '2024-04-29'),
(27, 'reservado', 3, 2, 9, '2024-04-30'),
(28, 'cancelado', 3, 1, 6, '2024-04-30'),
(29, 'cancelado', 3, 1, 1, '2024-04-30'),
(30, 'cancelado', 3, 1, 1, '2024-05-01'),
(31, 'reservado', 3, 1, 6, '2024-05-02'),
(32, 'cancelado', 3, 1, 4, '2024-05-10'),
(33, 'reservado', 2, 1, 6, '2024-05-01'),
(34, 'reservado', 3, 1, 6, '2024-05-10'),
(40, 'cancelado', 2, 2, 9, '2024-05-17'),
(41, 'cancelado', 2, 2, 9, '2024-05-25'),
(42, 'reservado', 3, 1, 6, '2024-05-19'),
(43, 'reservado', 4, 1, 1, '2024-05-26'),
(44, 'cancelado', 2, 1, 1, '2024-05-24'),
(45, 'Cancelado', 4, 2, 9, '2024-05-28'),
(46, 'cancelado', 2, 2, 9, '2024-05-28'),
(47, 'cancelado', 3, 1, 3, '2024-05-22'),
(48, 'reservado', 3, 1, 6, '2024-05-08'),
(49, 'reservado', 2, 1, 7, '2024-05-09'),
(50, 'reservado', 2, 1, 1, '2024-05-03'),
(51, 'cancelado', 2, 1, 6, '2024-05-21'),
(52, 'cancelado', 2, 1, 2, '2024-05-31'),
(53, 'cancelado', 2, 1, 1, '2024-05-21'),
(54, 'cancelado', 2, 1, 5, '2024-05-29'),
(55, 'Cancelado', 2, 1, 6, '2024-05-29'),
(56, 'cancelado', 2, 1, 2, '2024-05-21'),
(57, 'cancelado', 2, 1, 7, '2024-05-29'),
(58, 'reservado', 2, 1, 1, '2024-05-25'),
(59, 'cancelado', 2, 2, 9, '2024-05-23'),
(60, 'Cancelado', 2, 2, 9, '2024-05-30'),
(61, 'cancelado', 2, 1, 7, '2024-05-22'),
(62, 'reservado', 4, 1, 7, '2024-05-12'),
(63, 'Cancelado', 2, 2, 9, '2024-06-08'),
(64, 'cancelado', 2, 1, 7, '2024-05-28'),
(65, 'cancelado', 2, 1, 5, '2024-05-14'),
(66, 'cancelado', 2, 1, 7, '2024-05-13'),
(67, 'reservado', 2, 1, 3, '2024-05-19'),
(68, 'cancelado', 2, 1, 6, '2024-05-13'),
(69, 'reservado', 2, 1, 6, '2024-05-20'),
(71, 'reservado', 3, 1, 3, '2024-05-25'),
(72, 'cancelado', 2, 1, 5, '2024-05-30'),
(73, 'Cancelado', 3, 1, 3, '2024-05-31'),
(74, 'Cancelado', 3, 1, 7, '2024-05-27'),
(75, 'Reservado', 3, 2, 9, '2024-05-29'),
(76, 'Cancelado', 2, 2, 9, '2024-06-26'),
(77, 'Cancelado', 3, 1, 2, '2024-05-27'),
(78, 'Cancelado', 2, 2, 8, '2024-05-27'),
(79, 'Cancelado', 2, 1, 1, '2024-06-05'),
(80, 'Cancelado', 3, 1, 1, '2024-06-04'),
(81, 'Cancelado', 4, 1, 5, '2024-06-19'),
(82, 'Reservado', 4, 1, 7, '2024-06-20'),
(83, 'Cancelado', 2, 1, 2, '2024-06-01'),
(84, 'Reservado', 2, 1, 3, '2024-05-28'),
(85, 'Cancelado', 2, 1, 6, '2024-05-28'),
(86, 'Reservado', 2, 1, 6, '2024-05-27'),
(87, 'Reservado', 2, 1, 5, '2024-05-28'),
(88, 'Reservado', 3, 2, 8, '2024-05-28'),
(89, 'Reservado', 2, 1, 4, '2024-05-27'),
(90, 'Reservado', 2, 1, 2, '2024-06-11'),
(91, 'Reservado', 3, 1, 6, '2024-06-09'),
(92, 'Reservado', 3, 2, 8, '2024-06-02'),
(93, 'Reservado', 3, 1, 1, '2024-05-31'),
(94, 'Reservado', 2, 2, 8, '2024-06-13'),
(95, 'Cancelado', 2, 1, 1, '2024-05-30'),
(96, 'Reservado', 3, 2, 9, '2024-05-30'),
(97, 'Reservado', 3, 2, 9, '2024-06-08'),
(98, 'Reservado', 4, 1, 3, '2024-06-19'),
(99, 'Cancelado', 18, 1, 6, '2024-05-31'),
(100, 'Cancelado', 3, 2, 9, '2024-06-21'),
(101, 'Reservado', 16, 1, 7, '2024-06-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id_turno` int(11) NOT NULL,
  `id_instalacion` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id_turno`, `id_instalacion`, `hora_inicio`, `hora_fin`) VALUES
(1, 1, '09:00:00', '10:30:00'),
(2, 1, '10:30:00', '12:00:00'),
(3, 1, '12:00:00', '13:30:00'),
(4, 1, '13:30:00', '15:00:00'),
(5, 1, '17:00:00', '18:30:00'),
(6, 1, '18:30:00', '20:00:00'),
(7, 1, '20:00:00', '21:30:00'),
(8, 2, '10:00:00', '14:00:00'),
(9, 2, '17:00:00', '21:00:00'),
(15, 18, '11:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `portal` int(11) NOT NULL,
  `piso` varchar(10) NOT NULL,
  `letra` char(1) NOT NULL,
  `email` varchar(255) NOT NULL,
  `perfil` varchar(15) NOT NULL DEFAULT 'usuario' CHECK (`perfil` in ('usuario','administrador'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `fullname`, `portal`, `piso`, `letra`, `email`, `perfil`) VALUES
(2, 'Juan Perez Martin', 3, '2', 'A', 'juanpe66@example.com', 'usuario'),
(3, 'Carlos Moreno Guerrero', 2, 'BAJO', 'B', 'carlitosmorgue@example.com', 'administrador'),
(4, 'Maria Lopez Arias', 6, '2', 'D', 'marialopezarias@example.com', 'usuario'),
(6, 'Alberto Hermoso Prado', 2, 'ATICO', 'D', 'alber99@gmail.com', 'usuario'),
(16, 'Nuria Ribera Clavijo', 4, '1', 'A', 'nuriri80@gmail.com', 'usuario'),
(18, 'Patricia Potenciano Pelaez', 5, '1', 'D', 'patripot95@example.com', 'usuario'),
(20, 'Vanesa Grande Caballero', 6, 'BAJO', 'B', 'vanuqui@gmail.com', 'usuario'),
(30, 'Manuel Peñato Lucas', 1, '3', 'B', 'manupeñato@gmail.com', 'usuario'),
(32, 'Lucia Arroyo Requena', 3, '2', 'C', 'luciarroyo980@example.com', 'usuario'),
(43, 'Soraya Soto Carmona', 5, 'ATICO', 'C', 'sorysory@gmail.com', 'usuario'),
(45, 'Javier Sanchez Delgado', 1, '1', 'A', 'javijavito88@gmail.com', 'usuario'),
(47, 'Luis Martino Indalo', 5, '3', 'C', 'lusito@gmail.com', 'usuario'),
(48, 'Salvador Crespo Illa', 2, '1', 'B', 'salvacrespilla@gmail.com', 'usuario'),
(50, 'Juan Escobedo Garcia', 1, '3', 'A', 'juanescobedo@gmail.com', 'usuario'),
(53, 'Antonio Luque Santos', 1, '2', 'B', 'tonyluque900@gmail.com', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  ADD PRIMARY KEY (`id_instalacion`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_id_turno` (`id_turno`),
  ADD KEY `fk_id_usuario` (`id_usuario`),
  ADD KEY `fk_id_instalacion` (`id_instalacion`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id_turno`),
  ADD KEY `fk_instalacion` (`id_instalacion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `direccion_unica` (`portal`,`piso`,`letra`),
  ADD UNIQUE KEY `email_unico` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  MODIFY `id_instalacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_id_instalacion` FOREIGN KEY (`id_instalacion`) REFERENCES `instalaciones` (`id_instalacion`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_id_turno` FOREIGN KEY (`id_turno`) REFERENCES `turnos` (`id_turno`),
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `fk_instalacion` FOREIGN KEY (`id_instalacion`) REFERENCES `instalaciones` (`id_instalacion`) ON DELETE CASCADE,
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`id_instalacion`) REFERENCES `instalaciones` (`id_instalacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
