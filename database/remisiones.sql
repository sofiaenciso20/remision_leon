-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2025 a las 22:11:08
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
-- Base de datos: `remisiones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre_cliente` varchar(150) NOT NULL,
  `tipo_cliente` enum('empresa','persona') DEFAULT 'persona',
  `nit` varchar(50) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre_cliente`, `tipo_cliente`, `nit`, `direccion`, `telefono`, `correo`) VALUES
(1, 'luisa enciso', 'persona', '1106226099', 'Manzana L casa 32 Tolima Grande', '3144644540', 'luegar.25@gmail.com'),
(2, 'paula enciso', 'persona', '1012353999', 'Manzana L casa 32 Tolima Grande', '3028677343', 'sanpaula@gmail.com'),
(3, 'patricia gomez', 'persona', '1012300099', 'Manzana L casa 32 Tolima Grande', '3022987600', 'pati@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `nombre_estado`) VALUES
(3, 'Anulado'),
(2, 'Entregado'),
(1, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items_remisionados`
--

CREATE TABLE `items_remisionados` (
  `id_item` int(11) NOT NULL,
  `id_remision` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `descripcion` varchar(200) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `valor_unitario` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `items_remisionados`
--

INSERT INTO `items_remisionados` (`id_item`, `id_remision`, `id_producto`, `descripcion`, `cantidad`, `valor_unitario`) VALUES
(37, 21, 2, 'caja de carton ', 100, 5000.00),
(38, 22, 3, 'ROMAN SPIRIT – VOL. I', 1, 0.00),
(39, 22, 2, 'caja de carton ', 9, 0.00),
(40, 23, 2, 'caja de carton ', 1, 0.00),
(41, 24, 16, 'agua en botella', 1, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id_movimiento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `tipo_movimiento` enum('entrada','salida') NOT NULL COMMENT 'entrada: devolución/compra, salida: remisión',
  `cantidad` int(11) NOT NULL,
  `stock_anterior` int(11) NOT NULL,
  `stock_nuevo` int(11) NOT NULL,
  `motivo` varchar(100) NOT NULL COMMENT 'remision, devolucion, compra, ajuste',
  `id_remision` int(11) DEFAULT NULL COMMENT 'Si el movimiento está relacionado con una remisión',
  `id_usuario` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_movimiento` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_inventario`
--

INSERT INTO `movimientos_inventario` (`id_movimiento`, `id_producto`, `tipo_movimiento`, `cantidad`, `stock_anterior`, `stock_nuevo`, `motivo`, `id_remision`, `id_usuario`, `observaciones`, `fecha_movimiento`) VALUES
(1, 16, 'salida', 1, 40, 39, 'remision', 24, 1, 'Remisión #4', '2025-10-10 17:01:42'),
(2, 15, 'entrada', 10, 100, 110, 'ajuste_manual', NULL, 1, 'compraron 10 pacas mas', '2025-10-16 16:32:07'),
(3, 15, 'salida', 20, 110, 90, 'ajuste_manual', NULL, 1, 'sacaron 20 pacas de agua', '2025-10-16 16:32:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_contacto`
--

CREATE TABLE `personas_contacto` (
  `id_persona` int(11) NOT NULL,
  `nombre_persona` varchar(100) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas_contacto`
--

INSERT INTO `personas_contacto` (`id_persona`, `nombre_persona`, `cargo`, `telefono`, `correo`, `id_cliente`) VALUES
(1, 'sandra gallego', 'recepcionista', '3022920000', 'sandragallego@gmail.com', 1),
(2, 'sandra gallego', 'recepcionista', '3022927343', 'sandragallego@gmail.com', 2),
(3, 'katherin gallego ', 'ventas ', '3022927654', 'katherinn@gmail.com', 3),
(4, 'katherin gallego ', 'ventas ', '3022927654', 'katherinn@gmail.com', 3),
(5, 'katherin gallego ', 'ventas ', '3022927654', 'katherinn@gmail.com', 3),
(6, 'pepito', 'fgdfgfgg', '2423344545', 'zcxcxcvvv@gmail.com', 3),
(7, 'pepito', 'fgdfgfgg', '2423344545', 'zcxcxcvvv@gmail.com', 1),
(8, 'pepito', 'fgdfgfgg', '2423344545', 'zcxcxcvvv@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(200) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `maneja_inventario` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indica si el producto maneja inventario',
  `stock_actual` int(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad actual en inventario',
  `stock_minimo` int(11) NOT NULL DEFAULT 0 COMMENT 'Stock mínimo para alertas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `fecha_creacion`, `maneja_inventario`, `stock_actual`, `stock_minimo`) VALUES
(1, 'bolsas de papel', '2025-09-24 17:10:17', 0, 100, 0),
(2, 'caja de carton ', '2025-09-24 17:10:40', 0, 100, 0),
(3, 'ROMAN SPIRIT – VOL. I', '2025-10-01 13:31:45', 0, 100, 0),
(4, 'cafe', '2025-10-10 21:57:14', 1, 100, 10),
(5, 'azucar', '2025-10-10 21:57:14', 1, 50, 20),
(6, 'aromaticas', '2025-10-10 21:57:14', 1, 10, 15),
(7, 'bolsas de basura', '2025-10-10 21:57:14', 1, 40, 30),
(8, 'servilletas', '2025-10-10 21:57:14', 1, 100, 50),
(9, 'bizcochos', '2025-10-10 21:57:14', 1, 100, 25),
(10, 'galletas', '2025-10-10 21:57:14', 1, 100, 30),
(11, 'melcochas', '2025-10-10 21:57:14', 1, 1000, 40),
(12, 'bolsas de refrigerio', '2025-10-10 21:57:14', 1, 100, 100),
(13, 'cajas de refrigerio', '2025-10-10 21:57:14', 1, 100, 50),
(14, 'jugo en caja', '2025-10-10 21:57:14', 1, 100, 40),
(15, 'agua en bolsa', '2025-10-10 21:57:14', 1, 90, 60),
(16, 'agua en botella', '2025-10-10 21:57:14', 1, 39, 80),
(17, 'camisas amarillas', '2025-10-10 21:57:14', 1, 70, 25),
(18, 'camisas negras', '2025-10-10 21:57:14', 1, 42, 25),
(19, 'busos logistica', '2025-10-10 21:57:14', 1, 10, 20),
(20, 'busos aseo', '2025-10-10 21:57:14', 1, 100, 20),
(21, 'camisa manga larga', '2025-10-10 21:57:14', 1, 100, 15),
(22, 'jeans', '2025-10-10 21:57:14', 1, 10, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remisiones`
--

CREATE TABLE `remisiones` (
  `id_remision` int(11) NOT NULL,
  `numero_remision` int(11) NOT NULL,
  `fecha_emision` datetime DEFAULT current_timestamp(),
  `id_cliente` int(11) DEFAULT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `remisiones`
--

INSERT INTO `remisiones` (`id_remision`, `numero_remision`, `fecha_emision`, `id_cliente`, `id_persona`, `id_usuario`, `observaciones`, `id_estado`) VALUES
(21, 1, '2025-10-01 00:00:00', 1, 7, 1, 'llevar a topacio', 1),
(22, 2, '2025-10-01 00:00:00', 3, 4, 1, 'ppp', 1),
(23, 3, '2025-10-01 00:00:00', 2, 2, 1, '0000', 1),
(24, 4, '2025-10-11 00:00:00', 3, 3, 1, 'cvxcvxcv', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password_hash`, `created_at`, `name`) VALUES
(1, 'sofia.enciso', '$2y$10$TsubdWgeIU0yejBeD0dCA.3j5NnRJ741qx256cDEhwWWD3Ea7rB8K', '2025-09-08 14:11:41', 'Sofia Enciso');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `nit` (`nit`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `nombre_estado` (`nombre_estado`);

--
-- Indices de la tabla `items_remisionados`
--
ALTER TABLE `items_remisionados`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_remision` (`id_remision`),
  ADD KEY `fk_items_remisionados_productos` (`id_producto`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_remision` (`id_remision`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `personas_contacto`
--
ALTER TABLE `personas_contacto`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `nombre_producto` (`nombre_producto`);

--
-- Indices de la tabla `remisiones`
--
ALTER TABLE `remisiones`
  ADD PRIMARY KEY (`id_remision`),
  ADD UNIQUE KEY `numero_remision` (`numero_remision`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `items_remisionados`
--
ALTER TABLE `items_remisionados`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `personas_contacto`
--
ALTER TABLE `personas_contacto`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `remisiones`
--
ALTER TABLE `remisiones`
  MODIFY `id_remision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `items_remisionados`
--
ALTER TABLE `items_remisionados`
  ADD CONSTRAINT `fk_items_remisionados_productos` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `items_remisionados_ibfk_1` FOREIGN KEY (`id_remision`) REFERENCES `remisiones` (`id_remision`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `fk_movimientos_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_movimientos_remision` FOREIGN KEY (`id_remision`) REFERENCES `remisiones` (`id_remision`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_movimientos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `personas_contacto`
--
ALTER TABLE `personas_contacto`
  ADD CONSTRAINT `personas_contacto_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `remisiones`
--
ALTER TABLE `remisiones`
  ADD CONSTRAINT `remisiones_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `remisiones_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `personas_contacto` (`id_persona`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `remisiones_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `remisiones_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
