-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-08-2025 a las 06:26:51
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
-- Base de datos: `pandaburger1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_juanperez@email.com|127.0.0.1', 'i:1;', 1754407845),
('laravel_cache_juanperez@email.com|127.0.0.1:timer', 'i:1754407845;', 1754407845);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Hamburguesas', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(2, 'Bebidas', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(3, 'Acompañamientos', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(4, 'Postres', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(5, 'Combos', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `correo_electronico` varchar(120) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` varchar(120) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `precio_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido_porciones`
--

CREATE TABLE `detalle_pedido_porciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detalle_pedido_id` bigint(20) UNSIGNED NOT NULL,
  `porcion_producto_id` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_pago`
--

CREATE TABLE `estados_pago` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estados_pago`
--

INSERT INTO `estados_pago` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Pendiente', '2025-08-05 18:28:03', '2025-08-05 18:28:03'),
(2, 'Completado', '2025-08-05 18:28:03', '2025-08-05 18:28:03'),
(3, 'Fallido', '2025-08-05 18:28:03', '2025-08-05 18:28:03'),
(4, 'Reembolsado', '2025-08-05 18:28:03', '2025-08-05 18:28:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_pedido`
--

CREATE TABLE `estados_pedido` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estados_pedido`
--

INSERT INTO `estados_pedido` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Pendiente', '2025-08-05 18:28:03', '2025-08-05 18:28:03'),
(2, 'En Preparación', '2025-08-05 18:28:03', '2025-08-05 18:28:03'),
(3, 'Listo', '2025-08-05 18:28:03', '2025-08-05 18:28:03'),
(4, 'Entregado', '2025-08-05 18:28:03', '2025-08-05 18:28:03'),
(5, 'Cancelado', '2025-08-05 18:28:03', '2025-08-05 18:28:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Efectivo', 'Pago en efectivo al momento de la entrega', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(2, 'Tarjeta de Crédito', 'Pago con tarjeta de crédito', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(3, 'Tarjeta de Débito', 'Pago con tarjeta de débito', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(4, 'Transferencia Bancaria', 'Pago mediante transferencia bancaria', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(5, 'PayPal', 'Pago a través de PayPal', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_create_roles_table', 1),
(5, '2024_01_01_000002_create_categorias_table', 1),
(6, '2024_01_01_000003_create_productos_table', 1),
(7, '2024_01_01_000004_create_estados_pedido_table', 1),
(8, '2024_01_01_000005_create_estados_pago_table', 1),
(9, '2024_01_01_000006_create_metodos_pago_table', 1),
(10, '2024_01_01_000007_modify_users_table', 1),
(11, '2024_01_01_000008_create_clientes_table', 1),
(12, '2024_01_01_000009_create_pedidos_table', 1),
(13, '2024_01_01_000010_create_detalle_pedido_table', 1),
(14, '2024_01_01_000011_create_detalle_pedido_porciones_table', 1),
(15, '2024_01_01_000012_create_pagos_table', 1),
(16, '2024_01_01_000013_add_imagen_url_to_productos_table', 1),
(18, '2025_08_05_145004_create_permisos_table', 2),
(19, '2025_08_05_145013_create_rol_permiso_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_id` bigint(20) UNSIGNED NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metodo_pago_id` smallint(5) UNSIGNED NOT NULL,
  `estado_pago_id` smallint(5) UNSIGNED NOT NULL,
  `referencia_externa` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cliente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estado_id` smallint(5) UNSIGNED NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Ver usuarios', 'Permite ver la lista de usuarios', '2025-08-05 18:54:42', '2025-08-05 18:54:42'),
(2, 'Crear usuarios', 'Permite crear nuevos usuarios', '2025-08-05 18:54:42', '2025-08-05 18:54:42'),
(3, 'Editar usuarios', 'Permite editar usuarios existentes', '2025-08-05 18:54:42', '2025-08-05 18:54:42'),
(4, 'Eliminar usuarios', 'Permite eliminar usuarios', '2025-08-05 18:54:42', '2025-08-05 18:54:42'),
(5, 'Gestionar roles', 'Permite gestionar roles y permisos', '2025-08-05 18:54:42', '2025-08-05 18:54:42'),
(6, 'Ver productos', 'Permite ver la lista de productos', '2025-08-05 18:54:42', '2025-08-05 18:54:42'),
(7, 'Crear productos', 'Permite crear nuevos productos', '2025-08-05 18:54:43', '2025-08-05 18:54:43'),
(8, 'Editar productos', 'Permite editar productos existentes', '2025-08-05 18:54:43', '2025-08-05 18:54:43'),
(9, 'Eliminar productos', 'Permite eliminar productos', '2025-08-05 18:54:43', '2025-08-05 18:54:43'),
(10, 'Gestionar pedidos', 'Permite gestionar pedidos', '2025-08-05 18:54:43', '2025-08-05 18:54:43'),
(11, 'Ver reportes', 'Permite ver reportes del sistema', '2025-08-05 18:54:43', '2025-08-05 18:54:43'),
(12, 'Configuración del sistema', 'Permite configurar el sistema', '2025-08-05 18:54:43', '2025-08-05 18:54:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `categoria_id`, `imagen_url`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Hamburguesa Clásica', 'Hamburguesa con carne, lechuga, tomate y queso', 8.50, 1, 'https://images.unsplash.com/photo-1551782450-a2132b4ba21d?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-05 18:28:04', '2025-08-06 08:00:56', NULL),
(2, 'Hamburguesa Doble', 'Hamburguesa con doble carne y queso', 12.00, 1, 'https://images.unsplash.com/photo-1572802419224-296b0aeee0d9?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-05 18:28:04', '2025-08-06 08:01:37', NULL),
(3, 'Coca Cola', 'Bebida gaseosa Coca Cola 500ml', 2.50, 2, 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-05 18:28:04', '2025-08-06 08:01:37', NULL),
(4, 'Papas Fritas', 'Papas fritas crujientes con sal', 3.50, 3, 'https://images.unsplash.com/photo-1630384060421-cb20d0e0649d?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-05 18:28:04', '2025-08-06 08:00:56', NULL),
(5, 'Hamburguesa Clásica', 'Hamburguesa con carne, lechuga, tomate y queso', 8.50, 1, 'https://images.unsplash.com/photo-1568901346375-23c9450c58e5?w=400&h=400&fit=crop', '2025-08-06 07:36:36', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(6, 'Hamburguesa Doble', 'Hamburguesa con doble carne y queso', 12.00, 1, 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=400&h=400&fit=crop', '2025-08-06 07:36:36', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(7, 'Coca Cola', 'Bebida gaseosa Coca Cola 500ml', 2.50, 2, 'https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=400&h=400&fit=crop', '2025-08-06 07:36:36', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(8, 'Papas Fritas', 'Papas fritas crujientes con sal', 3.50, 3, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400&h=400&fit=crop', '2025-08-06 07:36:36', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(9, 'Agua Mineral', 'Agua mineral sin gas 500ml', 1.50, 2, 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-06 07:36:36', '2025-08-06 08:12:31', NULL),
(10, 'Hamburguesa Clásica', 'Hamburguesa con carne, lechuga, tomate y queso', 8.50, 1, 'https://images.unsplash.com/photo-1568901346375-23c9450c58e5?w=400&h=400&fit=crop', '2025-08-06 07:46:24', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(11, 'Hamburguesa Doble', 'Hamburguesa con doble carne y queso', 12.00, 1, 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=400&h=400&fit=crop', '2025-08-06 07:46:24', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(12, 'Coca Cola', 'Bebida gaseosa Coca Cola 500ml', 2.50, 2, 'https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=400&h=400&fit=crop', '2025-08-06 07:46:24', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(13, 'Papas Fritas', 'Papas fritas crujientes con sal', 3.50, 3, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400&h=400&fit=crop', '2025-08-06 07:46:24', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(14, 'Agua Mineral', 'Agua mineral sin gas 500ml', 1.50, 2, 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&h=400&fit=crop', '2025-08-06 07:46:24', '2025-08-06 07:52:41', '2025-08-06 07:52:41'),
(15, 'Hamburguesa BBQ', 'Hamburguesa con salsa BBQ, cebolla caramelizada y bacon', 10.50, 1, 'https://images.unsplash.com/photo-1550317138-10000687a72b?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-06 08:03:43', '2025-08-06 08:03:43', NULL),
(16, 'Nuggets de Pollo', 'Tiernos nuggets de pollo con salsa a elegir', 6.50, 3, 'https://images.unsplash.com/photo-1562967914-608f82629710?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-06 08:03:43', '2025-08-06 08:03:43', NULL),
(17, 'Sprite', 'Refrescante bebida de lima-limón 500ml', 2.50, 2, 'https://images.unsplash.com/photo-1624517452488-04869289c4ca?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-06 08:03:43', '2025-08-06 08:13:27', NULL),
(18, 'Anillos de Cebolla', 'Crujientes anillos de cebolla empanizados', 4.50, 3, 'https://images.unsplash.com/photo-1639024471283-03518883512d?w=500&h=500&fit=crop&auto=format&q=80', '2025-08-06 08:03:43', '2025-08-06 08:03:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrador', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(2, 'Empleado', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL),
(3, 'Cliente', '2025-08-05 18:28:03', '2025-08-05 18:28:03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `permiso_id` smallint(5) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('GP7mCyhiBfbsfZU7gzoX1bSHSIwfrgjYOE3vXw5A', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidFViZ0g2V0E4MnRSTkUyYkFXeHhkcktvbDZsWFRqSDRjTkZVc2l2TCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0b3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1754453834);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `correo_electronico` varchar(120) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `created_at`, `updated_at`, `rol_id`, `nombre`, `correo_electronico`, `telefono`, `password_hash`, `last_login_at`, `deleted_at`) VALUES
(1, '2025-08-05 18:28:04', '2025-08-05 18:28:04', 1, 'Administrador', 'admin@pandaburger.com', NULL, '$2y$12$Xt1my0KpkSsNmuLXNsz4TO0fmbCOursvhi1ilMUxora4f4ZLdsqTK', NULL, NULL),
(2, '2025-08-05 18:29:24', '2025-08-05 18:29:24', 1, 'GROVER RAMIREZ ZELADA', 'grover.ramirez.z@gmail.com', NULL, '$2y$12$26z3HYPxoRQG2UYK7dyENesiX5Dpx1c9cneZeIb1Qq11GkLBVcJtG', NULL, NULL),
(3, '2025-08-05 19:12:34', '2025-08-05 19:12:34', 2, 'Juan Carlos Perez', 'juanperez@gmail.com', '78541263', '$2y$12$Lod6xQLzv14BFqDzjfSLye2oLF0KTsAUbm.gzACHxor3NSkaxxk86', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_pedido_pedido_id_foreign` (`pedido_id`),
  ADD KEY `detalle_pedido_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `detalle_pedido_porciones`
--
ALTER TABLE `detalle_pedido_porciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_pedido_porciones_detalle_pedido_id_foreign` (`detalle_pedido_id`);

--
-- Indices de la tabla `estados_pago`
--
ALTER TABLE `estados_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados_pedido`
--
ALTER TABLE `estados_pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pagos_pedido_id_foreign` (`pedido_id`),
  ADD KEY `pagos_metodo_pago_id_foreign` (`metodo_pago_id`),
  ADD KEY `pagos_estado_pago_id_foreign` (`estado_pago_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_usuario_id_foreign` (`usuario_id`),
  ADD KEY `pedidos_cliente_id_foreign` (`cliente_id`),
  ADD KEY `pedidos_estado_id_foreign` (`estado_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permisos_nombre_unique` (`nombre`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productos_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`rol_id`,`permiso_id`),
  ADD KEY `rol_permiso_permiso_id_foreign` (`permiso_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_correo_electronico_unique` (`correo_electronico`),
  ADD KEY `users_rol_id_foreign` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido_porciones`
--
ALTER TABLE `detalle_pedido_porciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estados_pago`
--
ALTER TABLE `estados_pago`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estados_pedido`
--
ALTER TABLE `estados_pedido`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalle_pedido_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_pedido_porciones`
--
ALTER TABLE `detalle_pedido_porciones`
  ADD CONSTRAINT `detalle_pedido_porciones_detalle_pedido_id_foreign` FOREIGN KEY (`detalle_pedido_id`) REFERENCES `detalle_pedido` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_estado_pago_id_foreign` FOREIGN KEY (`estado_pago_id`) REFERENCES `estados_pago` (`id`),
  ADD CONSTRAINT `pagos_metodo_pago_id_foreign` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`id`),
  ADD CONSTRAINT `pagos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `pedidos_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados_pedido` (`id`),
  ADD CONSTRAINT `pedidos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_permiso_id_foreign` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rol_permiso_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
