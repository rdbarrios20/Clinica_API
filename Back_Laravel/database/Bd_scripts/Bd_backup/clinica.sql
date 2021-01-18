-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2021 at 02:27 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinica`
--

-- --------------------------------------------------------

--
-- Table structure for table `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_paciente_admision` int(11) DEFAULT NULL,
  `id_servicio` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ingresos`
--

INSERT INTO `ingresos` (`id`, `id_usuario`, `id_paciente_admision`, `id_servicio`, `created_at`, `updated_at`) VALUES
(5, 3, 3333, 2, '2021-01-16 04:10:51', '2021-01-16 04:10:51'),
(6, 3, 3333, 2, '2021-01-16 04:12:31', '2021-01-16 04:12:31'),
(7, 3, 3333, 2, '2021-01-16 04:42:28', '2021-01-16 04:42:28'),
(12, 8, NULL, 2, '2021-01-17 04:14:31', '2021-01-17 04:14:31'),
(13, 9, NULL, 2, '2021-01-17 04:20:30', '2021-01-17 04:20:30'),
(14, 10, NULL, 2, '2021-01-17 04:30:12', '2021-01-17 04:30:12'),
(15, 11, 2, 2, '2021-01-18 00:55:45', '2021-01-18 00:55:45'),
(16, 12, 1, 1, '2021-01-18 00:56:48', '2021-01-18 00:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `identificacion_usuario` int(11) NOT NULL,
  `password` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`identificacion_usuario`, `password`, `created_at`, `updated_at`) VALUES
(12131, '12131', '2021-01-17 04:14:31', '2021-01-17 04:14:31'),
(23456, '23456', '2021-01-18 00:56:48', '2021-01-18 00:56:48'),
(988958, '988958', '2021-01-18 00:55:45', '2021-01-18 00:55:45'),
(4452524, '4452524', '2021-01-17 04:20:30', '2021-01-17 04:20:30'),
(4454654, '4454654', '2021-01-17 04:30:12', '2021-01-17 04:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'uci', '2021-01-15 19:41:53', '2021-01-15 19:41:53'),
(2, 'urgencias', '2021-01-15 19:41:53', '2021-01-15 19:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `identificacion` int(11) NOT NULL COMMENT 'identification',
  `nombres` varchar(45) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `identificacion`, `nombres`, `telefono`, `direccion`, `created_at`, `updated_at`) VALUES
(3, 1234, 'Octavio3', '1234567', 'cll 17 # 32-80', '2021-01-16 04:10:51', '2021-01-17 05:51:16'),
(8, 12131, 'CARRLOS', 'DASDSA', 'ADASD', '2021-01-17 04:14:31', '2021-01-17 04:14:31'),
(9, 4452524, 'dfghi', 'fghfgh', 'fghfghf', '2021-01-17 04:20:30', '2021-01-17 21:46:54'),
(10, 4454654, 'sdfsdfsdfs', 'asdfasda', 'dasfsadfsde', '2021-01-17 04:30:12', '2021-01-17 04:30:12'),
(11, 988958, 'Pacho', '3534534', 'cll 17 # 32-80', '2021-01-18 00:55:45', '2021-01-18 00:55:45'),
(12, 23456, 'Edgar', '342423', 'cra 32-46', '2021-01-18 00:56:48', '2021-01-18 00:56:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ingreso_servicios_idx` (`id_servicio`),
  ADD KEY `fk_ingresos_usuarios_idx` (`id_usuario`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`identificacion_usuario`);

--
-- Indexes for table `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion_UNIQUE` (`identificacion`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `fk_ingreso_servicios` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ingresos_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_login_usuario` FOREIGN KEY (`identificacion_usuario`) REFERENCES `usuarios` (`identificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
