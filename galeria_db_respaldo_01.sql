-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-03-2026 a las 16:43:36
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
-- Base de datos: `galeria_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artista`
--

CREATE TABLE `artista` (
  `id_artista` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `nacionalidad` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `telefono` int(11) NOT NULL,
  `password` varchar(250) NOT NULL,
  `foto_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `artista`
--

INSERT INTO `artista` (`id_artista`, `nombre`, `apellido`, `email`, `fecha_nacimiento`, `nacionalidad`, `usuario`, `telefono`, `password`, `foto_perfil`) VALUES
(1, 'alfred', 'gustavo', 'susas@gamil.com', '2016-03-02', 'peruano', 'alfredo', 41485652, 'papita', 1211),
(30810283, 'jose', 'silva', 'panchopansa43@gmail.com', '0000-00-00', '', 'poche', 0, '$2y$10$cTC7XW5X2bvBMnQjV7mM1.bsES8LRrS7zUzV9ftfgazuXo.eCMdIi', 0),
(40810284, 'carlos', 'guerra', 'j6828611@gmail.com', '0000-00-00', '', 'pocheeeee', 0, '$2y$10$X6RhfXrWZKrbY.XIus0Ja./oCdLUMTPwzzTyq6YakBlBaHy4G2Q6W', 0),
(60810283, 'pepe', 'armandomonte', 'panchopansa43@gmail.com', '0000-00-00', 'peruano', 'poche3', 2147483647, '$2y$10$cHxvR.7cEZSrD9IIhqb4AOxDxhelu8Vwy21DNQMnObLBXtHSkcNP.', 0),
(70810283, 'albaricoke', 'munos', 'panchopansa43@gmail.com', '2003-12-19', 'Nigeria', 'poche4', 2147483647, '$2y$10$cJW8e/gJGbjf4WfaWxcoOONiJbclaIrPBA0YMZfZ07UwVpIFeAUnq', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ceramica`
--

CREATE TABLE `ceramica` (
  `id_ceramica` int(11) NOT NULL,
  `arcilla` varchar(200) NOT NULL,
  `tecnica` varchar(200) NOT NULL,
  `peso` float NOT NULL,
  `alto` float NOT NULL,
  `ancho` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprador`
--

CREATE TABLE `comprador` (
  `id_comprador` int(11) NOT NULL,
  `apellido` varchar(200) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `telefono` int(11) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `tcredito` int(5) NOT NULL,
  `cod_seguridad` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `p1` varchar(200) NOT NULL,
  `p2` varchar(200) NOT NULL,
  `p3` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comprador`
--

INSERT INTO `comprador` (`id_comprador`, `apellido`, `nombre`, `email`, `telefono`, `direccion`, `tcredito`, `cod_seguridad`, `usuario`, `password`, `p1`, `p2`, `p3`) VALUES
(90810283, 'pepe', 'julian', 'panchopansa43@gmail.com', 2147483647, 'tu corazon bb grrr', 4321, 2751, 'poche5', '$2y$10$QMZLtBG60VhyFe3Ipm7squtO3/yTxIAmSx8JI/o3DcflOin9PnO/W', 'p1', 'p2', 'p3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido` varchar(200) NOT NULL,
  `telefono` int(11) NOT NULL,
  `salario` float NOT NULL,
  `puesto` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envio`
--

CREATE TABLE `envio` (
  `id_envio` int(11) NOT NULL,
  `id_comprador` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `fecha_envio` datetime NOT NULL,
  `fecha_entrega` datetime NOT NULL,
  `id_venta` int(11) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `cod_postal` varchar(50) NOT NULL,
  `ciudad` varchar(200) NOT NULL,
  `pais` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escultura`
--

CREATE TABLE `escultura` (
  `id_escultura` int(11) NOT NULL,
  `material` varchar(200) NOT NULL,
  `peso` float NOT NULL,
  `alto` float NOT NULL,
  `ancho` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotografia`
--

CREATE TABLE `fotografia` (
  `id_fotografia` int(11) NOT NULL,
  `tecnica` varchar(200) NOT NULL,
  `papel` varchar(200) NOT NULL,
  `alto` float NOT NULL,
  `ancho` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `ID` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`ID`, `nombre`) VALUES
(1, 'Pintura'),
(2, 'Escultura'),
(3, 'Fotografía'),
(4, 'Ceramica'),
(5, 'Orfebrería');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero_artista`
--

CREATE TABLE `genero_artista` (
  `id_artista_genero` int(11) NOT NULL,
  `id_artista` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero_artista`
--

INSERT INTO `genero_artista` (`id_artista_genero`, `id_artista`, `id_genero`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obra`
--

CREATE TABLE `obra` (
  `id_obra` int(11) NOT NULL,
  `id_artista` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `precio` int(11) NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `obra`
--

INSERT INTO `obra` (`id_obra`, `id_artista`, `id_genero`, `nombre`, `precio`, `fecha_publicacion`, `status`) VALUES
(9, 70810283, 1, '69ad6a8ec90af.jpg', 0, '2026-03-08 12:24:46', 'disponible'),
(11, 70810283, 2, '69ad6dbd1c5e6.jpg', 0, '2026-03-08 12:38:21', 'disponible'),
(12, 70810283, 1, '69ad6f3b11db7_lasmeninas.jpg', 0, '2026-03-08 12:44:43', 'disponible'),
(13, 70810283, 1, '69ad6f6c1bc5e_mujer de perlas.jpg', 0, '2026-03-08 12:45:32', 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orferbreria`
--

CREATE TABLE `orferbreria` (
  `id_orfebreria` int(11) NOT NULL,
  `material` varchar(200) NOT NULL,
  `tecnica` varchar(200) NOT NULL,
  `peso` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pintura`
--

CREATE TABLE `pintura` (
  `id_pintura` int(11) NOT NULL,
  `tecnica` varchar(200) NOT NULL,
  `soporte` varchar(200) NOT NULL,
  `alto` float NOT NULL,
  `ancho` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id_venta` int(11) NOT NULL,
  `id_comprador` int(11) NOT NULL,
  `id_obra` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo_de_pago` varchar(200) NOT NULL,
  `precio_venta` float NOT NULL,
  `IVA` float NOT NULL,
  `tarifa_museo` float NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `artista`
--
ALTER TABLE `artista`
  ADD PRIMARY KEY (`id_artista`);

--
-- Indices de la tabla `ceramica`
--
ALTER TABLE `ceramica`
  ADD PRIMARY KEY (`id_ceramica`);

--
-- Indices de la tabla `comprador`
--
ALTER TABLE `comprador`
  ADD PRIMARY KEY (`id_comprador`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `envio`
--
ALTER TABLE `envio`
  ADD PRIMARY KEY (`id_envio`),
  ADD KEY `FKcomprador` (`id_comprador`),
  ADD KEY `FKventa` (`id_venta`);

--
-- Indices de la tabla `escultura`
--
ALTER TABLE `escultura`
  ADD PRIMARY KEY (`id_escultura`);

--
-- Indices de la tabla `fotografia`
--
ALTER TABLE `fotografia`
  ADD PRIMARY KEY (`id_fotografia`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `genero_artista`
--
ALTER TABLE `genero_artista`
  ADD PRIMARY KEY (`id_artista_genero`),
  ADD KEY `id_genero` (`id_genero`),
  ADD KEY `id_artista` (`id_artista`);

--
-- Indices de la tabla `obra`
--
ALTER TABLE `obra`
  ADD PRIMARY KEY (`id_obra`),
  ADD KEY `id_artista` (`id_artista`),
  ADD KEY `id_genero` (`id_genero`);

--
-- Indices de la tabla `orferbreria`
--
ALTER TABLE `orferbreria`
  ADD PRIMARY KEY (`id_orfebreria`);

--
-- Indices de la tabla `pintura`
--
ALTER TABLE `pintura`
  ADD PRIMARY KEY (`id_pintura`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_comprador` (`id_comprador`),
  ADD KEY `id_obra` (`id_obra`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `artista`
--
ALTER TABLE `artista`
  MODIFY `id_artista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70810284;

--
-- AUTO_INCREMENT de la tabla `ceramica`
--
ALTER TABLE `ceramica`
  MODIFY `id_ceramica` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comprador`
--
ALTER TABLE `comprador`
  MODIFY `id_comprador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90810284;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `envio`
--
ALTER TABLE `envio`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `escultura`
--
ALTER TABLE `escultura`
  MODIFY `id_escultura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fotografia`
--
ALTER TABLE `fotografia`
  MODIFY `id_fotografia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `obra`
--
ALTER TABLE `obra`
  MODIFY `id_obra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `orferbreria`
--
ALTER TABLE `orferbreria`
  MODIFY `id_orfebreria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pintura`
--
ALTER TABLE `pintura`
  MODIFY `id_pintura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ceramica`
--
ALTER TABLE `ceramica`
  ADD CONSTRAINT `ceramica_ibfk_1` FOREIGN KEY (`id_ceramica`) REFERENCES `obra` (`id_obra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `envio`
--
ALTER TABLE `envio`
  ADD CONSTRAINT `envio_ibfk_1` FOREIGN KEY (`id_comprador`) REFERENCES `empleado` (`id_empleado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `envio_ibfk_2` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `escultura`
--
ALTER TABLE `escultura`
  ADD CONSTRAINT `escultura_ibfk_1` FOREIGN KEY (`id_escultura`) REFERENCES `obra` (`id_obra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `fotografia`
--
ALTER TABLE `fotografia`
  ADD CONSTRAINT `fotografia_ibfk_1` FOREIGN KEY (`id_fotografia`) REFERENCES `obra` (`id_obra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `genero_artista`
--
ALTER TABLE `genero_artista`
  ADD CONSTRAINT `genero_artista_ibfk_1` FOREIGN KEY (`id_artista`) REFERENCES `artista` (`id_artista`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `genero_artista_ibfk_2` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `obra`
--
ALTER TABLE `obra`
  ADD CONSTRAINT `obra_ibfk_1` FOREIGN KEY (`id_artista`) REFERENCES `artista` (`id_artista`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `obra_ibfk_2` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `orferbreria`
--
ALTER TABLE `orferbreria`
  ADD CONSTRAINT `orferbreria_ibfk_1` FOREIGN KEY (`id_orfebreria`) REFERENCES `obra` (`id_obra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pintura`
--
ALTER TABLE `pintura`
  ADD CONSTRAINT `pintura_ibfk_1` FOREIGN KEY (`id_pintura`) REFERENCES `obra` (`id_obra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_comprador`) REFERENCES `comprador` (`id_comprador`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_obra`) REFERENCES `obra` (`id_obra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
