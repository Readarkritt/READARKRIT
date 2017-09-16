-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2017 a las 19:03:54
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
CREATE DATABASE IF NOT EXISTS `readarkrit` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `readarkrit`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

DROP TABLE IF EXISTS `alumno`;
CREATE TABLE `alumno` (
  `ID_ALUMNO` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `NUM_EXPEDIENTE` mediumint(8) UNSIGNED NOT NULL,
  `ID_TITULACION` tinyint(3) UNSIGNED NOT NULL,
  `CURSO` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `alumno`:
--   `ID_USUARIO`
--       `usuario` -> `ID_USUARIO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_libro`
--

DROP TABLE IF EXISTS `categoria_libro`;
CREATE TABLE `categoria_libro` (
  `ID_CATEGORIA` int(11) NOT NULL,
  `NOMBRE` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `categoria_libro`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `club_lectura`
--

DROP TABLE IF EXISTS `club_lectura`;
CREATE TABLE `club_lectura` (
  `ID_CLUB` int(10) UNSIGNED NOT NULL,
  `CREADO_POR` int(10) UNSIGNED NOT NULL,
  `NOMBRE` varchar(20) NOT NULL,
  `F_INICIO` date NOT NULL,
  `F_FIN` date NOT NULL,
  `ID_TITULACION` int(10) UNSIGNED NOT NULL,
  `CURSO` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `club_lectura`:
--   `CREADO_POR`
--       `usuario` -> `ID_USUARIO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario_club`
--

DROP TABLE IF EXISTS `comentario_club`;
CREATE TABLE `comentario_club` (
  `ID_COMENTARIO_CLUB` int(10) UNSIGNED NOT NULL,
  `ID_CLUB` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `FECHA` date NOT NULL,
  `COMENTARIO` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `comentario_club`:
--   `ID_USUARIO`
--       `usuario` -> `ID_USUARIO`
--   `ID_CLUB`
--       `club_lectura` -> `ID_CLUB`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estanteria`
--

DROP TABLE IF EXISTS `estanteria`;
CREATE TABLE `estanteria` (
  `ID_ESTANTERIA` int(10) UNSIGNED NOT NULL,
  `NOMBRE` varchar(20) NOT NULL,
  `CREADA_POR` int(10) UNSIGNED NOT NULL COMMENT 'el id_usuario que ha creado la estantería'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `estanteria`:
--   `ID_ESTANTERIA`
--       `usuario_sigue_estanteria` -> `ID_ESTANTERIA`
--   `CREADA_POR`
--       `usuario` -> `ID_USUARIO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

DROP TABLE IF EXISTS `libro`;
CREATE TABLE `libro` (
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `PORTADA` varchar(255) NOT NULL,
  `TITULO` varchar(50) NOT NULL,
  `TITULO_ORIGINAL` varchar(50) NOT NULL,
  `AUTOR` varchar(50) NOT NULL,
  `ANO` tinyint(3) UNSIGNED NOT NULL,
  `ANADIDO_POR` int(10) UNSIGNED NOT NULL COMMENT 'id_usuario del que lo subió, si es un profesor se pone un 0 (ARKRIT)',
  `ID_TITULACION` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `libro`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_anadido`
--

DROP TABLE IF EXISTS `libro_anadido`;
CREATE TABLE `libro_anadido` (
  `ID_LIBRO_ANADIDO` int(10) UNSIGNED NOT NULL,
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `ID_PAIS` int(10) UNSIGNED NOT NULL,
  `ID_CATEGORIA` int(10) UNSIGNED NOT NULL,
  `POSICION_RANKING` smallint(5) UNSIGNED NOT NULL,
  `MEDIA_NUM_USUARIOS` tinyint(3) UNSIGNED DEFAULT NULL,
  `NIVEL_ESPECIALIZACION` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `libro_anadido`:
--   `ID_LIBRO`
--       `libro` -> `ID_LIBRO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_propuesto`
--

DROP TABLE IF EXISTS `libro_propuesto`;
CREATE TABLE `libro_propuesto` (
  `ID_LIBRO_PROPUESTO` int(10) UNSIGNED NOT NULL,
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `PROPUESTO_PARA` varchar(10) NOT NULL COMMENT 'propuesto para añadir o quitar de la biblioteca ARKRIT',
  `MOTIVO` text NOT NULL,
  `NUM_VOTOS` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `libro_propuesto`:
--   `ID_LIBRO`
--       `libro` -> `ID_LIBRO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembro_club`
--

DROP TABLE IF EXISTS `miembro_club`;
CREATE TABLE `miembro_club` (
  `ID` int(10) UNSIGNED NOT NULL,
  `ID_CLUB` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `miembro_club`:
--   `ID_CLUB`
--       `club_lectura` -> `ID_CLUB`
--   `ID_USUARIO`
--       `usuario` -> `ID_USUARIO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

DROP TABLE IF EXISTS `pais`;
CREATE TABLE `pais` (
  `ID_PAIS` int(10) UNSIGNED NOT NULL,
  `ISO` char(2) DEFAULT NULL,
  `NOMBRE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `pais`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

DROP TABLE IF EXISTS `profesor`;
CREATE TABLE `profesor` (
  `ID_PROFESOR` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `ES_ADMIN` tinyint(1) NOT NULL,
  `EVITAR_NOTIFICACION` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `profesor`:
--   `ID_USUARIO`
--       `usuario` -> `ID_USUARIO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_libro_estanteria`
--

DROP TABLE IF EXISTS `rel_libro_estanteria`;
CREATE TABLE `rel_libro_estanteria` (
  `ID_REL_LIBRO_ESTANTERIA` int(10) UNSIGNED NOT NULL,
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `ID_ESTANTERIA` int(10) UNSIGNED NOT NULL,
  `LIBRO_LEIDO` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `rel_libro_estanteria`:
--   `ID_LIBRO`
--       `libro` -> `ID_LIBRO`
--   `ID_ESTANTERIA`
--       `estanteria` -> `ID_ESTANTERIA`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

DROP TABLE IF EXISTS `resena`;
CREATE TABLE `resena` (
  `ID_RESENA` int(10) UNSIGNED NOT NULL,
  `NOTA` tinyint(3) UNSIGNED NOT NULL,
  `COMENTARIO` text NOT NULL,
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `FECHA_ALTA` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `resena`:
--   `ID_USUARIO`
--       `usuario` -> `ID_USUARIO`
--   `ID_LIBRO`
--       `libro` -> `ID_LIBRO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulacion`
--

DROP TABLE IF EXISTS `titulacion`;
CREATE TABLE `titulacion` (
  `ID_TITULACION` int(11) NOT NULL,
  `NOMBRE` varchar(30) NOT NULL,
  `DURACION` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `titulacion`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `NOMBRE` varchar(40) NOT NULL,
  `PRIMER_APELLIDO` varchar(30) NOT NULL,
  `SEGUNDO_APELLIDO` varchar(30) NOT NULL,
  `F_NACIMIENTO` date NOT NULL,
  `CORREO` varchar(100) NOT NULL,
  `NOMBRE_USUARIO` varchar(20) NOT NULL,
  `CONTRASENA` char(255) NOT NULL,
  `BLOQUEADO` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `usuario`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_sigue_estanteria`
--

DROP TABLE IF EXISTS `usuario_sigue_estanteria`;
CREATE TABLE `usuario_sigue_estanteria` (
  `ID` int(10) UNSIGNED NOT NULL,
  `ID_ESTANTERIA` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `usuario_sigue_estanteria`:
--   `ID_USUARIO`
--       `usuario` -> `ID_USUARIO`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`ID_ALUMNO`),
  ADD UNIQUE KEY `ID_USUARIO` (`ID_USUARIO`),
  ADD UNIQUE KEY `NUM_EXPEDIENTE` (`NUM_EXPEDIENTE`);

--
-- Indices de la tabla `categoria_libro`
--
ALTER TABLE `categoria_libro`
  ADD PRIMARY KEY (`ID_CATEGORIA`);

--
-- Indices de la tabla `club_lectura`
--
ALTER TABLE `club_lectura`
  ADD PRIMARY KEY (`ID_CLUB`),
  ADD UNIQUE KEY `NOMBRE` (`NOMBRE`),
  ADD KEY `CREADO_POR` (`CREADO_POR`);

--
-- Indices de la tabla `comentario_club`
--
ALTER TABLE `comentario_club`
  ADD PRIMARY KEY (`ID_COMENTARIO_CLUB`),
  ADD UNIQUE KEY `ID_CLUB` (`ID_CLUB`),
  ADD KEY `ID_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `estanteria`
--
ALTER TABLE `estanteria`
  ADD PRIMARY KEY (`ID_ESTANTERIA`),
  ADD KEY `CREADA_POR` (`CREADA_POR`);

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`ID_LIBRO`),
  ADD KEY `ID_TITULACION` (`ID_TITULACION`),
  ADD KEY `ANADIDO_POR` (`ANADIDO_POR`);

--
-- Indices de la tabla `libro_anadido`
--
ALTER TABLE `libro_anadido`
  ADD PRIMARY KEY (`ID_LIBRO_ANADIDO`),
  ADD UNIQUE KEY `ID_LIBRO` (`ID_LIBRO`);

--
-- Indices de la tabla `libro_propuesto`
--
ALTER TABLE `libro_propuesto`
  ADD PRIMARY KEY (`ID_LIBRO_PROPUESTO`),
  ADD UNIQUE KEY `ID_LIBRO` (`ID_LIBRO`);

--
-- Indices de la tabla `miembro_club`
--
ALTER TABLE `miembro_club`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_CLUB` (`ID_CLUB`),
  ADD KEY `ID_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`ID_PAIS`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`ID_PROFESOR`),
  ADD UNIQUE KEY `ID_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `rel_libro_estanteria`
--
ALTER TABLE `rel_libro_estanteria`
  ADD PRIMARY KEY (`ID_REL_LIBRO_ESTANTERIA`),
  ADD KEY `ID_LIBRO` (`ID_LIBRO`),
  ADD KEY `ID_ESTANTERIA` (`ID_ESTANTERIA`);

--
-- Indices de la tabla `resena`
--
ALTER TABLE `resena`
  ADD PRIMARY KEY (`ID_RESENA`),
  ADD KEY `ID_LIBRO` (`ID_LIBRO`),
  ADD KEY `ID_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `titulacion`
--
ALTER TABLE `titulacion`
  ADD PRIMARY KEY (`ID_TITULACION`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_USUARIO`),
  ADD UNIQUE KEY `CORREO` (`CORREO`),
  ADD UNIQUE KEY `NOMBRE_USUARIO` (`NOMBRE_USUARIO`);

--
-- Indices de la tabla `usuario_sigue_estanteria`
--
ALTER TABLE `usuario_sigue_estanteria`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_ESTANTERIA` (`ID_ESTANTERIA`),
  ADD KEY `ID_USUARIO` (`ID_USUARIO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `ID_ALUMNO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `categoria_libro`
--
ALTER TABLE `categoria_libro`
  MODIFY `ID_CATEGORIA` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `club_lectura`
--
ALTER TABLE `club_lectura`
  MODIFY `ID_CLUB` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `comentario_club`
--
ALTER TABLE `comentario_club`
  MODIFY `ID_COMENTARIO_CLUB` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `estanteria`
--
ALTER TABLE `estanteria`
  MODIFY `ID_ESTANTERIA` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `ID_LIBRO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `libro_anadido`
--
ALTER TABLE `libro_anadido`
  MODIFY `ID_LIBRO_ANADIDO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `libro_propuesto`
--
ALTER TABLE `libro_propuesto`
  MODIFY `ID_LIBRO_PROPUESTO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `miembro_club`
--
ALTER TABLE `miembro_club`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `ID_PAIS` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `ID_PROFESOR` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_libro_estanteria`
--
ALTER TABLE `rel_libro_estanteria`
  MODIFY `ID_REL_LIBRO_ESTANTERIA` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `ID_RESENA` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `titulacion`
--
ALTER TABLE `titulacion`
  MODIFY `ID_TITULACION` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_USUARIO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `usuario_sigue_estanteria`
--
ALTER TABLE `usuario_sigue_estanteria`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `club_lectura`
--
ALTER TABLE `club_lectura`
  ADD CONSTRAINT `club_lectura_ibfk_1` FOREIGN KEY (`CREADO_POR`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `comentario_club`
--
ALTER TABLE `comentario_club`
  ADD CONSTRAINT `comentario_club_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`),
  ADD CONSTRAINT `comentario_club_ibfk_2` FOREIGN KEY (`ID_CLUB`) REFERENCES `club_lectura` (`ID_CLUB`);

--
-- Filtros para la tabla `estanteria`
--
ALTER TABLE `estanteria`
  ADD CONSTRAINT `estanteria_ibfk_1` FOREIGN KEY (`ID_ESTANTERIA`) REFERENCES `usuario_sigue_estanteria` (`ID_ESTANTERIA`),
  ADD CONSTRAINT `estanteria_ibfk_2` FOREIGN KEY (`CREADA_POR`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `libro_anadido`
--
ALTER TABLE `libro_anadido`
  ADD CONSTRAINT `libro_anadido_ibfk_1` FOREIGN KEY (`ID_LIBRO`) REFERENCES `libro` (`ID_LIBRO`);

--
-- Filtros para la tabla `libro_propuesto`
--
ALTER TABLE `libro_propuesto`
  ADD CONSTRAINT `libro_propuesto_ibfk_1` FOREIGN KEY (`ID_LIBRO`) REFERENCES `libro` (`ID_LIBRO`);

--
-- Filtros para la tabla `miembro_club`
--
ALTER TABLE `miembro_club`
  ADD CONSTRAINT `miembro_club_ibfk_1` FOREIGN KEY (`ID_CLUB`) REFERENCES `club_lectura` (`ID_CLUB`),
  ADD CONSTRAINT `miembro_club_ibfk_2` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `rel_libro_estanteria`
--
ALTER TABLE `rel_libro_estanteria`
  ADD CONSTRAINT `rel_libro_estanteria_ibfk_1` FOREIGN KEY (`ID_LIBRO`) REFERENCES `libro` (`ID_LIBRO`),
  ADD CONSTRAINT `rel_libro_estanteria_ibfk_2` FOREIGN KEY (`ID_ESTANTERIA`) REFERENCES `estanteria` (`ID_ESTANTERIA`);

--
-- Filtros para la tabla `resena`
--
ALTER TABLE `resena`
  ADD CONSTRAINT `resena_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`),
  ADD CONSTRAINT `resena_ibfk_2` FOREIGN KEY (`ID_LIBRO`) REFERENCES `libro` (`ID_LIBRO`);

--
-- Filtros para la tabla `usuario_sigue_estanteria`
--
ALTER TABLE `usuario_sigue_estanteria`
  ADD CONSTRAINT `usuario_sigue_estanteria_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
