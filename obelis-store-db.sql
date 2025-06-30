-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-06-2025 a las 05:46:19
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
-- Base de datos: `obelis-store-db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones_producto`
--

CREATE TABLE `calificaciones_producto` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `nombre_cliente` varchar(100) DEFAULT NULL,
  `calificacion` decimal(3,1) NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `imagen` varchar(150) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `imagen`, `estado`) VALUES
(1, 'TECNOLOGIA', 'assets/images/categorias/20220805093011.jpg', 1),
(2, 'DISEÑO WEB', 'assets/images/categorias/20220805093028.jpg', 1),
(3, 'FASHION', 'NO', 1),
(4, 'PRUEBA', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `apellido` varchar(15) NOT NULL,
  `correo` varchar(80) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `perfil` varchar(100) NOT NULL DEFAULT 'default.png',
  `token` varchar(100) DEFAULT NULL,
  `verify` int(11) NOT NULL DEFAULT 0,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `correo`, `clave`, `perfil`, `token`, `verify`, `foto`) VALUES
(3, 'ANGEL SIFUENTES', '', 'lovenaju2@gmail.com', '$2y$10$aFqMg0hAfmLz0vUQhPKcPuGQ57zO9bvYDEX83E2yIN5sdwEX/eH5q', 'default.png', '21a66551b83c814b108081b292dc65d2', 1, NULL),
(4, 'VIDA INFORMATICO', '', 'admin@angelsifuentes.com', '$2y$10$aFqMg0hAfmLz0vUQhPKcPuGQ57zO9bvYDEX83E2yIN5sdwEX/eH5q', 'default.png', NULL, 1, NULL),
(5, 'JUAN ANGEL', '', 'prueba@gmail.com', '$2y$10$rr24uW0VypkrDEhooFRsoOli/Pcm5NR5F8D9NARgQz0l1UfPkqV1q', 'default.png', 'c81b5136bcd10b4390108c979ed28ee6', 1, NULL),
(7, 'PPKNTOO', 'Rondón', 'peppecanto18@gmail.com', '$2y$10$TbpZ65g9/197MJtn3cdks.lUppdoHYfgjA7NNDyVdkOY/9aPFe3oi', 'default.png', NULL, 1, '683bc1a8998db.jpg'),
(8, 'Samuel', '', 'samuelrondonpaechatefly@gmail.com', '$2y$10$5MuM/dJfgs3YNRw1Dzz9MutyepaC.QKiQkxRFzBD7LkDblZwG4kCy', 'default.png', '2b9510d3b14f18403285bb227427f3fa', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores_producto`
--

CREATE TABLE `colores_producto` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id` int(11) NOT NULL,
  `producto` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `talla` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id`, `producto`, `precio`, `cantidad`, `id_pedido`, `id_producto`, `color`, `talla`) VALUES
(1, 'CAMARA WEB', 200.00, 1, 1, 2, NULL, NULL),
(2, 'TELEVISOR LG', 1500.00, 1, 1, 3, NULL, NULL),
(3, 'NUEVO PRODUCTO', 5.00, 1, 2, 6, NULL, NULL),
(4, 'FASHION 2', 3.00, 1, 2, 8, NULL, NULL),
(5, 'FASHION1', 8.00, 1, 2, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_transaccion` varchar(80) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `fecha` datetime NOT NULL,
  `email` varchar(80) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `proceso` enum('1','2','3') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_transaccion`, `monto`, `estado`, `fecha`, `email`, `nombre`, `apellido`, `direccion`, `ciudad`, `id_cliente`, `proceso`) VALUES
(1, '6PX22696F1040260E', 1700.00, 'COMPLETED', '2023-02-20 17:12:42', 'sb-j6jdb7896999@personal.example.com', 'John', 'Doe', 'Free Trade Zone', 'Lima', 5, '2'),
(2, '1HJ95121A9582915R', 16.00, 'COMPLETED', '2023-07-10 22:37:24', 'sb-j6jdb7896999@personal.example.com', 'John', 'Doe', 'Free Trade Zone', 'Lima', 3, '2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios_competencia`
--

CREATE TABLE `precios_competencia` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `fuente` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `imagen` varchar(150) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `cantidad`, `imagen`, `estado`, `destacado`, `id_categoria`) VALUES
(1, 'PARLANTE HILON', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur aperiam accusamus incidunt cum laudantium laborum ipsum magni sequi expedita ad, rem esse rerum ea saepe provident! Temporibus corporis atque earum?\r\n', 10.00, 10, 'assets/images/productos/20220805093212.jpg', 1, 1, 1),
(2, 'CAMARA WEB', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur aperiam accusamus incidunt cum laudantium laborum ipsum magni sequi expedita ad, rem esse rerum ea saepe provident! Temporibus corporis atque earum?', 20.00, 15, 'assets/images/productos/20220805093304.jpg', 1, 1, 1),
(3, 'TELEVISOR LG', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur aperiam accusamus incidunt cum laudantium laborum ipsum magni sequi expedita ad, rem esse rerum ea saepe provident! Temporibus corporis atque earum?', 10.00, 40, 'assets/images/productos/20220805093355.jpg', 1, 1, 1),
(4, 'MICROFONO', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur aperiam accusamus incidunt cum laudantium laborum ipsum magni sequi expedita ad, rem esse rerum ea saepe provident! Temporibus corporis atque earum?', 8.00, 5, 'assets/images/productos/20220805093426.jpg', 1, 1, 1),
(5, 'LAPTOP LENOVO', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur aperiam accusamus incidunt cum laudantium laborum ipsum magni sequi expedita ad, rem esse rerum ea saepe provident! Temporibus corporis atque earum?', 30.00, 12, 'assets/images/productos/20220805093535.jpg', 1, 1, 1),
(6, 'NUEVO PRODUCTO', '<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Incidunt, facere.</p><ol><li>Lorem ipsum, dolor sit amet <strong>consectetur</strong> adipisicing elit. Incidunt, facere.</li><li><a href=\"https://angelsifuentes.com/pos\">https://angelsifuentes.com/pos</a></li></ol>', 5.00, 16, 'assets/images/productos/20220806090232.jpg', 1, 1, 2),
(7, 'FASHION1', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur aperiam accusamus incidunt cum laudantium laborum ipsum magni sequi expedita ad, rem esse rerum ea saepe provident! Temporibus corporis atque earum?', 8.00, 5, 'assets/images/productos/fashion1.png', 1, 1, 3),
(8, 'FASHION 2', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur aperiam accusamus incidunt cum laudantium laborum ipsum magni sequi expedita ad, rem esse rerum ea saepe provident! Temporibus corporis atque earum?', 3.00, 12, 'assets/images/productos/fashion2.png', 1, 0, 3),
(10, 'vamos', 'si se puede', 12.00, 12, 'assets/images/productos/20230710235600.jpg', 1, 0, 4),
(11, 'NUEVO PRODUCTO', '', 12.37, 15, 'assets/images/productos/20250602034157.jpg', 1, 1, 3),
(12, 'NUEVO PRODUCTO', '', 12.37, 15, 'assets/images/productos/20250602034214.jpg', 1, 0, 1),
(13, 'Iphone 14 Pro Max', 'El iPhone 14 Pro Max tiene la capacidad de grabar videos en 4K y también puede reproducir contenido en 4K. La cámara principal de 48MP y el resto de las cámaras, incluyendo la gran angular y el teleobjetivo, pueden capturar videos de alta calidad. La pantalla Super Retina XDR de 6.7 pulgadas del iPhone 14 Pro Max también está diseñada para mostrar contenido en 4K de manera nítida y vibrante.', 1275.00, 100, 'assets/images/productos/20250602134628.jpg', 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas_producto`
--

CREATE TABLE `tallas_producto` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `talla` varchar(10) NOT NULL,
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `perfil` varchar(50) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `correo`, `clave`, `perfil`, `estado`) VALUES
(1, 'ANGEL', 'SIFUENTES', 'angelsifuentes2580@gmail.com', '$2y$10$XtV68JJ/fABHwWMARZotF.9BHbkwBpquycry2HYOlS.QaUGt/mls2', NULL, 0),
(2, 'hola', 'como', 'esta@gmail.com', '$2y$10$ZX9BeymxshOoe/I3pGoIp.6Ai5mrVxN1rdS4nrAQILsOjE9DPwdXW', NULL, 0),
(3, 'PPKNTOO', '...', 'peppecanto18@gmail.com', '$2y$10$P.lZlLe3XSs1YD5mdJv2a.GPpnvXzap5WqEeo2YotUTwf/cNHqLJu', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calificaciones_producto`
--
ALTER TABLE `calificaciones_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto_calificacion` (`id_producto`),
  ADD KEY `idx_cliente_calificacion` (`id_cliente`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `colores_producto`
--
ALTER TABLE `colores_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto_color` (`id_producto`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `precios_competencia`
--
ALTER TABLE `precios_competencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `tallas_producto`
--
ALTER TABLE `tallas_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto_talla` (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calificaciones_producto`
--
ALTER TABLE `calificaciones_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `colores_producto`
--
ALTER TABLE `colores_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `precios_competencia`
--
ALTER TABLE `precios_competencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tallas_producto`
--
ALTER TABLE `tallas_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calificaciones_producto`
--
ALTER TABLE `calificaciones_producto`
  ADD CONSTRAINT `calificaciones_producto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calificaciones_producto_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `colores_producto`
--
ALTER TABLE `colores_producto`
  ADD CONSTRAINT `colores_producto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `tallas_producto`
--
ALTER TABLE `tallas_producto`
  ADD CONSTRAINT `tallas_producto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
