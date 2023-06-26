-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 05-06-2023 a las 21:02:23
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_online`
--
CREATE DATABASE IF NOT EXISTS `tienda_online` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `tienda_online`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acceso_usuario`
--

DROP TABLE IF EXISTS `acceso_usuario`;
CREATE TABLE `acceso_usuario` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `activacion` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `token_password` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `password_request` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `acceso_usuario`
--

INSERT INTO `acceso_usuario` (`id`, `usuario`, `password`, `activacion`, `token`, `token_password`, `password_request`, `id_cliente`) VALUES
(1, 'Gabs', '1234', 0, 'b41781b576f4eb719fea65604268744b', NULL, 0, 4),
(2, 'Holitaa', '1234', 1, 'cd9b811e9f4f7431a9a817fc6c91d504', NULL, 0, 5),
(3, 'Gabi', '1234', 1, '256d1ee91fde9d0c016ebee6f79018f6', NULL, 0, 6),
(4, 'Alumno', '1234', 1, '5a34a3aaec90dfd8091ed5936e376ef9', NULL, 0, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caracteristicas`
--

DROP TABLE IF EXISTS `caracteristicas`;
CREATE TABLE `caracteristicas` (
  `id` int(11) NOT NULL,
  `caracteristica` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `caracteristicas`
--

INSERT INTO `caracteristicas` (`id`, `caracteristica`, `activo`) VALUES
(1, 'Talla', 1),
(2, 'Color', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `id_transaccion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `status` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `total` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `id_transaccion`, `fecha`, `status`, `email`, `id_cliente`, `total`) VALUES
(4, '0FN96614P70219536', '2023-05-24 23:02:38', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '1974'),
(5, '45P26902J6325452T', '2023-05-25 01:00:06', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '1974'),
(6, '6BY84793B2862931C', '2023-05-25 01:03:35', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '2260'),
(7, '63G58502GK554552B', '2023-06-01 17:40:11', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '658'),
(8, '6B3879948H5824103', '2023-06-01 18:17:23', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '2050'),
(9, '0CY612079M860040J', '2023-06-01 18:19:11', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '2050'),
(10, '0R488897CT2166101', '2023-06-02 00:07:15', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '5125'),
(11, '8CA86071X24152807', '2023-06-02 14:08:53', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '2025'),
(12, '7HJ92527JX5028607', '2023-06-02 14:12:38', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '1316'),
(13, '06E52550RP4084709', '2023-06-02 14:16:13', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '2050'),
(14, '3GP30478NS907801P', '2023-06-04 18:51:36', 'COMPLETED', 'sb-pt1ux26031189@personal.example.com', 2, '2260');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

DROP TABLE IF EXISTS `detalle_compra`;
CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `precio` int(10) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `id_compra`, `id_producto`, `nombre`, `precio`, `cantidad`) VALUES
(4, 6, 1, 'Leather chaps', 1025, 2),
(5, 6, 4, 'Pendientes modernos', 1235, 1),
(6, 7, 3, 'Leggins ondas', 658, 1),
(7, 8, 1, 'Leather chaps', 1025, 2),
(8, 10, 1, 'Leather chaps', 1025, 5),
(9, 11, 4, 'Pendientes modernos', 1235, 1),
(10, 11, 5, 'Body escotado', 790, 1),
(11, 12, 3, 'Leggins ondas', 658, 2),
(12, 13, 1, 'Leather chaps', 1025, 2),
(13, 14, 1, 'Leather chaps', 1025, 2),
(14, 14, 4, 'Pendientes modernos', 1235, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_prod_caracter`
--

DROP TABLE IF EXISTS `det_prod_caracter`;
CREATE TABLE `det_prod_caracter` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_caracteristica` int(11) NOT NULL,
  `valor` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `det_prod_caracter`
--

INSERT INTO `det_prod_caracter` (`id`, `id_producto`, `id_caracteristica`, `valor`, `stock`) VALUES
(1, 1, 1, '36', 10),
(2, 1, 1, '38', 10),
(3, 1, 1, '40', 10),
(4, 1, 1, '42', 10),
(5, 1, 2, 'Blanco', 30),
(6, 1, 2, 'Negro', 30),
(7, 1, 1, '34', 10),
(8, 1, 1, '32', 10),
(9, 2, 1, '36', 10),
(10, 2, 1, '38', 10),
(11, 2, 1, '40', 20),
(12, 2, 1, '42', 10),
(13, 2, 1, '44', 10),
(14, 2, 2, 'Blanco', 20),
(15, 2, 1, 'Negro', 20),
(16, 2, 2, 'Violeta', 20),
(17, 3, 1, '32', 10),
(18, 3, 1, '34', 10),
(19, 3, 1, '36', 10),
(20, 3, 2, 'Rosa tenue', 30),
(21, 4, 1, 'S', 10),
(22, 4, 1, 'M', 10),
(23, 4, 2, 'Plata', 20),
(24, 5, 1, '32', 20),
(25, 5, 1, '34', 10),
(26, 5, 1, '36', 10),
(27, 5, 1, '38', 20),
(29, 5, 1, '40', 10),
(30, 5, 1, '42', 20),
(31, 5, 2, 'Negro', 30),
(32, 5, 2, 'Blanco', 30),
(33, 5, 2, 'Cereza', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `precio` double(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `id_categoria`, `activo`) VALUES
(1, 'Leather chaps', 'Pantalones de cuero sintético de la mejor calidad.\r\nMade in Bulgaria.', 1024.99, 1, 1),
(2, 'Abrigo abullonado', 'Abrigo de piel sintética con hombreras redondeadas y cintura ajustada a la silueta.', 989.25, 2, 1),
(3, 'Leggins ondas', 'Leggins con trazos que se extienden redibujando la figura.', 657.99, 3, 1),
(4, 'Pendientes modernos', 'Joyas de plata y acero inoxidable con extensión alargada y diseño moderno.', 1234.95, 4, 1),
(5, 'Body escotado', 'Body con mangas largas y escote. Confeccionado con algodón 100%, poliamida 78%, elastano 22%.', 789.99, 5, 1),
(6, 'Body silver', 'Body de color plateado con formas redondeadas.', 779.69, 5, 1),
(7, 'Body ondas negro', 'Body con efecto transparencia y ondas. Tono en color negro con efecto transparencia similiar a la piel.', 755.99, 5, 1),
(8, 'Corset transparencias', 'Pieza de corset con transparencias de tejido ceñido y ajustable de forma discreta.', 569.99, 5, 1),
(9, 'Top estrellado', 'Top con transparencias y estrellas por toda la figura. Tejido muy flexible junto a cremallera lateral de fácil uso.', 388.95, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` tinyint(4) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_modifica` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `dni` varchar(20) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `telefono`, `estatus`, `fecha_alta`, `fecha_modifica`, `fecha_baja`, `dni`) VALUES
(4, 'Gabriel', 'Orellana Vásquez', 'gabrieldietista@gmail.com', '33333333', 1, '2023-05-20 20:21:03', NULL, NULL, 'x6666661'),
(5, 'Holita', 'Holita Holita', 'holita@gmail.com', '654654454', 1, '2023-05-22 02:43:39', NULL, NULL, '12345678o'),
(6, 'Gabriel', 'Orellana Vásquez', 'sirgabid@gmail.com', '6545353553', 1, '2023-05-22 17:57:20', NULL, NULL, 'x478548457j'),
(7, 'Alumno', '1', 'alumno@gmail.com', '67898871', 1, '2023-06-01 17:06:29', NULL, NULL, '34545543K');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acceso_usuario`
--
ALTER TABLE `acceso_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `det_prod_caracter`
--
ALTER TABLE `det_prod_caracter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_det_prod` (`id_producto`),
  ADD KEY `fk_det_caracter` (`id_caracteristica`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
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
-- AUTO_INCREMENT de la tabla `acceso_usuario`
--
ALTER TABLE `acceso_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `det_prod_caracter`
--
ALTER TABLE `det_prod_caracter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acceso_usuario`
--
ALTER TABLE `acceso_usuario`
  ADD CONSTRAINT `acceso_usuario_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `acceso_usuario` (`id`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id`),
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `det_prod_caracter`
--
ALTER TABLE `det_prod_caracter`
  ADD CONSTRAINT `fk_det_caracter` FOREIGN KEY (`id_caracteristica`) REFERENCES `caracteristicas` (`id`),
  ADD CONSTRAINT `fk_det_prod` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
