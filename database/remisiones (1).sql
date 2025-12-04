-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2025 a las 15:17:36
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
(1, 'RAPIFRIOS', 'empresa', '900278125', 'CALLE 16 1-22 SUR CENTRO ', '6082614200', 'contab.rapifrios@gmail.com'),
(2, 'HORNEADOS LA 5TA ESTRELLA SAS', 'empresa', '901260012', 'CRA 5 #23-30', '2635565', 'horneadoslaquinta@gmail.com'),
(3, 'UNIVERSIDAD DEL TOLIMA ', 'empresa', '890700640', 'CALLE 41 #1-02 SANTA ELENA PARTE ALTA', '2771212', 'atencionalciudadano@ut.edu.co'),
(4, 'RESTAURANTE DELIRIOS ', 'empresa', '551622834', 'CALLE 6 #3-60', '2774437', 'rociovargas09@hotmail.com'),
(5, 'GOBERNACION DEL TOLIMA ', 'empresa', '800113672', 'CRA 3 CALLE 10 Y 11 CENTRO ', '6082611111', 'notificaciones.judiciales@tolima.gov.co'),
(6, 'MURELLI - DEL CARBON', 'empresa', '5899559', 'CARRERA 45 SUR #161 - 180 PICALEÑA ', '6082695536', 'zeta_enc29@hotmail.com'),
(7, 'IBAL S.A E.P.S OFICIAL', 'empresa', '800089809', 'CRA 3 #1-04 BARRIO LA POLA ', '2611298', 'sgeneral@ibal.gov.co'),
(8, 'CORTOLIMA ', 'empresa', '890704536', 'CARREA 5 AVDA FERROCARRIL CALLE 44', '2654555', 'carolina.hurtado@cortolima.gov.co'),
(9, 'APP GICA', 'empresa', '900816750', 'VARIANTE IBAGUE VIA NUEVA TOTUMO ', '3005666345', 'facturacion.bogota@appgica.com.co'),
(10, 'FESTEJAMOS SAS', 'empresa', '901095022', 'CRA 9 A #79 BIS -51 RINCON DE LA CAMPIÑA ', '2685100', 'festejamos.sas@gmail.com'),
(11, 'AGROINVERSIONES EL CURAL ', 'empresa', '901249976', 'VIA TOTUMO ', '3176585155', 'lacteoselcural@yahoo.es'),
(12, 'COORPORACION CLUB CAMPESTRE DE IBAGUE ', 'empresa', '890703094', 'KM 5 VIA PICALEÑA', '2770151', '890703094@factureinbox.co'),
(13, 'SUPERMERCADOS MERCACENTRO SAS', 'empresa', '901370428', 'CRA 16 SUR 96_48 EL POBLADO ', '5152424', 'recepcionfe@mercacentro.com.co'),
(14, 'CAPITAL ACOSTA GROUP SAS', 'empresa', '901381194', 'DG 21-19 60 BARRIO CALAMBEO ', '3146831757', 'capitalacostagroupsas@gmail.com'),
(15, 'AV DISTRIBUIDORES ', 'empresa', '1110542007', 'MZ B CASA 30 BARRIO VALPARAISO ', '3147826053', 'andreacarlosama07@gmail.com'),
(16, 'FEDERACION NACIONAL DE CAFETEROS DE COLOMBIA ', 'empresa', '860007538', 'CALLE 73 # 8-13', '2739424', 'faelectronica.tolima@cafedecolombia.com'),
(17, ' UNIVERSIDAD UNIMINUTO ', 'empresa', '800116217', 'CALLE 81 B 72B-70', '2616968', 'facturacionradian@uniminuto.edu'),
(18, 'FENALCO ', 'empresa', '890700172', 'CRA 4B 31 A-17', '2771335', 'contadortolima@fenalco.com.co'),
(19, 'MARIA NUBIA SEGOVIA', 'persona', '28915213', 'AV FERROCARRIL #25-38', '3132463182', NULL),
(20, 'UNIVERSIDAD DE IBAGUE ', 'empresa', '890704382', 'CRA 22 CALLE 67 BARRIO AMBALA ', '2760010', '890704382@recepciondefacturas.co'),
(21, 'EXTRACRYL', 'empresa', '93373967', 'CALLE 25 AC -52', '2634096', 'pinturasextracril@yahoo.com'),
(22, 'LINA MARCELA CAMEDO ', 'persona', '1110512019-7', NULL, NULL, 'elpuertolm@gmail.com'),
(23, 'COMFENALCO ', 'empresa', '890700148', 'CALLE 37 CRA QUINTA ESQUINA ', '2670088', '890700148@factureinbox.co'),
(24, 'LEON GRAFICAS SAS ', 'empresa', '809012539-4', 'CALLE 14 #6-50 BARRIO PUEBLO NUEVO ', '3185780327', 'facturacion.electronica@leongraficas.com'),
(25, 'CAMARA DE COMERCIO DE IBAGUE', 'empresa', '890700622', 'CALLE 10 #3-76 PARQUE MURILLO TORO ', '2772000', 'recepcionfacturas@ccibague.org'),
(26, 'TRAVESIAS TURISTICAS', 'empresa', '14223229-8', 'CRA 8 #12-38 PUEBLO NUEVO ', '3153576479', 'bonillajesus203@gmail.com'),
(27, 'ARQUIDIOCESIS DE IBAGUE', 'empresa', '890700692', 'CALLE 10 #2-58', '6082620682', 'arquiteso@gmail.com'),
(28, 'JULIAN FERNANDO VARELA CABEZAS ', 'persona', '1110510622', 'CALLE 36 A #13-23 BARRIO GAITANA ', '3177266365', 'fernando.cabezas22@outlook.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_remision`
--

CREATE TABLE `detalle_remision` (
  `id_detalle` int(11) NOT NULL,
  `id_remision` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id_devolucion` int(11) NOT NULL,
  `id_remision` int(11) NOT NULL,
  `fecha_devolucion` datetime NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 'En alquiler'),
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
(1, 1, 23, 'BOTELLONES DE AGUA BRISA', 3, 0.00),
(2, 2, 24, 'STIKERS', 500, 0.00),
(3, 2, 14, 'jugo en caja', 350, 0.00),
(4, 2, 1, 'bolsas de papel', 350, 0.00),
(5, 3, 1, 'bolsas de papel', 250, 0.00),
(6, 3, 14, 'jugo en caja', 250, 0.00),
(7, 4, 26, 'ARAÑAS', 2, 0.00),
(8, 4, 25, 'PENDONES', 2, 0.00),
(9, 5, 27, 'RECIPIENTE', 1, 0.00),
(10, 6, 28, 'BISUTERIA', 1, 0.00),
(11, 7, 29, 'PLEGABLES 3 CUERPOS ', 500, 0.00),
(12, 8, 24, 'STIKERS', 6880, 0.00),
(13, 9, 30, 'VOLANTES', 6000, 0.00),
(14, 10, 31, 'CERTIFICADOS', 10, 0.00),
(15, 11, 34, 'MANILLAS', 500, 0.00),
(16, 11, 33, 'CENEFAS ', 11, 0.00),
(17, 11, 32, 'MARCACION PARA ATRIL', 1, 0.00),
(18, 12, 35, 'AGENDA PASTA DURA', 200, 0.00),
(19, 12, 36, 'PLACAS', 29, 0.00),
(20, 13, 37, 'REVISTAS', 2000, 0.00),
(21, 14, 38, 'BICICLETAS', 2, 0.00),
(22, 14, 39, 'BALONES', 10, 0.00),
(23, 14, 39, 'BALONES', 10, 0.00),
(24, 14, 40, 'TENIS', 20, 0.00),
(25, 15, 1, 'bolsas de papel', 50, 0.00),
(26, 15, 14, 'jugo en caja', 50, 0.00),
(27, 16, 41, 'SILLAS', 15, 0.00),
(28, 16, 42, 'NEVERA', 1, 0.00),
(29, 16, 43, 'AIRE ACONDICIONADO', 2, 0.00),
(30, 17, 44, 'PASACALLES', 12, 0.00),
(31, 17, 44, 'PASACALLES', 1, 0.00),
(34, 19, 45, 'ETIQUETAS', 6000, 0.00),
(35, 19, 45, 'ETIQUETAS', 1000, 0.00),
(36, 19, 45, 'ETIQUETAS', 2000, 0.00),
(37, 19, 45, 'ETIQUETAS', 2000, 0.00),
(38, 19, 45, 'ETIQUETAS', 2000, 0.00),
(39, 19, 45, 'ETIQUETAS', 3000, 0.00),
(40, 20, 34, 'MANILLAS TIVECK', 1000, 0.00),
(41, 21, 46, 'FOTOS', 2, 0.00),
(42, 22, 15, ' Paca agua en bolsa', 30, 0.00),
(43, 22, 16, 'Paca agua en botella', 10, 0.00),
(44, 23, 14, 'jugo en caja', 11, 0.00),
(45, 23, 1, 'bolsas de papel', 11, 0.00),
(46, 23, 14, 'jugo en caja', 49, 0.00),
(47, 23, 1, 'bolsas de papel', 49, 0.00),
(48, 24, 14, 'jugo en caja', 700, 0.00),
(49, 24, 47, 'TURRONES DE GRANOLA', 700, 0.00),
(50, 24, 13, 'cajas de refrigerio', 700, 0.00),
(51, 25, 48, 'BANDEJA DE FRUTAS', 1, 0.00),
(52, 25, 49, 'GASEOSA', 15, 0.00),
(53, 25, 50, 'HIELO', 2, 0.00),
(54, 25, 51, 'VASOS DESECHABLES', 2, 0.00),
(55, 25, 8, 'servilletas', 1, 0.00),
(56, 25, 52, 'PAQUETE DE PAPAS', 24, 0.00),
(57, 25, 53, 'GRANOLA', 20, 0.00),
(58, 25, 15, ' Paca agua en bolsa', 2, 0.00),
(59, 25, 54, 'PIZZA FAMILIAR', 2, 0.00),
(60, 26, 55, 'BONOS', 3, 0.00),
(61, 26, 16, 'Paca agua en botella', 19, 0.00),
(62, 27, 56, 'HOJAS FONDEADAS', 7500, 0.00),
(63, 27, 56, 'HOJAS FONDEADAS', 5000, 0.00),
(64, 27, 56, 'HOJAS FONDEADAS', 4000, 0.00),
(65, 27, 56, 'HOJAS FONDEADAS', 20000, 0.00),
(66, 27, 57, 'SOBRES TARJETA', 5000, 0.00),
(67, 28, 58, 'TIQUETES', 400, 0.00),
(68, 28, 58, 'TIQUETES', 400, 0.00),
(69, 29, 59, 'SOBRES', 500, 0.00),
(70, 30, 59, 'SOBRES', 2000, 0.00),
(71, 31, 24, 'STIKERS', 2500, 0.00),
(72, 32, 60, 'TERMOS TERMICOS', 500, 0.00),
(73, 32, 61, 'PINES', 200, 0.00),
(74, 32, 36, 'PLACAS', 3, 0.00),
(75, 33, 62, 'CABINA TIPO MALETA', 1, 0.00),
(76, 33, 63, 'MICROFONO', 1, 0.00),
(77, 34, 35, 'AGENDA PASTA DURA', 650, 0.00),
(78, 34, 64, 'ESCARAPELAS', 650, 0.00),
(79, 34, 65, 'LAPICEROS', 650, 0.00),
(80, 35, 66, 'PONQUE GALA', 50, 0.00),
(81, 35, 14, 'jugo en caja', 50, 0.00),
(82, 35, 12, 'bolsas de refrigerio', 50, 0.00),
(83, 36, 14, 'jugo en caja', 95, 0.00),
(84, 36, 1, 'bolsas de papel', 95, 0.00),
(85, 37, 14, 'jugo en caja', 30, 0.00),
(86, 37, 1, 'bolsas de papel', 90, 0.00),
(87, 38, 67, 'CARPETAS', 1, 0.00),
(88, 39, 68, 'SOMBRILLAS', 11, 0.00),
(89, 40, 24, 'STIKERS', 1000, 0.00),
(90, 41, 64, 'ESCARAPELAS', 100, 0.00),
(91, 42, 16, 'Paca agua en botella', 10, 0.00),
(92, 42, 8, 'servilletas', 2, 0.00),
(93, 42, 64, 'ESCARAPELAS', 40, 0.00),
(94, 42, 69, 'MANTEL BLANCO', 1, 0.00),
(95, 42, 70, 'TAPA ROJA', 1, 0.00),
(96, 42, 71, 'TENEDORES DESECHABLES', 400, 0.00),
(97, 42, 72, 'PLATOS DESECHABLES', 400, 0.00),
(98, 43, 1, 'bolsas de papel', 30, 0.00),
(99, 43, 14, 'jugo en caja', 30, 0.00),
(100, 44, 73, 'INFORMES ARGOLLADOS', 3, 0.00),
(101, 45, 74, 'BANNER', 1, 0.00),
(102, 45, 24, 'STIKERS', 500, 0.00),
(103, 46, 45, 'ETIQUETAS', 400, 0.00),
(104, 47, 75, 'TARJETAS', 230, 0.00),
(105, 48, 76, 'RECONOCIMIENTOS', 2, 0.00),
(106, 49, 77, 'LONA', 1, 0.00),
(107, 50, 25, 'PENDONES', 2, 0.00),
(108, 50, 78, 'LIBRETAS', 50, 0.00),
(109, 50, 64, 'ESCARAPELAS', 50, 0.00),
(110, 51, 24, 'STIKERS', 400, 0.00),
(111, 52, 30, 'VOLANTES', 1000, 0.00),
(112, 53, 45, 'ETIQUETAS', 500, 0.00),
(114, 55, 60, 'TERMOS TERMICOS', 500, 0.00),
(115, 55, 59, 'SOBRES', 840, 0.00),
(116, 55, 24, 'STIKERS', 840, 0.00),
(117, 56, 79, 'CARTA MENU', 50, 0.00),
(118, 57, 80, 'CHEQUES', 4, 0.00),
(119, 58, 81, 'PLEGABLES 2 CUERPOS', 3000, 0.00),
(120, 59, 25, 'PENDONES', 1, 0.00),
(121, 60, 76, 'RECONOCIMIENTOS', 1, 0.00),
(122, 61, 45, 'ETIQUETAS', 500, 0.00),
(123, 62, 25, 'PENDONES', 8, 0.00),
(124, 63, 82, 'CAMISETAS', 8, 0.00),
(125, 63, 82, 'CAMISETAS', 28, 0.00),
(126, 63, 83, 'CAMISAS', 15, 0.00),
(127, 64, 25, 'PENDONES', 2, 0.00),
(128, 65, 84, 'BAKING', 1, 0.00),
(129, 66, 37, 'REVISTAS', 150, 0.00),
(130, 67, 81, 'PLEGABLES 2 CUERPOS', 70, 0.00),
(131, 67, 81, 'PLEGABLES 2 CUERPOS', 400, 0.00),
(132, 67, 82, 'CAMISETAS', 8, 0.00),
(133, 67, 85, 'BOLSAS CAMBREL', 100, 0.00),
(134, 67, 85, 'BOLSAS CAMBREL', 50, 0.00),
(135, 68, 37, 'REVISTAS', 2000, 0.00),
(136, 69, 30, 'VOLANTES', 200, 0.00),
(137, 70, 86, 'CATESISMOS', 5000, 0.00),
(138, 70, 86, 'CATESISMOS', 5000, 0.00),
(139, 71, 80, 'CHEQUES', 1, 0.00),
(140, 72, 87, 'ABANICOS', 250, 0.00),
(141, 72, 35, 'AGENDA PASTA DURA', 300, 0.00),
(142, 73, 88, 'GLOBOS', 200, 0.00),
(143, 74, 80, 'CHEQUES', 3, 0.00),
(144, 74, 85, 'BOLSAS CAMBREL', 200, 0.00),
(145, 75, 25, 'PENDONES', 1, 0.00),
(146, 75, 89, 'ACRILICO', 1, 0.00),
(148, 77, 90, 'REFRIGERIOS', 250, 0.00),
(149, 78, 90, 'REFRIGERIOS', 35, 0.00),
(150, 79, 90, 'REFRIGERIOS', 25, 0.00),
(152, 81, 5, 'azucar', 8, 0.00);

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
(1, 4, 'entrada', 10, 122, 132, 'ajuste_manual', NULL, 1, '', '2025-10-28 15:22:23'),
(2, 4, 'entrada', 10, 132, 142, 'ajuste_manual', NULL, 1, '', '2025-10-28 15:22:23'),
(3, 4, 'entrada', 10, 142, 152, 'ajuste_manual', NULL, 1, 'hgfhgfh', '2025-10-28 15:22:27'),
(4, 4, 'entrada', 10, 152, 162, 'ajuste_manual', NULL, 1, 'hgfhgfh', '2025-10-28 15:22:27'),
(5, 4, 'entrada', 10, 162, 172, 'ajuste_manual', NULL, 1, 'hgfhgfh', '2025-10-28 15:22:40'),
(6, 4, 'entrada', 10, 172, 182, 'ajuste_manual', NULL, 1, 'hgfhgfh', '2025-10-28 15:22:40'),
(7, 4, 'entrada', 1, 182, 183, 'ajuste_manual', NULL, 1, 'jkhjk', '2025-10-28 15:23:43'),
(8, 4, 'entrada', 1, 183, 184, 'ajuste_manual', NULL, 1, 'jkhjk', '2025-10-28 15:23:43'),
(9, 14, 'salida', 350, 500, 150, 'remision', 2, 1, 'Remisión #2', '2025-10-29 15:35:32'),
(10, 14, 'salida', 250, 1000, 750, 'remision', 3, 1, 'Remisión #3', '2025-10-29 15:38:27'),
(11, 14, 'salida', 50, 750, 700, 'remision', 15, 1, 'Remisión #15', '2025-10-30 16:47:46'),
(12, 15, 'salida', 30, 90, 60, 'remision', 22, 1, 'Remisión #22', '2025-10-31 14:46:26'),
(13, 16, 'salida', 10, 39, 29, 'remision', 22, 1, 'Remisión #22', '2025-10-31 14:46:26'),
(14, 14, 'salida', 11, 700, 689, 'remision', 23, 1, 'Remisión #23', '2025-10-31 14:53:58'),
(15, 14, 'salida', 49, 689, 640, 'remision', 23, 1, 'Remisión #23', '2025-10-31 14:53:58'),
(16, 14, 'salida', 700, 10000, 9300, 'remision', 24, 1, 'Remisión #24', '2025-10-31 15:01:42'),
(17, 13, 'salida', 700, 10000, 9300, 'remision', 24, 1, 'Remisión #24', '2025-10-31 15:01:42'),
(18, 8, 'salida', 1, 100, 99, 'remision', 25, 1, 'Remisión #25', '2025-10-31 15:19:48'),
(19, 15, 'salida', 2, 60, 58, 'remision', 25, 1, 'Remisión #25', '2025-10-31 15:19:48'),
(20, 16, 'salida', 19, 29, 10, 'remision', 26, 1, 'Remisión #26', '2025-10-31 15:38:50'),
(21, 14, 'salida', 50, 9300, 9250, 'remision', 35, 1, 'Remisión #35', '2025-11-01 12:14:26'),
(22, 12, 'salida', 50, 500, 450, 'remision', 35, 1, 'Remisión #35', '2025-11-01 12:14:26'),
(23, 14, 'salida', 95, 9250, 9155, 'remision', 36, 1, 'Remisión #36', '2025-11-04 08:47:10'),
(24, 14, 'salida', 30, 9155, 9125, 'remision', 37, 1, 'Remisión #37', '2025-11-04 08:48:27'),
(25, 16, 'salida', 10, 10, 0, 'remision', 42, 1, 'Remisión #42', '2025-11-04 11:10:14'),
(26, 8, 'salida', 2, 99, 97, 'remision', 42, 1, 'Remisión #42', '2025-11-04 11:10:14'),
(27, 14, 'salida', 30, 9125, 9095, 'remision', 43, 1, 'Remisión #43', '2025-11-04 14:10:51'),
(28, 6, 'entrada', 1, 10, 11, 'ajuste_manual', NULL, 1, '', '2025-11-05 09:11:20'),
(29, 6, 'entrada', 1, 11, 12, 'ajuste_manual', NULL, 1, '', '2025-11-05 09:11:20'),
(30, 4, 'entrada', 1, 184, 185, 'ajuste_manual', NULL, 1, 'weewew', '2025-11-18 11:12:13'),
(31, 5, 'salida', 8, 50, 42, 'remision', 81, 1, 'Remisión #80', '2025-11-24 14:33:40');

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
(1, 'ADRIANA ', 'PROFESORA ', '3057898725', NULL, 3),
(2, 'ROCIO VARGAS ', 'dueña', '3106749580', 'rociovargas09@hotmail.com', 4),
(3, 'MARTHA PEÑA ', NULL, '3173817070', NULL, 5),
(4, 'VALENTINA ZABALA', NULL, '3152673225', NULL, 3),
(5, 'jairo alfonso acosta ', 'dueño ', '3153917994', NULL, 6),
(6, 'DANIELA ZAPATA ', NULL, '3164297302', NULL, 8),
(7, 'CAROLINA VERA', 'COMUNICADORA', NULL, NULL, 9),
(8, 'LUIS CARLOS CASTELANOS ', NULL, '3118327925', NULL, 3),
(9, 'MARIA JIMENA SEGOVIA', NULL, '3103295348', NULL, 12),
(10, 'MARIA VICTORIA ', NULL, '3134107689', NULL, 5),
(11, 'VALENTINA ZULUAGA ', NULL, '3123885078', NULL, 5),
(12, 'SOFIA ENCISO ', 'recepcionista', '3022927343', 'encisogarciaelisabetsofia@gmail.com', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_responsables`
--

CREATE TABLE `personas_responsables` (
  `id_responsable` int(11) NOT NULL,
  `nombre_responsable` varchar(100) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas_responsables`
--

INSERT INTO `personas_responsables` (`id_responsable`, `nombre_responsable`, `cargo`, `telefono`, `correo`, `id_cliente`, `fecha_creacion`) VALUES
(1, 'DIEGO RADA', NULL, '3144644540', 'diegowww@gmail.com', 3, '2025-11-24 19:51:42'),
(2, 'lina enciso', NULL, '3144644540', 'diegowww@gmail.com', 3, '2025-11-24 19:51:42');

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
(1, 'bolsas de papel', '2025-09-24 17:10:17', 0, 500, 0),
(3, 'ROMAN SPIRIT – VOL. I', '2025-10-01 13:31:45', 0, 100, 0),
(4, 'cafe', '2025-10-10 21:57:14', 1, 185, 10),
(5, 'azucar', '2025-10-10 21:57:14', 1, 42, 20),
(6, 'aromaticas', '2025-10-10 21:57:14', 1, 12, 15),
(7, 'bolsas de basura', '2025-10-10 21:57:14', 1, 40, 30),
(8, 'servilletas', '2025-10-10 21:57:14', 1, 97, 50),
(9, 'bizcochos', '2025-10-10 21:57:14', 1, 100, 25),
(10, 'galletas', '2025-10-10 21:57:14', 1, 100, 30),
(11, 'melcochas', '2025-10-10 21:57:14', 1, 1000, 40),
(12, 'bolsas de refrigerio', '2025-10-10 21:57:14', 1, 450, 100),
(13, 'cajas de refrigerio', '2025-10-10 21:57:14', 1, 9300, 50),
(14, 'jugo en caja', '2025-10-10 21:57:14', 1, 9095, 40),
(15, ' Paca agua en bolsa', '2025-10-10 21:57:14', 1, 58, 60),
(16, 'Paca agua en botella', '2025-10-10 21:57:14', 1, 0, 80),
(17, 'camisas amarillas', '2025-10-10 21:57:14', 1, 70, 25),
(18, 'camisas negras', '2025-10-10 21:57:14', 1, 42, 25),
(19, 'busos logistica', '2025-10-10 21:57:14', 1, 10, 20),
(20, 'busos aseo', '2025-10-10 21:57:14', 1, 100, 20),
(21, 'camisa manga larga', '2025-10-10 21:57:14', 1, 100, 15),
(22, 'jeans', '2025-10-10 21:57:14', 1, 10, 30),
(23, 'BOTELLONES DE AGUA BRISA', '2025-10-29 16:15:43', 0, 0, 0),
(24, 'STIKERS', '2025-10-29 20:32:56', 0, 0, 0),
(25, 'PENDONES', '2025-10-29 21:32:29', 0, 0, 0),
(26, 'ARAÑAS', '2025-10-29 21:32:35', 0, 0, 0),
(27, 'RECIPIENTE', '2025-10-30 13:31:42', 0, 0, 0),
(28, 'BISUTERIA', '2025-10-30 14:06:36', 0, 0, 0),
(29, 'PLEGABLES 3 CUERPOS ', '2025-10-30 14:25:53', 0, 0, 0),
(30, 'VOLANTES', '2025-10-30 15:47:10', 0, 0, 0),
(31, 'CERTIFICADOS', '2025-10-30 16:32:11', 0, 0, 0),
(32, 'MARCACION PARA ATRIL', '2025-10-30 16:36:50', 0, 0, 0),
(33, 'CENEFAS ', '2025-10-30 16:37:38', 0, 0, 0),
(34, 'MANILLAS TIVECK', '2025-10-30 16:38:51', 0, 0, 0),
(35, 'AGENDA PASTA DURA', '2025-10-30 21:11:08', 0, 0, 0),
(36, 'PLACAS', '2025-10-30 21:11:34', 0, 0, 0),
(37, 'REVISTAS', '2025-10-30 21:15:49', 0, 0, 0),
(38, 'BICICLETAS', '2025-10-30 21:43:09', 0, 0, 0),
(39, 'BALONES', '2025-10-30 21:43:52', 0, 0, 0),
(40, 'TENIS', '2025-10-30 21:44:53', 0, 0, 0),
(41, 'SILLAS', '2025-10-30 22:13:52', 0, 0, 0),
(42, 'NEVERA', '2025-10-30 22:14:16', 0, 0, 0),
(43, 'AIRE ACONDICIONADO', '2025-10-30 22:14:41', 0, 0, 0),
(44, 'PASACALLES', '2025-10-31 15:37:55', 0, 0, 0),
(45, 'ETIQUETAS', '2025-10-31 17:00:26', 0, 0, 0),
(46, 'FOTOS', '2025-10-31 19:21:00', 0, 0, 0),
(47, 'TURRONES DE GRANOLA', '2025-10-31 19:54:56', 0, 0, 0),
(48, 'BANDEJA DE FRUTAS', '2025-10-31 20:10:31', 0, 0, 0),
(49, 'GASEOSA', '2025-10-31 20:12:16', 0, 0, 0),
(50, 'HIELO', '2025-10-31 20:12:43', 0, 0, 0),
(51, 'VASOS DESECHABLES', '2025-10-31 20:13:19', 0, 0, 0),
(52, 'PAQUETE DE PAPAS', '2025-10-31 20:13:57', 0, 0, 0),
(53, 'GRANOLA', '2025-10-31 20:14:18', 0, 0, 0),
(54, 'PIZZA FAMILIAR', '2025-10-31 20:16:00', 0, 0, 0),
(55, 'BONOS', '2025-10-31 20:36:55', 0, 0, 0),
(56, 'HOJAS FONDEADAS', '2025-11-01 13:57:56', 0, 0, 0),
(57, 'SOBRES TARJETA', '2025-11-01 13:59:05', 0, 0, 0),
(58, 'TIQUETES', '2025-11-01 15:01:36', 0, 0, 0),
(59, 'SOBRES', '2025-11-01 15:10:05', 0, 0, 0),
(60, 'TERMOS TERMICOS', '2025-11-01 16:15:07', 0, 0, 0),
(61, 'PINES', '2025-11-01 16:15:25', 0, 0, 0),
(62, 'CABINA TIPO MALETA', '2025-11-01 16:20:57', 0, 0, 0),
(63, 'MICROFONO', '2025-11-01 16:21:06', 0, 0, 0),
(64, 'ESCARAPELAS', '2025-11-01 16:28:19', 0, 0, 0),
(65, 'LAPICEROS', '2025-11-01 16:28:58', 0, 0, 0),
(66, 'PONQUE GALA', '2025-11-01 17:13:31', 0, 0, 0),
(67, 'CARPETAS', '2025-11-04 13:56:35', 0, 0, 0),
(68, 'SOMBRILLAS', '2025-11-04 14:04:54', 0, 0, 0),
(69, 'MANTEL BLANCO', '2025-11-04 16:08:37', 0, 0, 0),
(70, 'TAPA ROJA', '2025-11-04 16:08:50', 0, 0, 0),
(71, 'TENEDORES DESECHABLES', '2025-11-04 16:09:15', 0, 0, 0),
(72, 'PLATOS DESECHABLES', '2025-11-04 16:09:32', 0, 0, 0),
(73, 'INFORMES ARGOLLADOS', '2025-11-04 19:21:56', 0, 0, 0),
(74, 'BANNER', '2025-11-04 20:03:21', 0, 0, 0),
(75, 'TARJETAS', '2025-11-04 22:04:33', 0, 0, 0),
(76, 'RECONOCIMIENTOS', '2025-11-05 13:27:52', 0, 0, 0),
(77, 'LONA', '2025-11-05 14:28:36', 0, 0, 0),
(78, 'LIBRETAS', '2025-11-05 15:25:51', 0, 0, 0),
(79, 'CARTA MENU', '2025-11-05 21:19:32', 0, 0, 0),
(80, 'CHEQUES', '2025-11-05 22:16:27', 0, 0, 0),
(81, 'PLEGABLES 2 CUERPOS', '2025-11-06 13:31:44', 0, 0, 0),
(82, 'CAMISETAS', '2025-11-06 20:23:04', 0, 0, 0),
(83, 'CAMISAS', '2025-11-06 20:25:41', 0, 0, 0),
(84, 'BAKING', '2025-11-06 21:26:48', 0, 0, 0),
(85, 'BOLSAS CAMBREL', '2025-11-07 14:44:09', 0, 0, 0),
(86, 'CATECISMOS', '2025-11-07 15:41:48', 0, 0, 0),
(87, 'ABANICOS', '2025-11-07 15:59:34', 0, 0, 0),
(88, 'GLOBOS', '2025-11-07 21:51:59', 0, 0, 0),
(89, 'ACRILICO', '2025-11-07 22:59:26', 0, 0, 0),
(90, 'REFRIGERIOS', '2025-11-14 14:08:28', 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remisiones`
--

CREATE TABLE `remisiones` (
  `id_remision` int(11) NOT NULL,
  `numero_remision` int(11) NOT NULL,
  `tipo_remision` enum('Alquiler','Venta') NOT NULL DEFAULT 'Venta',
  `fecha_emision` datetime DEFAULT current_timestamp(),
  `id_cliente` int(11) DEFAULT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `id_responsable` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `remisiones`
--

INSERT INTO `remisiones` (`id_remision`, `numero_remision`, `tipo_remision`, `fecha_emision`, `id_cliente`, `id_persona`, `id_responsable`, `id_usuario`, `observaciones`, `id_estado`) VALUES
(1, 1, 'Venta', '2025-10-29 00:00:00', 1, NULL, NULL, 1, 'ENTREGA DE BOTELLONES BRISA ', 1),
(2, 2, 'Venta', '2025-10-29 00:00:00', 2, NULL, NULL, 1, '', 1),
(3, 3, 'Venta', '2025-10-29 00:00:00', 2, NULL, NULL, 1, 'REFRIGERIOS INDEPORTES ', 1),
(4, 4, 'Venta', '2025-10-29 00:00:00', 3, 1, NULL, 1, 'ENTREGAR A ADRIANA \r\nCEL: 3057898725', 1),
(5, 5, 'Venta', '2025-10-30 00:00:00', 4, 2, NULL, 1, 'DEVOLUCION RECIPIENTE ROSADO ', 1),
(6, 6, 'Venta', '2025-10-30 00:00:00', 5, 3, NULL, 1, 'ENTREGA DE IMPLEMENTOS DE BISUTERIA VARIOS (cantidades varias)\r\nENTREGAR EN BANCO DE LA REPUBLICA 9 PISO ', 1),
(7, 7, 'Venta', '2025-10-30 00:00:00', 3, 4, NULL, 1, '', 1),
(8, 8, 'Venta', '2025-10-30 00:00:00', 6, 5, NULL, 1, ' + SOBRANTES', 1),
(9, 9, 'Venta', '2025-10-30 00:00:00', 7, NULL, NULL, 1, '', 1),
(10, 10, 'Venta', '2025-10-30 00:00:00', 8, 6, NULL, 1, 'EN PAPEL EART PACT', 1),
(11, 11, 'Venta', '2025-10-30 00:00:00', 3, 4, NULL, 1, 'MANILLAS TIVECK(GRADUADOS MARCADAS UT)\r\nCENEFAS ( 1M X 20 CM)', 1),
(12, 12, 'Venta', '2025-10-30 00:00:00', 3, 4, NULL, 1, 'AGENDAS PASTA DURA (EDUCACION AMBIENTAL)', 1),
(13, 13, 'Venta', '2025-10-30 00:00:00', 9, 7, NULL, 1, '', 1),
(14, 14, 'Venta', '2025-10-30 00:00:00', 8, 6, NULL, 1, 'BICICLETAS (medianas)\r\nBALONES(futbol)\r\nBALONES (basquet)\r\nTENIS ZAPATOS (tallas varias 28-36)', 1),
(15, 15, 'Venta', '2025-10-30 00:00:00', 2, NULL, NULL, 1, '', 1),
(16, 16, 'Venta', '2025-10-30 00:00:00', 10, NULL, NULL, 1, 'SILLAS (auditorio)\r\nNEVERA (mini bar)\r\nAIRE ACONDICIONADO COMPLETO ', 1),
(17, 17, 'Venta', '2025-10-31 00:00:00', 3, 8, NULL, 1, 'PASACALLE (2.50 M X 70 CM)\r\nPASACALLE (5M X 1M)', 1),
(19, 19, 'Venta', '2025-10-31 00:00:00', 11, NULL, NULL, 1, 'GRIEGO SIN AZUCAR 1000 GR +SOBRANTES\r\nKUMIS 1750 GR \r\nKUMIS HORNEADOS 230GR\r\nKUMIS DESLACTOSADO 1000GR + SOBRANTES \r\nYOGURT NATURAL 1000GR\r\nYOGURT GRIEGO CON DULCE 500GR + SOBRANTES ', 1),
(20, 20, 'Venta', '2025-10-31 00:00:00', 5, NULL, NULL, 1, '', 1),
(21, 21, 'Venta', '2025-10-31 00:00:00', 12, 9, NULL, 1, 'ENTREGAR EN CAMINOS DEL VERGEL CASA I 5 A NOMBRE DE MARIA JIMENA SEGOVIA \r\nFOTOS CAMPESTRE', 1),
(22, 22, 'Venta', '2025-10-31 00:00:00', 3, NULL, NULL, 1, '', 1),
(23, 23, 'Venta', '2025-10-31 00:00:00', 2, NULL, NULL, 1, 'JUGO EN CAJA 11 UNIDADES \r\nBOLSAS DE PAPEL 11 UNIDADES\r\n\r\nJUGO EN CAJA 49 UNIDADES \r\nBOLSAS DE PAPEL 49 UNIDADES', 1),
(24, 24, 'Venta', '2025-10-31 00:00:00', 4, NULL, NULL, 1, '', 1),
(25, 25, 'Venta', '2025-10-31 00:00:00', 3, NULL, NULL, 1, 'BANDEJA DE FRUTAS 1 UNIDAD , GASEOSAS 15 UNIDADES, HIELO 2 PACAS, VASOS DESECHABLES 2 PACAS, SERVILLETAS 1 PQUETE, PAQUETE DE PAPAS 24 UNIDADES, GRANOLA 20 UNIDADES, PACA DE AGUA 2 Y PIZZAS FAMILIARES 2\r\n\r\n\r\n', 1),
(26, 26, 'Venta', '2025-10-31 00:00:00', 8, NULL, NULL, 1, 'BONOS DE 200.000 PESOS CADA UNO ', 1),
(27, 27, 'Venta', '2025-11-01 00:00:00', 13, NULL, NULL, 1, 'HOJAS FONDEADAS AMARILLAS \r\nHOJAS FONDEADAS VERDES\r\nHOJAS FONDEADAS AMARILLA FLUORECENTE\r\nHOJAS FONDEADAS ROSADAS\r\nSOBRES TARJETAS REGALO', 1),
(28, 28, 'Venta', '2025-11-01 00:00:00', 14, NULL, NULL, 1, 'TIQUETES CHOLAO \r\nTIQUETES MARACUMANGO ', 1),
(29, 29, 'Venta', '2025-11-01 00:00:00', 15, NULL, NULL, 1, '', 1),
(30, 30, 'Venta', '2025-11-01 00:00:00', 15, NULL, NULL, 1, '', 1),
(31, 31, 'Venta', '2025-11-01 00:00:00', 4, NULL, NULL, 1, 'STIKERS FACULTAD DE LA INGENERIA FORESTAL UT + SOBRANTES ', 1),
(32, 32, 'Venta', '2025-11-01 00:00:00', 3, 4, NULL, 1, 'TERMOS TERMICOS ( MARCADOS CON LOGOS )\r\nPINES(ORGULLOSAMENTE GRADUADOS ORO)\r\nPLACAS (ACRILICO)', 1),
(33, 33, 'Venta', '2025-11-01 00:00:00', 3, 4, NULL, 1, 'CABINA TIPO MALETA CON MICROFONO ', 1),
(34, 34, 'Venta', '2025-11-01 00:00:00', 3, NULL, NULL, 1, 'FALCULTAD DE INGENERIA FORESTAL \r\n', 1),
(35, 35, 'Venta', '2025-11-01 00:00:00', 8, NULL, NULL, 1, '50 REGRIGERIOS - JUGO Y PONGUE ', 1),
(36, 36, 'Venta', '2025-11-04 00:00:00', 2, NULL, NULL, 1, '', 1),
(37, 37, 'Venta', '2025-11-04 00:00:00', 2, NULL, NULL, 1, '', 1),
(38, 38, 'Venta', '2025-11-04 00:00:00', 16, NULL, NULL, 1, 'CARPETA RECONOCIMIENTO GOBERNADORA ', 1),
(39, 39, 'Venta', '2025-11-04 00:00:00', 17, NULL, NULL, 1, '', 1),
(40, 40, 'Venta', '2025-09-09 00:00:00', 8, NULL, NULL, 1, '', 1),
(41, 41, 'Venta', '2025-11-04 00:00:00', 3, NULL, NULL, 1, '', 1),
(42, 42, 'Venta', '2025-11-04 00:00:00', 3, NULL, NULL, 1, '40 ESCARAPELAS CON CORDON ', 1),
(43, 43, 'Venta', '2025-11-04 00:00:00', 2, NULL, NULL, 1, '', 1),
(44, 44, 'Venta', '2025-11-04 00:00:00', 5, NULL, NULL, 1, 'SECRETARIA DE PLANEACION \r\n3 INFORMES ARGOLLADOS (231 PAGINAS C/U)', 1),
(45, 45, 'Venta', '2025-11-04 00:00:00', 5, NULL, NULL, 1, 'BANNER 1.30 X 80 CM SIN ESTRUCTURA \r\nSTIKERS GOBERNACION (ACCIONES QUE  INCLUYEN)', 1),
(46, 46, 'Venta', '2025-11-04 00:00:00', 11, NULL, NULL, 1, 'ETIQUETAS EL CURAL 1000 GR GRIEGO SIN AZUCAR ', 1),
(47, 47, 'Venta', '2025-11-04 00:00:00', 18, NULL, NULL, 1, '', 1),
(48, 48, 'Venta', '2025-11-05 00:00:00', 5, NULL, NULL, 1, '', 1),
(49, 49, 'Venta', '2025-11-05 00:00:00', 19, NULL, NULL, 1, '', 1),
(50, 50, 'Venta', '2025-11-05 00:00:00', 5, NULL, NULL, 1, 'DIRECCION DE CULTURA \r\n2 PENDONES (CON OJALETES)\r\n50 LIBRETAS (PROMOCIONALES) \r\n50 ESCARAPELAS (CON FUNDA Y CORDON )\r\n', 1),
(51, 51, 'Venta', '2025-11-05 00:00:00', 5, NULL, NULL, 1, 'DE PARTE DE SECRETARIA DE DESARROLLO ECONOMICO \r\nSTIKERS (BENEFICIOS TENDEROS)', 1),
(52, 52, 'Venta', '2025-11-05 00:00:00', 20, NULL, NULL, 1, 'VOLANTES POSGRADOS ', 1),
(53, 53, 'Venta', '2025-11-05 00:00:00', 21, NULL, NULL, 1, 'ETIQUETAS EXTRACRYL 1/4 GALON + SOBRANTES ', 1),
(55, 55, 'Venta', '2025-11-05 00:00:00', 3, 4, NULL, 1, 'SECRETARIA GENERAL \r\n500 TERMOS TERMICOS (2 REF)\r\n840 SOBRES (ROJOS TROQUELADOS)\r\n840 STIKERS (PLATEADOS)', 1),
(56, 56, 'Venta', '2025-11-05 00:00:00', 22, NULL, NULL, 1, 'CARTA MENU( EL PUERTO)', 1),
(57, 57, 'Venta', '2025-11-05 00:00:00', 14, NULL, NULL, 1, '', 1),
(58, 58, 'Venta', '2025-11-06 00:00:00', 20, NULL, NULL, 1, '', 1),
(59, 59, 'Venta', '2025-11-06 00:00:00', 14, NULL, NULL, 1, '1 PENDON 1 X 2 ', 1),
(60, 60, 'Venta', '2025-11-06 00:00:00', 5, 10, NULL, 1, 'PLACA RECONOCIMIENTO \r\n', 1),
(61, 61, 'Venta', '2025-11-06 00:00:00', 21, NULL, NULL, 1, 'ETIQUETAS EXTRACRYL GALON ', 1),
(62, 62, 'Venta', '2025-11-06 00:00:00', 23, NULL, NULL, 1, '8 PENDONES CON ARAÑA', 1),
(63, 63, 'Venta', '2025-11-06 00:00:00', 3, 4, NULL, 1, '8 CAMISETAS (THISER COLOR NARANJA ESTAMPADA CON LOGOS )\r\n28 CAMISETAS(POOLO COLOR BLANCO ESTAMPADAS CON LOGO)\r\n15 CAMISAS (NEGRAS BORDADAS CON LOGO \"VIOLIN\")', 1),
(64, 64, 'Venta', '2025-11-06 00:00:00', 24, NULL, NULL, 1, 'REGALO A IBANASCA', 1),
(65, 65, 'Venta', '2025-11-06 00:00:00', 25, NULL, NULL, 1, '', 1),
(66, 66, 'Venta', '2025-11-06 00:00:00', 26, NULL, NULL, 1, '150 REVISTAS RUTA COLOMBIA', 1),
(67, 67, 'Venta', '2025-11-07 00:00:00', 3, 4, NULL, 1, 'SECRETARIA GENERAL\r\n70 PLEGABLES CONCURSO VIOLIN (RECITAL DE INAGURACION)\r\n400 PLEGABLES CONSURSO VIOLIN (CREMONIA DE CLASULA)\r\n8 CAMISETAS FESTIVAL VIOLINES \r\n100 BOLSAS CAMBREL (NEGRAS DE PRESENTACION MARCADAS CON LOGOS DORADOS UT )\r\n50 BOLSAS CAMBREL (BLANCAS CON LOGO INSTITUCIONALES )', 1),
(68, 68, 'Venta', '2025-11-07 00:00:00', 26, NULL, NULL, 1, '2000 REVISTAS RUTA COLOMBIA ', 1),
(69, 69, 'Venta', '2025-11-07 00:00:00', 8, 6, NULL, 1, '', 1),
(70, 70, 'Venta', '2025-11-07 00:00:00', 27, NULL, NULL, 1, '5000 CATECISMOS COMUNION NIÑO \r\n5000 CATECISMOS CONFIRMACION ', 1),
(71, 71, 'Venta', '2025-11-07 00:00:00', 5, 10, NULL, 1, '1 CHEQUE 1.20 M X 50 CM', 1),
(72, 72, 'Venta', '2025-11-07 00:00:00', 5, 11, NULL, 1, '', 1),
(73, 73, 'Venta', '2025-11-07 00:00:00', 13, NULL, NULL, 1, '', 1),
(74, 74, 'Venta', '2025-11-07 00:00:00', 3, 4, NULL, 1, '200 BOLSAS CAMBREL (BLANCAS MARCADAS CON LOGO INSTITUCIONAL)\r\n3 CHEQUES (1.50 M x 73 CM)\r\n', 1),
(75, 75, 'Venta', '2025-11-07 00:00:00', 28, NULL, NULL, 1, '1 PENDON CON ARAÑA\r\n1 SEÑALIZACION ACRILICO ', 1),
(77, 77, 'Venta', '2025-10-30 00:00:00', 8, 6, NULL, 1, '', 1),
(78, 78, 'Venta', '2025-11-04 00:00:00', 8, 6, NULL, 1, '', 1),
(79, 79, 'Venta', '2025-11-10 00:00:00', 8, 6, NULL, 1, '', 1),
(81, 80, 'Venta', '2025-11-24 00:00:00', 3, 4, NULL, 1, 'aasass', 1);

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
-- Indices de la tabla `detalle_remision`
--
ALTER TABLE `detalle_remision`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_detalle_remision` (`id_remision`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id_devolucion`),
  ADD KEY `fk_devoluciones_remision` (`id_remision`),
  ADD KEY `fk_devoluciones_usuario` (`id_usuario`);

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
-- Indices de la tabla `personas_responsables`
--
ALTER TABLE `personas_responsables`
  ADD PRIMARY KEY (`id_responsable`),
  ADD KEY `fk_responsables_cliente` (`id_cliente`);

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
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `idx_remisiones_responsable` (`id_responsable`);

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
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `detalle_remision`
--
ALTER TABLE `detalle_remision`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id_devolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `items_remisionados`
--
ALTER TABLE `items_remisionados`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `personas_contacto`
--
ALTER TABLE `personas_contacto`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `personas_responsables`
--
ALTER TABLE `personas_responsables`
  MODIFY `id_responsable` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `remisiones`
--
ALTER TABLE `remisiones`
  MODIFY `id_remision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_remision`
--
ALTER TABLE `detalle_remision`
  ADD CONSTRAINT `fk_detalle_remision` FOREIGN KEY (`id_remision`) REFERENCES `remisiones` (`id_remision`) ON DELETE CASCADE;

--
-- Filtros para la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD CONSTRAINT `fk_devoluciones_remision` FOREIGN KEY (`id_remision`) REFERENCES `remisiones` (`id_remision`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devoluciones_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_remisiones_responsable` FOREIGN KEY (`id_responsable`) REFERENCES `personas_responsables` (`id_responsable`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `remisiones_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `remisiones_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `remisiones_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
