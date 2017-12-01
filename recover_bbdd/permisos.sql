-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2017 a las 01:37:22
-- Versión del servidor: 10.1.24-MariaDB
-- Versión de PHP: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `readarkrit`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `ID_PERMISO` int(11) NOT NULL,
  `RUTA_FICHERO` varchar(500) NOT NULL,
  `ROL` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`ID_PERMISO`, `RUTA_FICHERO`, `ROL`) VALUES
(1, '/READARKRIT/html/alumno/adminAlumno.html', 'profesor'),
(2, '/READARKRIT/html/alumno/altaAlumno.html', 'visitante'),
(3, '/READARKRIT/html/alumno/formAlumno.html', 'visitante'),
(4, '/READARKRIT/html/error/error.html', 'visitante'),
(5, '/READARKRIT/html/estadistica/listarEstadistica.html', 'visitante'),
(6, '/READARKRIT/html/libro/adminLibro.html', 'profesor'),
(7, '/READARKRIT/html/libro/adminNominaciones.html', 'profesor'),
(8, '/READARKRIT/html/libro/formLibro.html', 'profesor'),
(9, '/READARKRIT/html/libro/nominarLibro.html', 'alumno'),
(10, '/READARKRIT/html/menuApp/menuApp.html', 'visitante'),
(11, '/READARKRIT/html/menuApp/menus/menuAdmin.html', 'admin'),
(12, '/READARKRIT/html/menuApp/menus/menuAlumno.html', 'alumno'),
(13, '/READARKRIT/html/menuApp/menus/menuProfesor.html', 'profesor'),
(14, '/READARKRIT/html/miBiblioteca/listarMiBiblioteca.html', 'alumno'),
(15, '/READARKRIT/html/profesor/formProfesor.html', 'profesor'),
(16, '/READARKRIT/html/profesor/listarProfesor.html', 'profesor'),
(17, '/READARKRIT/html/resena/resena.html', 'alumno'),
(18, '/READARKRIT/html/resena/resenaAdmin.html', 'profesor'),
(19, '/READARKRIT/html/load.html', 'visitante'),
(20, '/READARKRIT/html/almasGemelas/listarAlmasGemelas.html', 'alumno'),
(21, '/READARKRIT/html/clubLectura/adminClubLectura.html', 'profesor'),
(22, '/READARKRIT/html/clubLectura/anadirComentarioClub.html', 'alumno'),
(23, '/READARKRIT/html/seguirEstanteria/listarSeguirEstanteria.html', 'alumno');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`ID_PERMISO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `ID_PERMISO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
