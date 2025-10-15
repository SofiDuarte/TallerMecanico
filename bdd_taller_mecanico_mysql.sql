-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-10-2025 a las 18:19:54
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
-- Base de datos: `bdd_taller_mecanico_mysql`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `cliente_DNI` varchar(10) NOT NULL,
  `cliente_contrasena` varchar(255) NOT NULL,
  `cliente_nombre` varchar(50) NOT NULL,
  `cliente_direccion` varchar(50) DEFAULT NULL,
  `cliente_localidad` varchar(15) DEFAULT NULL,
  `cliente_telefono` varchar(15) DEFAULT NULL,
  `cliente_email` varchar(255) NOT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cliente_DNI`, `cliente_contrasena`, `cliente_nombre`, `cliente_direccion`, `cliente_localidad`, `cliente_telefono`, `cliente_email`, `token_recuperacion`) VALUES
('11179113', '$2y$10$XZ4MO8mXG4/GO6YTtfbvVO0lwE/BqX2DkIuC97ESE2f6N/b3O1K/S', 'Ana Maria Gomes Rosa', 'Portela 1136', 'CABA', '1166890208', 'anamaria396424@gmail.com', NULL),
('18762965', '$2y$10$tYDuZiiScaJgGqJE8SLtguuhgyIGYD8kcrx.gUkeSL/rA98G6TXaO', 'Laura Martínez', NULL, NULL, '3515555678', 'christian.caprarulo@gmail.com', NULL),
('19786413', '$2y$10$xzLTMTIzZHSbk0Fsq0/HUeaoHAGzfWLJ5duC5X96k3I9IyFZ2Ui6i', 'María García', 'Ascasubi 1342', 'Buenos Aires', '1167439855', '', NULL),
('22870111', '$2y$10$Uz1AKpqPJ/07.s7IHOokq.1klFUIMmj1YpN4/Sxz3ZkAmq92oGoVi', 'Juan Villalba', 'Monroe 87', 'Lanus', '1137081077', '', NULL),
('27552991', '$2y$10$TPPOV5Q7xnNTsR3PwMamTe68VNo7eBQkfvRV.1zESaCYNO5BGguZ2', 'Alejandro López', NULL, NULL, NULL, '', NULL),
('28090318', '$2y$10$4yPA4IJbyFidZn5fybqYgeI6L1/ByLyna1A.Kgljndsw/fqhz8u6e', 'Miguel Martinez', 'Pedro Lozano 452', 'Caba', '1145269854', '', NULL),
('30164750', '$2y$10$HObPHxeBfMcrhRmwpd3A5On8.vWBy56uZnRonzI4MCZMrRkdbWNR6', 'Maria Sotelo', 'Tandil 6940', 'Caba', '1122083320', '', NULL),
('30700247', '$2y$10$ho/z/4IPCiRt7yQlGPZnU.IB9YgPFve6PqSvcO4Jspm/wUYDv1/JS', 'Christian Caprarulo', 'Portela 1136', 'CABA', '01157172522', 'christian.caprarulo@gmail.com', 'b6c493c0d6996a23f1b1c6243ed1ea02c908eec989fbf62200a4efefe94086af'),
('32489632', '$2y$10$isL6a3BO9M9Afhqpn84LGuZH0DfLR0jFYsVHeDa2sB4I1KPhigem.', 'Juan Pérez', 'San Martin 514', 'Caba', '1167349281', '', NULL),
('32690367', '$2y$10$WvQLICi2WXZ/kOVx5yhtke3SSOwafNFq1KdP/mUdx0u8lg0Hzh9GG', 'Adrian Favio Caprarulo', 'Jose Ingenieros', 'San Justo', '1157316427', 'adrian.caprarulo@gmail.com', NULL),
('41298533', '$2y$10$BkSZA7Oo/WbXyBgbSgU5LOgXvoB1LLbv6qp./sPf6vr1PN5xeFVnq', 'Carlos Rodríguez', NULL, 'Mendoza', NULL, '', NULL),
('43796532', '$2y$10$ZT.y6r.9A6mqqNOvX4w1/.JCD9VQQrfc.qnDp2RItDSelpkxqmxU6', 'Sergio Benitez', 'Av.Saenz 708', 'Caba', '1147552201', '', NULL),
('44671150', '$2y$10$puw7dci8b2nvSEyCSSTi2udf7WJLdcWB.Fv/advhZVFlmFuNFUN5y', 'Sofia Duarte Villan', 'Homero 919', 'CABA', '1135932021', 'sofiduvi@gmail.com', NULL),
('47651867', '$2y$10$ujHC4B1Rac0BNRT3vM5lteCFosundlBVPW/734vNucl3ebDAwTO92', 'Martin Damian Caprarulo', 'Portela 1136', 'CABA', '1157379981', 'martin.d.caprarulo@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `empleado_DNI` varchar(10) NOT NULL,
  `empleado_contrasena` varchar(255) NOT NULL,
  `empleado_nombre` varchar(50) NOT NULL,
  `empleado_roll` varchar(255) NOT NULL,
  `empleado_email` text NOT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `empleado_direccion` varchar(50) DEFAULT NULL,
  `empleado_localidad` varchar(15) DEFAULT NULL,
  `empleado_telefono` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`empleado_DNI`, `empleado_contrasena`, `empleado_nombre`, `empleado_roll`, `empleado_email`, `token_recuperacion`, `empleado_direccion`, `empleado_localidad`, `empleado_telefono`) VALUES
('08326014', '$2y$10$TZQtjVmYfrIcqxqU/EkeSe7nPgEkIz25dKNmRRs4idnF86HLx162.', 'Norberto Caprarulo', 'mecanico', 'norberto.caprarulo@gmail.com', NULL, 'J. Ingenieros 3964', 'San Justo', '1160102685'),
('24874723', '$2y$10$D4u9GL/1rhsdVbWBLeyv2urFR9/OdEL89KcO028uFKFxJ3bpuxOt6', 'Stella Brzostowski', 'recepcionista', '0', NULL, 'Pedro M. Obligado 1489', 'Laferrere', NULL),
('30700247', '$2y$10$TZQtjVmYfrIcqxqU/EkeSe7nPgEkIz25dKNmRRs4idnF86HLx162.', 'Christian Caprarulo', 'mecanico', 'christian.caprarulo@gmail.com', 'c8b9d38cdfcadc04e2b96decdc25b0b25ce5430f5ca4a6ed3fb9ca75965ab649', 'Portela 1136', 'CABA', '1157172522'),
('32690365', '$2y$10$TZQtjVmYfrIcqxqU/EkeSe7nPgEkIz25dKNmRRs4idnF86HLx162.$2y$10$TZQtjVmYfrIcqxqU/EkeSe7nPgEkIz25dKNmRRs4idnF86HLx162.', 'Adrián Caprarulo', 'mecanico', '0', NULL, 'J. Ingenieros 3964', 'San Justo', '1160152685'),
('44671150', '$2y$10$8I/M3RbRn.FUuovkMkvJO.WGcLxWXaq689Xg5xQ.AU73w7INxmSEK', 'Sofia Duarte', 'recepcionista', 'sofiduvi@gmail.com', '85d6877e5029ba391ce5d2a72d48151ab00a14a36f9d1830f0abd8672f8424eb', 'Homero 919', 'CABA', ''),
('47651867', '$2y$10$nyqZ/3LasLxH/FfKUhp5MeBSACkABS.mF12eZ5zJoSVhzKqrRWm9u', 'Martin Caprarulo', 'mecanico', 'martin.d.caprarulo@gmail.com', NULL, 'Portela 1136', 'CABA', '1157379981');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `factura_id` int(11) NOT NULL,
  `tipo` enum('A','B','C') NOT NULL,
  `nro_comprobante` int(11) NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `orden_numero` int(11) NOT NULL,
  `servicio_codigo` varchar(5) NOT NULL,
  `cliente_dni` varchar(10) NOT NULL,
  `vehiculo_patente` varchar(10) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `pdf_nombre` varchar(255) DEFAULT NULL,
  `email_destino` varchar(255) DEFAULT NULL,
  `email_enviado` tinyint(1) NOT NULL DEFAULT 0,
  `empleado_emisor` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`factura_id`, `tipo`, `nro_comprobante`, `fecha_emision`, `orden_numero`, `servicio_codigo`, `cliente_dni`, `vehiculo_patente`, `total`, `pdf_nombre`, `email_destino`, `email_enviado`, `empleado_emisor`) VALUES
(1, 'C', 1, '2025-10-09 20:44:58', 13, 'D001', '30700247', 'GCR891', 5500.00, 'Factura_C_00000001_Orden_13.pdf', NULL, 0, '44671150'),
(2, 'C', 2, '2025-10-09 20:48:43', 11, 'D001', '22870111', 'POD166', 5500.00, 'Factura_C_00000002_Orden_11.pdf', NULL, 0, '44671150'),
(4, 'C', 3, '2025-10-09 20:53:55', 9, 'SEL00', '32489632', 'GHI410', 11760.00, 'Factura_C_00000003_Orden_9.pdf', NULL, 0, '44671150'),
(5, 'C', 4, '2025-10-12 17:56:15', 7, 'FR002', '41298533', 'FOM132', 16380.00, 'Factura_C_00000004_Orden_7.pdf', NULL, 0, '44671150'),
(6, 'A', 1, '2025-10-13 16:21:10', 2, 'FR003', '30164750', 'NJK038', 12540.00, 'Factura_A_00000001_Orden_2.pdf', NULL, 0, '44671150'),
(7, 'B', 1, '2025-10-13 16:21:29', 2, 'CLA00', '30164750', 'NJK038', 7920.00, 'Factura_B_00000001_Orden_2.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(10, 'C', 5, '2025-10-13 16:25:15', 4, 'EB001', '22870111', 'POD166', 71760.00, 'Factura_C_00000005_Orden_4.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(11, 'C', 6, '2025-10-13 16:28:02', 5, 'EB001', '30164750', 'AA459FT', 65780.00, 'Factura_C_00000006_Orden_5.pdf', '', 0, '44671150'),
(14, 'C', 7, '2025-10-13 16:28:24', 6, 'ST002', '43796532', 'EOZ386', 45840.00, 'Factura_C_00000007_Orden_6.pdf', '', 0, '44671150'),
(15, 'C', 8, '2025-10-13 16:29:10', 6, 'SD002', '43796532', 'EOZ386', 30140.00, 'Factura_C_00000008_Orden_6.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(16, 'C', 9, '2025-10-13 16:32:25', 18, 'SA001', '30700247', 'GCR891', 20570.00, 'Factura_C_00000009_Orden_18.pdf', 'christian.caprarulo@gmail.com', 0, '44671150'),
(17, 'C', 10, '2025-10-13 16:33:29', 19, 'RR001', '30700247', 'GCR891', 8580.00, 'Factura_C_00000010_Orden_19.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(18, 'C', 11, '2025-10-13 16:36:43', 16, 'SD001', '30700247', 'UWL004', 20020.00, 'Factura_C_00000011_Orden_16.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(20, 'B', 2, '2025-10-13 16:49:24', 14, 'FR002', '44671150', 'A221GAR', 13860.00, 'Factura_B_00000002_Orden_14.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(21, 'C', 12, '2025-10-13 16:49:56', 12, 'S002', '18762965', 'AB307CI', 24200.00, 'Factura_C_00000012_Orden_12.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(22, 'C', 13, '2025-10-13 16:51:30', 10, 'SDI00', '30164750', 'AA459FT', 14630.00, 'Factura_C_00000013_Orden_10.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(23, 'C', 14, '2025-10-13 16:52:05', 5, 'CV001', '30164750', 'AA459FT', 79860.00, 'Factura_C_00000014_Orden_5.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(24, 'C', 15, '2025-10-13 21:04:06', 21, 'MT001', '30700247', 'GCR891', 108240.00, 'Factura_C_00000015_Orden_21.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(25, 'C', 16, '2025-10-13 21:05:59', 7, 'FR001', '41298533', 'FOM132', 15600.00, 'Factura_C_00000016_Orden_7.pdf', 'sofiduvi@gmail.com', 0, '44671150'),
(26, 'C', 17, '2025-10-13 23:44:54', 4, 'CV001', '22870111', 'POD166', 87120.00, 'Factura_C_00000017_Orden_4.pdf', 'sofiduvi@gmail.com', 0, '44671150');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_numeradores`
--

CREATE TABLE `factura_numeradores` (
  `tipo` enum('A','B','C') NOT NULL,
  `proximo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura_numeradores`
--

INSERT INTO `factura_numeradores` (`tipo`, `proximo`) VALUES
('A', 2),
('B', 3),
('C', 18);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `historico`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `historico` (
`orden_fecha` varchar(255)
,`vehiculo_patente` varchar(10)
,`orden_numero` int(11)
,`orden_kilometros` int(10) unsigned
,`servicio_descripcion` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `orden_numero` int(11) NOT NULL,
  `orden_fecha` varchar(255) NOT NULL,
  `vehiculo_patente` varchar(10) NOT NULL,
  `orden_costo` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`orden_numero`, `orden_fecha`, `vehiculo_patente`, `orden_costo`) VALUES
(0, '', 'GCR891', NULL),
(1, '2019-07-12', 'AB307CI', NULL),
(2, '2019-08-15', 'NJK038', NULL),
(3, '2019-08-19', 'JKM733', NULL),
(4, '2019-09-01', 'POD166', NULL),
(5, '2019-09-15', 'AA459FT', NULL),
(6, '2019-09-22', 'EOZ386', NULL),
(7, '2019-09-28', 'FOM132', NULL),
(8, '2019-10-11', 'CDE091', NULL),
(9, '2019-12-19', 'GHI410', NULL),
(10, '2020-03-25', 'AA459FT', NULL),
(11, '2020-06-04', 'POD166', NULL),
(12, '2020-08-06', 'AB307CI', NULL),
(13, '2025-05-25', 'GCR891', NULL),
(14, '2025-05-29', 'A221GAR', NULL),
(16, '2025-06-15', 'UWL004', NULL),
(17, '2025-10-13', 'GCR891', NULL),
(18, '2025-10-14', 'GCR891', NULL),
(19, '2025-10-13', 'GCR891', NULL),
(20, '2025-10-13', 'GCR891', NULL),
(21, '2025-10-13', 'GCR891', NULL),
(22, '2025-10-15', 'GCR891', NULL),
(23, '2025-10-14', 'A221GAR', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_trabajo`
--

CREATE TABLE `orden_trabajo` (
  `orden_numero` int(11) NOT NULL,
  `servicio_codigo` varchar(5) NOT NULL,
  `complejidad` int(11) NOT NULL,
  `costo_ajustado` decimal(8,2) DEFAULT NULL,
  `orden_kilometros` int(10) UNSIGNED NOT NULL,
  `orden_comentario` varchar(255) NOT NULL,
  `orden_estado` tinyint(1) NOT NULL,
  `mecanico_DNI` varchar(15) DEFAULT NULL,
  `turno_id` int(11) DEFAULT NULL,
  `factura_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_trabajo`
--

INSERT INTO `orden_trabajo` (`orden_numero`, `servicio_codigo`, `complejidad`, `costo_ajustado`, `orden_kilometros`, `orden_comentario`, `orden_estado`, `mecanico_DNI`, `turno_id`, `factura_id`) VALUES
(2, 'CLA00', 1, 7920.00, 120324, '', 1, '30700247', NULL, 7),
(2, 'FR003', 1, 12540.00, 120324, '', 1, '47651867', NULL, 6),
(2, 'SA001', 3, 24310.00, 120324, '', 0, '32690365', NULL, NULL),
(3, 'MT002', 2, 146880.00, 250341, '', 0, '47651867', NULL, NULL),
(4, 'CV001', 2, 87120.00, 60724, 'se armo correctamente', 1, '30700247', NULL, 26),
(4, 'EB001', 2, 71760.00, 60724, 'se armo correctamente', 1, '32690365', NULL, 10),
(5, 'CV001', 1, 79860.00, 71543, '', 1, '30700247', NULL, 23),
(5, 'EB001', 1, 65780.00, 71543, '', 1, '47651867', NULL, 11),
(6, 'SD002', 1, 30140.00, 47980, '', 1, '32690365', NULL, 15),
(6, 'ST002', 2, 45840.00, 47980, '', 1, '30700247', NULL, 14),
(7, 'FR001', 3, 15600.00, 56782, '', 1, '47651867', NULL, 25),
(7, 'FR002', 3, 16380.00, 56782, '', 1, '32690365', NULL, 5),
(8, 'SES00', 2, 47400.00, 25619, '', 0, '08326014', NULL, NULL),
(9, 'SEL00', 2, 11760.00, 94723, '', 1, '08326014', NULL, 4),
(10, 'SDI00', 1, 14630.00, 119832, '', 1, '30700247', NULL, 22),
(11, 'D001', 1, 5500.00, 43909, '', 1, '08326014', NULL, 2),
(12, 'S002', 1, 24200.00, 67413, '', 1, '30700247', NULL, 21),
(13, 'D001', 1, 5500.00, 1000, 'NINGUNO', 1, '08326014', NULL, 1),
(14, 'FR002', 1, 13860.00, 1338, 'La clienta dice que no frena un carajo.', 1, '30700247', NULL, 20),
(16, 'SD001', 1, 20020.00, 85021, 'Hace un ruidito', 1, '30700247', NULL, 18),
(17, 'OT001', 1, 61160.00, 1, '1234', 0, '08326014', 47, NULL),
(18, 'SA001', 1, 20570.00, 61999, '123456789', 1, '30700247', 48, 16),
(19, 'RR001', 1, 8580.00, 62000, '123456789', 1, '30700247', 49, 17),
(20, 'S001', 1, 9350.00, 62001, '123654125874126541', 0, '32690365', 50, NULL),
(21, 'MT001', 1, 108240.00, 62005, '12368741236584', 0, '47651867', 51, 24),
(22, 'FR001', 1, 13200.00, 62009, '1236987412', 0, '32690365', 52, NULL),
(23, 'S001', 1, 9350.00, 67413, 'testeo', 0, '08326014', 54, NULL);

--
-- Disparadores `orden_trabajo`
--
DELIMITER $$
CREATE TRIGGER `before_insert_orden_trabajo` BEFORE INSERT ON `orden_trabajo` FOR EACH ROW BEGIN
  DECLARE base_costo DECIMAL(10,2) DEFAULT 0;
  DECLARE mult       DECIMAL(4,2)  DEFAULT 1;

  SELECT COALESCE(servicio_costo,0) INTO base_costo
  FROM servicios
  WHERE servicio_codigo = NEW.servicio_codigo;

  SET mult = CASE NEW.complejidad
               WHEN 1 THEN 1.10
               WHEN 2 THEN 1.20
               WHEN 3 THEN 1.30
               ELSE 1
             END;

  SET NEW.costo_ajustado = ROUND(base_costo * mult, 2);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_orden_trabajo` BEFORE UPDATE ON `orden_trabajo` FOR EACH ROW BEGIN
  DECLARE base_costo DECIMAL(10,2) DEFAULT 0;
  DECLARE mult       DECIMAL(4,2)  DEFAULT 1;

  SELECT COALESCE(servicio_costo,0) INTO base_costo
  FROM servicios
  WHERE servicio_codigo = NEW.servicio_codigo;

  SET mult = CASE NEW.complejidad
               WHEN 1 THEN 1.10
               WHEN 2 THEN 1.20
               WHEN 3 THEN 1.30
               ELSE 1
             END;

  SET NEW.costo_ajustado = ROUND(base_costo * mult, 2);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `servicio_codigo` varchar(5) NOT NULL,
  `servicio_nombre` varchar(35) NOT NULL,
  `servicio_descripcion` varchar(100) NOT NULL,
  `servicio_costo` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`servicio_codigo`, `servicio_nombre`, `servicio_descripcion`, `servicio_costo`) VALUES
('CLA00', 'CAMBIO DE LAMPARAS', 'REVICION Y CAMBIO DE LAMPARAS', 7200.00),
('CV001', 'CAJA DE VELOCIDADES', 'REPARACION DE COMPETA DE CAJA DE VELOCIDADES CAMBIO DE RETENES', 72600.00),
('D001', 'DIAGNOSTICO COMPUTARIZADO', 'TOMA DE DIAGNOSTICO', 5000.00),
('EB001', 'EMBRAGUE', 'CAMBIO O REPARACION DE EMBRAGUE, BOMBA, BOMBIN, RULEMAN DE EMPUJE', 59800.00),
('FR001', 'FRENOS DELANTEROS', 'REPARACION O CAMBIO DE DISCO, CAMBIO DE PASTILLAS.', 12000.00),
('FR002', 'FRENOS TRASERO', 'REPARACION O CAMBIO DE ZAPATAS, REPARACION O CAMBIO DE CAMPANAS.', 12600.00),
('FR003', 'SISTEMA DE FRENOS', 'BOMBA, ABS, CALIBRQACION DE FRENOS', 11400.00),
('LIM00', 'LIMPIEZA DE INYECTORES', 'LIMPIEZA Y PUESTA A PUNTO DE INYECTORES', 19700.00),
('MT001', '½ MOTOR', 'CAMBIO DE AROS, METALES Y RETENES', 98400.00),
('MT002', 'MOTOR COMPLETO', 'DESARME Y REPARACION COMPLETA DE MOTOR', 122400.00),
('OT001', 'OTROS', 'TRABAJOS NO CONTEMPLADOS.', 55600.00),
('RE001', 'REVISION ECU', 'REVISION, AJUSTE PROGRAMACION DE ECU', 19600.00),
('RR001', 'RULEMANES DE RUEDA DELANTEROS', 'CAMBIO DE RULEMANES DE RUEDA', 7800.00),
('RR002', 'RULEMANES DE RUEDA TRASEROS', 'CAMBIO DE RULEMANES DE RUEDA', 8100.00),
('S001', 'SERVICE', 'CAMBIO DE FILTROS –ACEITE, AIREMOTOR, AIRE HABITACULO, COMBUSTIBLE- CAMBIO DE FLUIDOS', 8500.00),
('S002', 'SERVICE DISTRIBUCION', 'CORREA O CADENA DE DISTRIBUCION, TENSORES, CORREA DE ACCESORIOS', 22000.00),
('SA001', 'SISTEMA DE ADMSION', 'LIMPIEZA, REGULACION Y PUESTA A PUNTO DE CARBURADOR O CUERPO MARIPOSA', 18700.00),
('SD001', 'SUSPENSIÓN DELANTERA BÁSICA', 'CAMBIO DE ROTULAS, BIELETA,BUJES DE PARRILLA, BUJES BARRA ESTABILIZADORA', 18200.00),
('SD002', 'SUSPENSIÓN DELANTERA COMPLETA', 'SUPENCION DELANTERA BASICA + AMORTIGUADORES, CASOLETAS, ESPIRALES, PARRILLAS', 27400.00),
('SDI00', 'SISTEMA DE DIRECCION', 'CREMALLERA, EXTREMOS, PRECAP, COLUMNA DE DIRECCION', 13300.00),
('SE001', 'SISTEMA DE ENCENDIDO', 'BOBINA, CABLES, BUJIAS, PRECALENTADORES, DISTRIBUIDOR', 10400.00),
('SEL00', 'SISTEMA ELECTRICO', 'BATERIA, ALTERNADOR, ARRANQUE', 9800.00),
('SES00', 'SISTEMA DE ESCAPE ', 'REPARACION DE MULTIPLE DE ESCAPE, CAÑO DE ESCAPE, SILENCIADOR, CAMBIO DE JUNTAS.', 39500.00),
('SRF00', 'SISTEMA DE REFRIGERACION', 'CAMBIO DE MANGUERAS, RADIADOR, TERMOSTATO, BULBO DE TEMPERATURA,  BIDON DE REFRIGERANTE, BOMBE DE AG', 14600.00),
('ST001', 'SUSPENSIÓN TRASERA BÁSICA', 'BUJES DE PARRILLA SUPERIOR E INFERIOR, BUJES PUENTE TRASERO O BRAZO OSCILANTE', 22100.00),
('ST002', 'SUSPENSIÓN TRASERA COMPLETA', 'SUSPENSIÓN TRASERA BASICA + AMORTIGUADORES, ESPIRALES, PUENTE TRASERO, PARRILLAS INFERIOR Y SUPERIOR', 38200.00),
('STR00', 'SISTEMA TRACCION', 'PALIERES, TRICETAS,  HOMOCINETICAS, CARDAN, DIFERENCIAL', 17600.00),
('TP001', 'TAPA DE CILINDRO', 'REPARACION DE TAPA DE CILINDROS, CAMBIO DE JUNTAS, RETENES Y BULONES', 48500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `turno_id` int(11) NOT NULL,
  `turno_fecha` date NOT NULL,
  `turno_hora` time NOT NULL,
  `cliente_DNI` char(8) DEFAULT NULL,
  `vehiculo_patente` varchar(10) DEFAULT NULL,
  `mecanico_dni` char(8) DEFAULT NULL,
  `turno_estado` enum('pendiente','finalizado') DEFAULT 'pendiente',
  `turno_comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`turno_id`, `turno_fecha`, `turno_hora`, `cliente_DNI`, `vehiculo_patente`, `mecanico_dni`, `turno_estado`, `turno_comentario`) VALUES
(47, '2025-10-13', '08:00:00', '30700247', 'GCR891', '08326014', 'pendiente', '1234'),
(48, '0000-00-00', '00:00:00', '30700247', 'GCR891', '30700247', 'pendiente', '123456789'),
(49, '2025-10-13', '08:00:00', '30700247', 'GCR891', '30700247', 'pendiente', '123456789'),
(50, '2025-10-13', '08:00:00', '30700247', 'GCR891', '32690365', 'pendiente', '123654125874126541'),
(51, '2025-10-13', '08:00:00', '30700247', 'GCR891', '47651867', 'pendiente', '12368741236584'),
(52, '2025-10-15', '08:00:00', '30700247', 'GCR891', '32690365', 'pendiente', '1236987412'),
(54, '2025-10-14', '08:00:00', '44671150', 'A221GAR', '08326014', 'pendiente', 'testeo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `vehiculo_patente` varchar(10) NOT NULL,
  `cliente_DNI` varchar(10) NOT NULL,
  `vehiculo_marca` varchar(10) NOT NULL,
  `vehiculo_modelo` varchar(10) NOT NULL,
  `vehiculo_anio` varchar(4) DEFAULT NULL,
  `vehiculo_color` varchar(10) DEFAULT NULL,
  `vehiculo_motor` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`vehiculo_patente`, `cliente_DNI`, `vehiculo_marca`, `vehiculo_modelo`, `vehiculo_anio`, `vehiculo_color`, `vehiculo_motor`) VALUES
('A221GAR', '44671150', 'Bajaja', 'Rouser', '2024', 'Negro', '125cc'),
('AA459FT', '30164750', 'Toyota', 'Corolla', '2016', 'Blanco', 'VVTI 1.8'),
('AB307CI', '18762965', 'Volkswagen', 'Gol trend', '2017', 'Negro', 'HTV 1.6'),
('AE489AB', '28090318', 'Peugeot', '208', '2020', NULL, NULL),
('CDE091', '19786413', 'Renault', 'Clio', '2003', NULL, 'BLUE DCI'),
('EOZ386', '43796532', 'Ford', 'Fiesta', '2004', 'Rojo', 'ZETECK 1.6'),
('FOM132', '41298533', 'Fiat', 'Palio', '2012', 'Negro', 'Fire 1.6'),
('GCR891', '30700247', 'Chevrolet', 'Zafira', '2007', 'Gris', '2.0L 16V'),
('GHI410', '32489632', 'Citroen', 'Xsara', '2007', 'Rojo', 'L416V 1.8'),
('JKM733', '32489632', 'Susuki', 'Fun', NULL, 'Gris', NULL),
('NJK038', '30164750', 'Chevrolet', 'Corsa', '2011', 'Azul', NULL),
('POD166', '22870111', 'Dodge', 'Journey', '2015', 'Verde', 'DOHC Penta'),
('UWL004', '30700247', 'FIAT', '128 IAVA', '1973', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura para la vista `historico`
--
DROP TABLE IF EXISTS `historico`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `historico`  AS SELECT `o`.`orden_fecha` AS `orden_fecha`, `o`.`vehiculo_patente` AS `vehiculo_patente`, `ot`.`orden_numero` AS `orden_numero`, `ot`.`orden_kilometros` AS `orden_kilometros`, `s`.`servicio_descripcion` AS `servicio_descripcion` FROM ((`ordenes` `o` join `orden_trabajo` `ot` on(`o`.`orden_numero` = `ot`.`orden_numero`)) join `servicios` `s` on(`ot`.`servicio_codigo` = `s`.`servicio_codigo`)) ORDER BY `o`.`vehiculo_patente` ASC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_DNI`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`empleado_DNI`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`factura_id`),
  ADD UNIQUE KEY `uk_tipo_nro` (`tipo`,`nro_comprobante`),
  ADD KEY `idx_orden_serv` (`orden_numero`,`servicio_codigo`);

--
-- Indices de la tabla `factura_numeradores`
--
ALTER TABLE `factura_numeradores`
  ADD PRIMARY KEY (`tipo`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`orden_numero`),
  ADD KEY `vehiculo_patente` (`vehiculo_patente`);

--
-- Indices de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD PRIMARY KEY (`orden_numero`,`servicio_codigo`),
  ADD KEY `servicio_codigo` (`servicio_codigo`),
  ADD KEY `fk_turno_orden_trabajo` (`turno_id`),
  ADD KEY `idx_factura_id` (`factura_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`servicio_codigo`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`turno_id`),
  ADD KEY `cliente_dni` (`cliente_DNI`),
  ADD KEY `vehiculo_patente` (`vehiculo_patente`),
  ADD KEY `mecanico_dni` (`mecanico_dni`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`vehiculo_patente`),
  ADD KEY `cliente_DNI` (`cliente_DNI`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `factura_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `turno_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `ordenes_ibfk_1` FOREIGN KEY (`vehiculo_patente`) REFERENCES `vehiculos` (`vehiculo_patente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD CONSTRAINT `fk_ot_factura` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`factura_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turno_orden_trabajo` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`turno_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orden_trabajo_ibfk_1` FOREIGN KEY (`servicio_codigo`) REFERENCES `servicios` (`servicio_codigo`),
  ADD CONSTRAINT `orden_trabajo_ibfk_2` FOREIGN KEY (`orden_numero`) REFERENCES `ordenes` (`orden_numero`);

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`cliente_DNI`) REFERENCES `clientes` (`cliente_DNI`),
  ADD CONSTRAINT `turnos_ibfk_2` FOREIGN KEY (`vehiculo_patente`) REFERENCES `vehiculos` (`vehiculo_patente`),
  ADD CONSTRAINT `turnos_ibfk_3` FOREIGN KEY (`mecanico_dni`) REFERENCES `empleados` (`empleado_DNI`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`cliente_DNI`) REFERENCES `clientes` (`cliente_DNI`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
