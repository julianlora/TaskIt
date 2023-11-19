-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2023 a las 20:51:39
-- Versión del servidor: 8.0.34
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taskit`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas`
--

CREATE TABLE `etiquetas` (
  `id` int NOT NULL,
  `texto` varchar(20) NOT NULL,
  `color` varchar(10) NOT NULL,
  `id_usuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `etiquetas`
--

INSERT INTO `etiquetas` (`id`, `texto`, `color`, `id_usuario`) VALUES
(19, 'Universidad', '#1ebe20', 3),
(20, 'Trabajo', '#ff00a2', 3),
(22, 'Personal', '#0040ff', 3),
(23, 'Personal', '#ffbb00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `texto` varchar(200) NOT NULL,
  `id_lista` int NOT NULL,
  `nivel` int NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `id_item_padre` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `texto`, `id_lista`, `nivel`, `tipo`, `checked`, `id_item_padre`) VALUES
(81, 'Tarea1', 69, 0, 'sublista', 0, NULL),
(82, 'Tarea2', 69, 0, 'item', 0, NULL),
(83, 'Tarea3', 69, 0, 'sublista', 0, NULL),
(84, 'Tarea4', 69, 0, 'item', 0, NULL),
(85, 'Subtarea1', 69, 1, 'sublista', 0, 81),
(86, 'Subtarea2', 69, 1, 'subitem', 0, 81),
(87, 'Subtarea', 69, 1, 'subitem', 0, 83),
(88, 'Subtarea', 69, 2, 'subitem', 0, 85),
(89, 'Subtarea', 69, 2, 'subitem', 0, 85),
(90, 'Tarea1', 71, 0, 'item', 1, NULL),
(91, 'Tarea2sdf', 71, 0, 'item', 1, NULL),
(100, 'Calendario', 72, 0, 'sublista', 0, NULL),
(101, 'Perfil', 72, 0, 'item', 0, NULL),
(102, 'Compartir listas', 72, 0, 'sublista', 0, NULL),
(103, 'Enviar mail', 72, 0, 'item', 1, NULL),
(104, 'Items', 72, 0, 'sublista', 0, NULL),
(106, 'pan', 75, 0, 'item', 0, NULL),
(107, 'yerba', 75, 0, 'item', 0, NULL),
(108, 'salame', 75, 0, 'item', 0, NULL),
(109, 'aceitunas', 75, 0, 'item', 0, NULL),
(111, 'aceite de coco', 75, 0, 'item', 0, NULL),
(112, 'alfajor terrabusi triple', 75, 0, 'item', 0, NULL),
(114, 'otra cosa', 73, 0, 'item', 0, NULL),
(115, 'mas cosas', 73, 0, 'sublista', 1, NULL),
(116, 'uno', 73, 1, 'subitem', 1, 115),
(118, 'Suscripcion', 72, 0, 'sublista', 0, NULL),
(119, 'Contacto', 72, 0, 'item', 1, NULL),
(120, 'mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm', 71, 0, 'item', 0, NULL),
(121, 'Tarealargaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 71, 0, 'item', 0, NULL),
(122, 'Agregar eliminar miembro', 72, 1, 'subitem', 1, 102),
(123, 'Checkbox', 72, 0, 'sublista', 0, NULL),
(124, 'cuando se agrega un subitem no se actualizan', 72, 1, 'subitem', 1, 123),
(125, 'editar texto', 72, 1, 'subitem', 1, 104),
(126, 'mover', 72, 1, 'subitem', 0, 104),
(128, 'Agregar roles', 72, 1, 'subitem', 1, 102),
(129, 'Listas', 72, 0, 'sublista', 0, NULL),
(130, 'falta checkear titulo al crear', 72, 1, 'subitem', 1, 129),
(131, 'esconder checkeados', 72, 1, 'subitem', 1, 129),
(133, 'modificar etiqueta', 72, 1, 'subitem', 1, 129),
(134, 'modificar fecha de finalizacion', 72, 1, 'subitem', 0, 129),
(137, 'cosa1', 73, 0, 'item', 0, NULL),
(157, 'tipos de items', 72, 1, 'subitem', 0, 104),
(158, 'abandonar lista', 72, 1, 'subitem', 1, 102),
(161, 'archivos adjuntos', 72, 1, 'subitem', 0, 129),
(163, 'Cerrar ventanas al abrir otras', 72, 0, 'item', 0, NULL),
(164, 'mover', 72, 1, 'sublista', 0, 129),
(165, 'hay problemas probablemente con la acumulacion de eventos', 72, 2, 'subitem', 0, 164),
(167, 'silenciar notificaciones', 72, 1, 'subitem', 0, 129),
(168, 'cambiar tipo de acceso de la lista al eliminar todos los miembros', 72, 1, 'subitem', 0, 102),
(169, 'Notificaciones', 72, 0, 'sublista', 0, NULL),
(170, 'en chekcs', 72, 1, 'subitem', 0, 169),
(171, 'en fecha de finalizacion', 72, 1, 'subitem', 0, 169),
(172, 'editar titulo', 72, 1, 'subitem', 0, 129),
(173, 'Ventana', 72, 0, 'sublista', 0, NULL),
(174, 'mantener ubicacion de pagina al refreshear', 72, 1, 'subitem', 0, 173),
(175, 'retraer', 72, 1, 'subitem', 1, 129),
(177, 'mantener ventana al seleccionar opcion de lista', 72, 1, 'subitem', 0, 173),
(178, 'Usuario', 72, 0, 'sublista', 1, NULL),
(179, 'nombre, apellido, mail', 72, 1, 'subitem', 1, 178),
(180, 'slider meses', 72, 1, 'subitem', 0, 100),
(181, 'compartidas no propietario solo a listas compartidas', 72, 1, 'subitem', 1, 129),
(182, 'seguir vencimiento', 72, 1, 'subitem', 0, 118),
(184, 'una cosa', 73, 0, 'item', 0, NULL),
(187, 'al crear subitem y eliminarlo se checkea el item padre aunque estaba todo descheckeado', 72, 1, 'subitem', 0, 123),
(191, 'el lector todavía puede modificar items', 72, 1, 'subitem', 0, 102),
(192, 'etiqueta', 72, 0, 'sublista', 0, NULL),
(193, 'no muestra las compartidas no propias con etiqueta', 72, 1, 'subitem', 0, 192);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listas`
--

CREATE TABLE `listas` (
  `id` int NOT NULL,
  `titulo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `etiqueta` varchar(20) DEFAULT NULL,
  `id_usuario` int NOT NULL,
  `fecha_finalizacion` date DEFAULT NULL,
  `acceso` varchar(10) NOT NULL DEFAULT 'privado',
  `esconder_terminadas` tinyint(1) NOT NULL DEFAULT '0',
  `minimizada` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `listas`
--

INSERT INTO `listas` (`id`, `titulo`, `etiqueta`, `id_usuario`, `fecha_finalizacion`, `acceso`, `esconder_terminadas`, `minimizada`) VALUES
(69, 'Metodología de Sistemas', 'Universidad', 3, '2023-11-03', 'compartido', 0, 0),
(71, 'Proyecto 1', 'Trabajo', 3, NULL, 'privado', 0, 0),
(72, 'Laboratorio 4', 'Universidad', 3, '2023-11-01', 'compartido', 0, 0),
(73, 'Lista sin etiqueta', NULL, 3, '2023-11-11', 'privado', 0, 0),
(75, 'Lista de compras', 'Personal', 3, NULL, 'compartido', 0, 0),
(130, 'Nueva lista', '', 7, '2023-11-23', 'compartido', 0, 0),
(131, 'Nueva lista', '', 1, '2023-11-23', 'compartido', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listas_compartidas`
--

CREATE TABLE `listas_compartidas` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `rol` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'colaborador',
  `id_lista` int NOT NULL,
  `id_etiqueta` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `listas_compartidas`
--

INSERT INTO `listas_compartidas` (`id`, `id_usuario`, `rol`, `id_lista`, `id_etiqueta`) VALUES
(8, 3, 'administrador', 72, NULL),
(9, 1, 'colaborador', 72, NULL),
(28, 3, 'administrador', 69, NULL),
(124, 4, 'lector', 72, NULL),
(127, 1, 'colaborador', 69, NULL),
(128, 7, 'administrador', 130, NULL),
(130, 3, 'administrador', 75, NULL),
(131, 7, 'lector', 75, NULL),
(132, 1, 'administrador', 131, NULL),
(133, 3, 'lector', 131, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int NOT NULL,
  `id_usuario_destino` int NOT NULL,
  `mensaje` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'pendiente',
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `id_usuario_destino`, `mensaje`, `estado`, `fecha`) VALUES
(3, 1, 'admin2 te ha compartido la lista ddffd.', 'leido', '2023-10-31'),
(4, 1, 'admin2 te ha compartido la lista aaaaaaaaa.', 'leido', '2023-10-31'),
(5, 3, 'admin te ha compartido la lista asdfasfa.', 'leido', '2023-10-31'),
(6, 1, 'admin2 te ha compartido la lista asdasd.', 'leido', '2023-10-31'),
(7, 1, 'admin2 te ha compartido la lista Metodología de Sistemas.', 'leido', '2023-11-01'),
(8, 1, 'admin2 te ha compartido la lista dfsf.', 'leido', '2023-11-01'),
(9, 3, 'admin te ha compartido la lista fffff.', 'leido', '2023-11-01'),
(10, 1, 'admin2 te ha compartido la lista asdasd.', 'leido', '2023-11-01'),
(11, 1, 'admin2 te ha compartido la lista sfsfsfsf.', 'leido', '2023-11-01'),
(12, 1, 'admin2 te ha compartido la lista aaaaaa.', 'leido', '2023-11-01'),
(13, 1, 'admin2 te ha compartido la lista aaaaaa.', 'leido', '2023-11-01'),
(14, 1, 'admin2 te ha compartido la lista asdfasf.', 'leido', '2023-11-01'),
(15, 3, 'admin ha abandonado la lista asdfasf.', 'leido', '2023-11-01'),
(16, 1, 'admin2 te ha compartido la lista asdfaf.', 'leido', '2023-11-01'),
(17, 3, 'admin ha abandonado la lista asdfaf.', 'leido', '2023-11-01'),
(18, 4, 'admin2 te ha compartido la lista copmaritmientoso.', 'leido', '2023-11-01'),
(19, 1, 'admin2 te ha compartido la lista copmaritmientoso.', 'leido', '2023-11-01'),
(20, 4, 'admin ha abandonado la lista copmaritmientoso.', 'leido', '2023-11-01'),
(21, 1, 'admin2 te ha compartido la lista fadfafa.', 'leido', '2023-11-01'),
(22, 4, 'admin2 te ha compartido la lista fadfafa.', 'leido', '2023-11-01'),
(23, 3, 'admin ha abandonado la lista fadfafa.', 'leido', '2023-11-01'),
(24, 4, 'admin ha abandonado la lista fadfafa.', 'leido', '2023-11-01'),
(25, 1, 'admin2 te ha compartido la lista adsssdadad.', 'leido', '2023-11-01'),
(26, 4, 'admin2 te ha compartido la lista adsssdadad.', 'leido', '2023-11-01'),
(27, 1, 'admin2 se ha unido a la lista adsssdadad.', 'leido', '2023-11-01'),
(28, 1, 'admin2 te ha compartido la lista dafasdfdasf.', 'leido', '2023-11-01'),
(29, 1, 'admin2 te ha compartido la lista aaaaaa.', 'leido', '2023-11-01'),
(30, 4, 'admin2 te ha compartido la lista aaaaaa.', 'leido', '2023-11-01'),
(31, 1, 'admin3 se ha unido a la lista aaaaaa.', 'leido', '2023-11-01'),
(32, 1, 'admin2 te ha unido a la lista gasdfas como colaborador.', 'leido', '2023-11-01'),
(33, 4, 'admin2 te ha unido a la lista gasdfas como administrador.', 'pendiente', '2023-11-01'),
(34, 1, 'admin3 se ha unido a la lista gasdfas como administrador.', 'leido', '2023-11-01'),
(35, 3, 'admin te ha unido a la lista asdasdsa como colaborador.', 'leido', '2023-11-01'),
(36, 1, 'admin2 te ha unido a la lista afssafas como colaborador.', 'leido', '2023-11-01'),
(37, 1, 'admin2 te ha unido a la lista adasdasdd como colaborador.', 'leido', '2023-11-01'),
(38, 1, 'admin2 te ha unido a la lista fff como colaborador.', 'leido', '2023-11-01'),
(39, 1, 'admin2 te ha unido a la lista fafa como colaborador.', 'leido', '2023-11-01'),
(40, 1, 'admin2 te ha unido a la lista dsad como colaborador.', 'leido', '2023-11-01'),
(41, 1, 'admin2 te ha unido a la lista adsdsaasd como colaborador.', 'leido', '2023-11-01'),
(42, 1, 'admin2 te ha unido a la lista ddsds como colaborador.', 'leido', '2023-11-01'),
(43, 1, 'admin2 te ha unido a la lista sssssss como colaborador.', 'leido', '2023-11-01'),
(44, 1, 'La lista sssssss ha sido eliminada.', 'leido', '2023-11-01'),
(45, 4, 'admin2 te ha unido a la lista Laboratorio 4 como lector.', 'pendiente', '2023-11-04'),
(46, 1, 'admin3 se ha unido a la lista Laboratorio 4 como lector.', 'leido', '2023-11-04'),
(47, 4, 'admin2 te ha unido a la lista Laboratorio 4 como lector.', 'pendiente', '2023-11-05'),
(48, 1, 'admin3 se ha unido a la lista Laboratorio 4 como lector.', 'leido', '2023-11-05'),
(49, 4, 'admin2 te ha unido a la lista Laboratorio 4 como lector.', 'pendiente', '2023-11-05'),
(50, 1, 'admin3 se ha unido a la lista Laboratorio 4 como lector.', 'leido', '2023-11-05'),
(51, 4, 'admin2 te ha unido a la lista Laboratorio 4 como lector.', 'pendiente', '2023-11-05'),
(52, 1, 'admin3 se ha unido a la lista Laboratorio 4 como lector.', 'leido', '2023-11-05'),
(53, 4, 'admin2 te ha unido a la lista Laboratorio 4 como lector.', 'pendiente', '2023-11-05'),
(54, 1, 'admin3 se ha unido a la lista Laboratorio 4 como lector.', 'leido', '2023-11-05'),
(55, 4, 'admin2 te ha unido a la lista Laboratorio 4 como lector.', 'pendiente', '2023-11-05'),
(56, 1, 'admin3 se ha unido a la lista Laboratorio 4 como lector.', 'leido', '2023-11-05'),
(57, 4, 'admin2 te ha unido a la lista Laboratorio 4 como lector.', 'pendiente', '2023-11-05'),
(58, 1, 'admin3 se ha unido a la lista Laboratorio 4 como lector.', 'leido', '2023-11-05'),
(59, 4, 'admin2 te ha convertido en lector de la lista Laboratorio 4.', 'pendiente', '2023-11-05'),
(60, 1, 'admin3 se ha convertido en lector de la lista Laboratorio 4.', 'leido', '2023-11-05'),
(61, 1, 'admin2 te ha unido a la lista Lista de compras como administrador.', 'leido', '2023-11-13'),
(62, 3, 'admin ha abandonado la lista Metodología de Sistemas.', 'leido', '2023-11-13'),
(63, 1, 'admin2 te ha unido a la lista Metodología de Sistemas como colaborador.', 'pendiente', '2023-11-13'),
(64, 3, 'pepe te ha unido a la lista Nueva lista como colaborador.', 'leido', '2023-11-15'),
(65, 7, 'admin2 te ha unido a la lista Lista de compras como lector.', 'pendiente', '2023-11-17'),
(66, 3, 'admin te ha unido a la lista Nueva lista como lector.', 'leido', '2023-11-18'),
(67, 7, 'admin2 ha abandonado la lista Nueva lista.', 'pendiente', '2023-11-18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscripciones`
--

CREATE TABLE `suscripciones` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `fecha` date NOT NULL,
  `plan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `suscripciones`
--

INSERT INTO `suscripciones` (`id`, `id_usuario`, `fecha`, `plan`) VALUES
(3, 3, '2023-11-13', 'mensual'),
(4, 6, '2023-11-15', 'mensual'),
(5, 7, '2023-11-15', 'mensual'),
(6, 1, '2023-11-18', 'anual');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `categoria` varchar(20) NOT NULL DEFAULT 'estandar',
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `nombre`, `apellido`, `categoria`, `email`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Ignacio', 'Dazza', 'suscriptor', 'julian.ariel.lora.96@outlook.com'),
(3, 'admin2', 'c84258e9c39059a89ab77d846ddab909', 'Julián', 'Lora', 'suscriptor', 'julian.ariel.lora.96@outlook.com'),
(4, 'admin3', '32cacb2f994f6b42183a1300d9a3e8d6', 'Agustín', 'Saporiti', 'estandar', 'julian.ariel.lora.96@outlook.com'),
(6, 'aaa', '47bce5c74f589f4867dbd57e9ca9f808', 'aaaa', 'nuevoaaa', 'suscriptor', 'julian.lora.96@gmail.com'),
(7, 'pepe', '926e27eecdbc7a18858b3798ba99bddd', 'Pepe', 'Garcia', 'suscriptor', 'julian.lora.96@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lista` (`id_lista`),
  ADD KEY `id_item_padre` (`id_item_padre`);

--
-- Indices de la tabla `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `listas_compartidas`
--
ALTER TABLE `listas_compartidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lista` (`id_lista`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_etiqueta` (`id_etiqueta`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_destino` (`id_usuario_destino`);

--
-- Indices de la tabla `suscripciones`
--
ALTER TABLE `suscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT de la tabla `listas`
--
ALTER TABLE `listas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT de la tabla `listas_compartidas`
--
ALTER TABLE `listas_compartidas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `suscripciones`
--
ALTER TABLE `suscripciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD CONSTRAINT `etiquetas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Filtros para la tabla `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`id_lista`) REFERENCES `listas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`id_item_padre`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Filtros para la tabla `listas`
--
ALTER TABLE `listas`
  ADD CONSTRAINT `listas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Filtros para la tabla `listas_compartidas`
--
ALTER TABLE `listas_compartidas`
  ADD CONSTRAINT `listas_compartidas_ibfk_1` FOREIGN KEY (`id_lista`) REFERENCES `listas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `listas_compartidas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `listas_compartidas_ibfk_3` FOREIGN KEY (`id_etiqueta`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario_destino`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Filtros para la tabla `suscripciones`
--
ALTER TABLE `suscripciones`
  ADD CONSTRAINT `suscripciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
