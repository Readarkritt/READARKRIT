-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2017 a las 13:15:51
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
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `ID_ALUMNO` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `NUM_EXPEDIENTE` mediumint(8) UNSIGNED NOT NULL,
  `ID_TITULACION` tinyint(3) UNSIGNED NOT NULL,
  `CURSO` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`ID_ALUMNO`, `ID_USUARIO`, `NUM_EXPEDIENTE`, `ID_TITULACION`, `CURSO`) VALUES
(2, 17, 798, 1, 1),
(9, 20, 9798, 4, 1),
(10, 21, 98, 1, 4),
(11, 22, 298, 2, 1),
(12, 23, 293, 1, 1),
(13, 24, 3, 2, 1),
(14, 63, 7879, 1, 2),
(15, 64, 78, 1, 3),
(16, 65, 458, 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_libro`
--

CREATE TABLE `categoria_libro` (
  `ID_CATEGORIA` int(11) NOT NULL,
  `NOMBRE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categoria_libro`
--

INSERT INTO `categoria_libro` (`ID_CATEGORIA`, `NOMBRE`) VALUES
(1, 'Teoría'),
(2, 'Historia'),
(3, 'Crítica'),
(4, 'Monografías (arquitectos, obras, tipos, uso)'),
(5, 'Ciudad, Urbanismo, Territorio y Paisaje'),
(6, 'Metodología. Epistemología (lógica interna)'),
(7, 'Técnica'),
(8, 'Disciplinas afines');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `club_lectura`
--

CREATE TABLE `club_lectura` (
  `ID_CLUB` int(10) UNSIGNED NOT NULL,
  `CREADO_POR` int(10) UNSIGNED NOT NULL,
  `NOMBRE` varchar(20) NOT NULL,
  `F_INICIO` date NOT NULL,
  `F_FIN` date DEFAULT NULL,
  `ID_TITULACION` int(10) UNSIGNED NOT NULL,
  `CURSO` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `club_lectura`
--

INSERT INTO `club_lectura` (`ID_CLUB`, `CREADO_POR`, `NOMBRE`, `F_INICIO`, `F_FIN`, `ID_TITULACION`, `CURSO`) VALUES
(32, 64, 'o', '2017-11-03', NULL, 0, 0),
(33, 64, 'aq', '2017-11-03', NULL, 1, 1),
(34, 64, 'aqy', '2017-11-03', NULL, 1, 1),
(35, 64, 'profesores', '2017-11-03', '2017-11-07', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario_club`
--

CREATE TABLE `comentario_club` (
  `ID_COMENTARIO_CLUB` int(10) UNSIGNED NOT NULL,
  `ID_CLUB` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `FECHA` date NOT NULL,
  `COMENTARIO` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `comentario_club`
--

INSERT INTO `comentario_club` (`ID_COMENTARIO_CLUB`, `ID_CLUB`, `ID_USUARIO`, `FECHA`, `COMENTARIO`) VALUES
(1, 32, 64, '2017-11-06', 'jajajja'),
(12, 32, 64, '2017-11-06', 'Hola a toooo'),
(13, 32, 63, '2017-11-06', 'Hola JK'),
(14, 32, 64, '2017-11-06', 'ereer'),
(15, 32, 64, '2017-11-06', 'ereer'),
(16, 32, 64, '2017-11-06', 'ssiiii'),
(17, 32, 63, '2017-11-06', 'hablemos'),
(18, 32, 64, '2017-11-06', 'de que'),
(19, 32, 64, '2017-11-06', 'no se'),
(20, 32, 64, '2017-11-06', 'por probar el chat'),
(21, 32, 64, '2017-11-06', 'se me acaban los recusos'),
(22, 32, 64, '2017-11-07', 'Vamos a probar con los mensajes largos jajajjajajjaja'),
(23, 32, 64, '2017-11-07', 'otro mas'),
(24, 32, 64, '2017-11-07', 'Otro massss'),
(25, 32, 64, '2017-11-07', 'Prueba intro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_votacion`
--

CREATE TABLE `control_votacion` (
  `ID_CONTROL_VOTACION` int(10) UNSIGNED NOT NULL,
  `ID_LIBRO_PROPUESTO` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estanteria`
--

CREATE TABLE `estanteria` (
  `ID_ESTANTERIA` int(10) UNSIGNED NOT NULL,
  `NOMBRE` varchar(20) NOT NULL,
  `CREADA_POR` int(10) UNSIGNED NOT NULL COMMENT 'el id_usuario que ha creado la estantería'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estanteria`
--

INSERT INTO `estanteria` (`ID_ESTANTERIA`, `NOMBRE`, `CREADA_POR`) VALUES
(6, 'Libros Leídoos', 64),
(7, 'Librosjjj', 64),
(8, 'Libros Leídos', 65),
(9, 'Libros Leídoos', 64),
(10, 'Libros L', 64),
(11, 'Li', 64),
(12, 'Libros Leídoos', 64),
(13, 'Libros Leídoos', 64);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invitacion`
--

CREATE TABLE `invitacion` (
  `ID_INVITACION` int(11) NOT NULL,
  `CORREO` varchar(100) NOT NULL,
  `CONTRASENA` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `invitacion`
--

INSERT INTO `invitacion` (`ID_INVITACION`, `CORREO`, `CONTRASENA`) VALUES
(1, 'cookie@eres.es', '$2y$12$bLbD8AybI8URP5O/sIITRulVrK0H/Ku3ddxq9fEcV9KjaVCxRnFgu'),
(2, 'cookie@eres.es', '$2y$12$DQ6ZvQeJ4wQaYXjGJRjWueDG9iRN1TNfjXWOxz5/3acUnXJrwNucy');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `PORTADA` varchar(255) NOT NULL,
  `TITULO` varchar(100) NOT NULL,
  `TITULO_ORIGINAL` varchar(100) NOT NULL,
  `AUTOR` varchar(50) NOT NULL,
  `ANO` smallint(4) UNSIGNED NOT NULL,
  `ANADIDO_POR` int(10) UNSIGNED NOT NULL COMMENT 'id_usuario del que lo subió, si es un profesor se pone un 0 (ARKRIT)',
  `ID_TITULACION` int(10) UNSIGNED NOT NULL,
  `F_BAJA` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`ID_LIBRO`, `PORTADA`, `TITULO`, `TITULO_ORIGINAL`, `AUTOR`, `ANO`, `ANADIDO_POR`, `ID_TITULACION`, `F_BAJA`) VALUES
(4, '1507545779547.jpg', 'Espacio, tiempo y arquitectura', 'Space, Time & Architecture: the growth of a new tr', 'Giedion, Sigfried', 255, 0, 1, NULL),
(5, '1507545779751.jpg', 'Complejidad y contradicción en la arquitectura', 'Complexity and contradiction in architecture', 'Venturi, Robert', 255, 0, 2, NULL),
(6, '1507545779882.jpg', 'Historia crítica de la arquitectura moderna', 'Modern Architecture: A Critical History', 'Frampton, Kenneth', 255, 0, 3, NULL),
(7, '1507545817497.jpg', 'Espacio, tiempo y arquitectura', 'Space, Time & Architecture: the growth of a new tr', 'Giedion, Sigfried', 255, 0, 1, NULL),
(8, '1507545817642.jpg', 'Complejidad y contradicción en la arquitectura', 'Complexity and contradiction in architecture', 'Venturi, Robert', 255, 0, 2, '2017-10-09'),
(9, '1507545817787.jpg', 'Historia crítica de la arquitectura moderna', 'Modern Architecture: A Critical History', 'Frampton, Kenneth', 255, 0, 3, '2017-10-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_anadido`
--

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
-- Volcado de datos para la tabla `libro_anadido`
--

INSERT INTO `libro_anadido` (`ID_LIBRO_ANADIDO`, `ID_LIBRO`, `ID_PAIS`, `ID_CATEGORIA`, `POSICION_RANKING`, `MEDIA_NUM_USUARIOS`, `NIVEL_ESPECIALIZACION`) VALUES
(1, 4, 208, 3, 1, 0, 'Básico'),
(2, 5, 75, 3, 2, 0, 'Básico'),
(3, 6, 180, 2, 3, 0, 'Básico'),
(4, 7, 208, 3, 4, 0, 'Básico'),
(5, 8, 75, 3, 5, 0, 'Básico'),
(6, 9, 180, 2, 6, 0, 'Básico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_propuesto`
--

CREATE TABLE `libro_propuesto` (
  `ID_LIBRO_PROPUESTO` int(10) UNSIGNED NOT NULL,
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `PROPUESTO_PARA` varchar(10) NOT NULL COMMENT 'propuesto para añadir o quitar de la biblioteca ARKRIT',
  `MOTIVO` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembro_club`
--

CREATE TABLE `miembro_club` (
  `ID` int(10) UNSIGNED NOT NULL,
  `ID_CLUB` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `miembro_club`
--

INSERT INTO `miembro_club` (`ID`, `ID_CLUB`, `ID_USUARIO`) VALUES
(31, 32, 63),
(32, 32, 64),
(33, 32, 47),
(34, 32, 45),
(35, 33, 17),
(36, 33, 23),
(37, 34, 17),
(38, 34, 23),
(39, 34, 63),
(40, 34, 65),
(41, 34, 62),
(42, 35, 47),
(43, 35, 62);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `ID_PAIS` int(10) UNSIGNED NOT NULL,
  `ISO` char(2) NOT NULL,
  `NOMBRE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`ID_PAIS`, `ISO`, `NOMBRE`) VALUES
(1, 'AF', 'Afganistán'),
(2, 'AX', 'Islas Gland'),
(3, 'AL', 'Albania'),
(4, 'DE', 'Alemania'),
(5, 'AD', 'Andorra'),
(6, 'AO', 'Angola'),
(7, 'AI', 'Anguilla'),
(8, 'AQ', 'Antártida'),
(9, 'AG', 'Antigua y Barbuda'),
(10, 'AN', 'Antillas Holandesas'),
(11, 'SA', 'Arabia Saudí'),
(12, 'DZ', 'Argelia'),
(13, 'AR', 'Argentina'),
(14, 'AM', 'Armenia'),
(15, 'AW', 'Aruba'),
(16, 'AU', 'Australia'),
(17, 'AT', 'Austria'),
(18, 'AZ', 'Azerbaiyán'),
(19, 'BS', 'Bahamas'),
(20, 'BH', 'Bahréin'),
(21, 'BD', 'Bangladesh'),
(22, 'BB', 'Barbados'),
(23, 'BY', 'Bielorrusia'),
(24, 'BE', 'Bélgica'),
(25, 'BZ', 'Belice'),
(26, 'BJ', 'Benin'),
(27, 'BM', 'Bermudas'),
(28, 'BT', 'Bhután'),
(29, 'BO', 'Bolivia'),
(30, 'BA', 'Bosnia y Herzegovina'),
(31, 'BW', 'Botsuana'),
(32, 'BV', 'Isla Bouvet'),
(33, 'BR', 'Brasil'),
(34, 'BN', 'Brunéi'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'CV', 'Cabo Verde'),
(39, 'KY', 'Islas Caimán'),
(40, 'KH', 'Camboya'),
(41, 'CM', 'Camerún'),
(42, 'CA', 'Canadá'),
(43, 'CF', 'República Centroafricana'),
(44, 'TD', 'Chad'),
(45, 'CZ', 'República Checa'),
(46, 'CL', 'Chile'),
(47, 'CN', 'China'),
(48, 'CY', 'Chipre'),
(49, 'CX', 'Isla de Navidad'),
(50, 'VA', 'Ciudad del Vaticano'),
(51, 'CC', 'Islas Cocos'),
(52, 'CO', 'Colombia'),
(53, 'KM', 'Comoras'),
(54, 'CD', 'República Democrática del Congo'),
(55, 'CG', 'Congo'),
(56, 'CK', 'Islas Cook'),
(57, 'KP', 'Corea del Norte'),
(58, 'KR', 'Corea del Sur'),
(59, 'CI', 'Costa de Marfil'),
(60, 'CR', 'Costa Rica'),
(61, 'HR', 'Croacia'),
(62, 'CU', 'Cuba'),
(63, 'DK', 'Dinamarca'),
(64, 'DM', 'Dominica'),
(65, 'DO', 'República Dominicana'),
(66, 'EC', 'Ecuador'),
(67, 'EG', 'Egipto'),
(68, 'SV', 'El Salvador'),
(69, 'AE', 'Emiratos Árabes Unidos'),
(70, 'ER', 'Eritrea'),
(71, 'SK', 'Eslovaquia'),
(72, 'SI', 'Eslovenia'),
(73, 'ES', 'España'),
(74, 'UM', 'Islas ultramarinas de Estados Unidos'),
(75, 'US', 'Estados Unidos'),
(76, 'EE', 'Estonia'),
(77, 'ET', 'Etiopía'),
(78, 'FO', 'Islas Feroe'),
(79, 'PH', 'Filipinas'),
(80, 'FI', 'Finlandia'),
(81, 'FJ', 'Fiyi'),
(82, 'FR', 'Francia'),
(83, 'GA', 'Gabón'),
(84, 'GM', 'Gambia'),
(85, 'GE', 'Georgia'),
(86, 'GS', 'Islas Georgias del Sur y Sandwich del Sur'),
(87, 'GH', 'Ghana'),
(88, 'GI', 'Gibraltar'),
(89, 'GD', 'Granada'),
(90, 'GR', 'Grecia'),
(91, 'GL', 'Groenlandia'),
(92, 'GP', 'Guadalupe'),
(93, 'GU', 'Guam'),
(94, 'GT', 'Guatemala'),
(95, 'GF', 'Guayana Francesa'),
(96, 'GN', 'Guinea'),
(97, 'GQ', 'Guinea Ecuatorial'),
(98, 'GW', 'Guinea-Bissau'),
(99, 'GY', 'Guyana'),
(100, 'HT', 'Haití'),
(101, 'HM', 'Islas Heard y McDonald'),
(102, 'HN', 'Honduras'),
(103, 'HK', 'Hong Kong'),
(104, 'HU', 'Hungría'),
(105, 'IN', 'India'),
(106, 'ID', 'Indonesia'),
(107, 'IR', 'Irán'),
(108, 'IQ', 'Iraq'),
(109, 'IE', 'Irlanda'),
(110, 'IS', 'Islandia'),
(111, 'IL', 'Israel'),
(112, 'IT', 'Italia'),
(113, 'JM', 'Jamaica'),
(114, 'JP', 'Japón'),
(115, 'JO', 'Jordania'),
(116, 'KZ', 'Kazajstán'),
(117, 'KE', 'Kenia'),
(118, 'KG', 'Kirguistán'),
(119, 'KI', 'Kiribati'),
(120, 'KW', 'Kuwait'),
(121, 'LA', 'Laos'),
(122, 'LS', 'Lesotho'),
(123, 'LV', 'Letonia'),
(124, 'LB', 'Líbano'),
(125, 'LR', 'Liberia'),
(126, 'LY', 'Libia'),
(127, 'LI', 'Liechtenstein'),
(128, 'LT', 'Lituania'),
(129, 'LU', 'Luxemburgo'),
(130, 'MO', 'Macao'),
(131, 'MK', 'ARY Macedonia'),
(132, 'MG', 'Madagascar'),
(133, 'MY', 'Malasia'),
(134, 'MW', 'Malawi'),
(135, 'MV', 'Maldivas'),
(136, 'ML', 'Malí'),
(137, 'MT', 'Malta'),
(138, 'FK', 'Islas Malvinas'),
(139, 'MP', 'Islas Marianas del Norte'),
(140, 'MA', 'Marruecos'),
(141, 'MH', 'Islas Marshall'),
(142, 'MQ', 'Martinica'),
(143, 'MU', 'Mauricio'),
(144, 'MR', 'Mauritania'),
(145, 'YT', 'Mayotte'),
(146, 'MX', 'México'),
(147, 'FM', 'Micronesia'),
(148, 'MD', 'Moldavia'),
(149, 'MC', 'Mónaco'),
(150, 'MN', 'Mongolia'),
(151, 'MS', 'Montserrat'),
(152, 'MZ', 'Mozambique'),
(153, 'MM', 'Myanmar'),
(154, 'NA', 'Namibia'),
(155, 'NR', 'Nauru'),
(156, 'NP', 'Nepal'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Níger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Isla Norfolk'),
(162, 'NO', 'Noruega'),
(163, 'NC', 'Nueva Caledonia'),
(164, 'NZ', 'Nueva Zelanda'),
(165, 'OM', 'Omán'),
(166, 'NL', 'Países Bajos'),
(167, 'PK', 'Pakistán'),
(168, 'PW', 'Palau'),
(169, 'PS', 'Palestina'),
(170, 'PA', 'Panamá'),
(171, 'PG', 'Papúa Nueva Guinea'),
(172, 'PY', 'Paraguay'),
(173, 'PE', 'Perú'),
(174, 'PN', 'Islas Pitcairn'),
(175, 'PF', 'Polinesia Francesa'),
(176, 'PL', 'Polonia'),
(177, 'PT', 'Portugal'),
(178, 'PR', 'Puerto Rico'),
(179, 'QA', 'Qatar'),
(180, 'GB', 'Reino Unido'),
(181, 'RE', 'Reunión'),
(182, 'RW', 'Ruanda'),
(183, 'RO', 'Rumania'),
(184, 'RU', 'Rusia'),
(185, 'EH', 'Sahara Occidental'),
(186, 'SB', 'Islas Salomón'),
(187, 'WS', 'Samoa'),
(188, 'AS', 'Samoa Americana'),
(189, 'KN', 'San Cristóbal y Nevis'),
(190, 'SM', 'San Marino'),
(191, 'PM', 'San Pedro y Miquelón'),
(192, 'VC', 'San Vicente y las Granadinas'),
(193, 'SH', 'Santa Helena'),
(194, 'LC', 'Santa Lucía'),
(195, 'ST', 'Santo Tomé y Príncipe'),
(196, 'SN', 'Senegal'),
(197, 'CS', 'Serbia y Montenegro'),
(198, 'SC', 'Seychelles'),
(199, 'SL', 'Sierra Leona'),
(200, 'SG', 'Singapur'),
(201, 'SY', 'Siria'),
(202, 'SO', 'Somalia'),
(203, 'LK', 'Sri Lanka'),
(204, 'SZ', 'Suazilandia'),
(205, 'ZA', 'Sudáfrica'),
(206, 'SD', 'Sudán'),
(207, 'SE', 'Suecia'),
(208, 'CH', 'Suiza'),
(209, 'SR', 'Surinam'),
(210, 'SJ', 'Svalbard y Jan Mayen'),
(211, 'TH', 'Tailandia'),
(212, 'TW', 'Taiwán'),
(213, 'TZ', 'Tanzania'),
(214, 'TJ', 'Tayikistán'),
(215, 'IO', 'Territorio Británico del Océano Índico'),
(216, 'TF', 'Territorios Australes Franceses'),
(217, 'TL', 'Timor Oriental'),
(218, 'TG', 'Togo'),
(219, 'TK', 'Tokelau'),
(220, 'TO', 'Tonga'),
(221, 'TT', 'Trinidad y Tobago'),
(222, 'TN', 'Túnez'),
(223, 'TC', 'Islas Turcas y Caicos'),
(224, 'TM', 'Turkmenistán'),
(225, 'TR', 'Turquía'),
(226, 'TV', 'Tuvalu'),
(227, 'UA', 'Ucrania'),
(228, 'UG', 'Uganda'),
(229, 'UY', 'Uruguay'),
(230, 'UZ', 'Uzbekistán'),
(231, 'VU', 'Vanuatu'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Islas Vírgenes Británicas'),
(235, 'VI', 'Islas Vírgenes de los Estados Unidos'),
(236, 'WF', 'Wallis y Futuna'),
(237, 'YE', 'Yemen'),
(238, 'DJ', 'Yibuti'),
(239, 'ZM', 'Zambia'),
(240, 'ZW', 'Zimbabue');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `ID_PERMISO` int(11) NOT NULL,
  `RUTA_FICHERO` varchar(500) NOT NULL,
  `ROL` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `ID_PROFESOR` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `ES_ADMIN` tinyint(1) NOT NULL,
  `EVITAR_NOTIFICACION` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`ID_PROFESOR`, `ID_USUARIO`, `ES_ADMIN`, `EVITAR_NOTIFICACION`) VALUES
(21, 45, 1, 1),
(22, 46, 0, 1),
(25, 47, 0, 1),
(31, 61, 0, 1),
(32, 62, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_libro_estanteria`
--

CREATE TABLE `rel_libro_estanteria` (
  `ID_REL_LIBRO_ESTANTERIA` int(10) UNSIGNED NOT NULL,
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `ID_ESTANTERIA` int(10) UNSIGNED NOT NULL,
  `LIBRO_LEIDO` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rel_libro_estanteria`
--

INSERT INTO `rel_libro_estanteria` (`ID_REL_LIBRO_ESTANTERIA`, `ID_LIBRO`, `ID_ESTANTERIA`, `LIBRO_LEIDO`) VALUES
(53, 4, 6, 1),
(54, 5, 6, 1),
(55, 4, 7, 0),
(56, 5, 7, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

CREATE TABLE `resena` (
  `ID_RESENA` int(10) UNSIGNED NOT NULL,
  `NOTA` tinyint(3) UNSIGNED NOT NULL,
  `COMENTARIO` text NOT NULL,
  `ID_LIBRO` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `FECHA_ALTA` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulacion`
--

CREATE TABLE `titulacion` (
  `ID_TITULACION` int(11) NOT NULL,
  `NOMBRE` varchar(30) NOT NULL,
  `DURACION` tinyint(3) UNSIGNED NOT NULL COMMENT '(en años)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `titulacion`
--

INSERT INTO `titulacion` (`ID_TITULACION`, `NOMBRE`, `DURACION`) VALUES
(1, 'Grado', 5),
(2, 'Máster Habilitante', 1),
(3, 'Máster de Especialidad', 1),
(4, 'Doctorado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_USUARIO` int(10) UNSIGNED NOT NULL,
  `NOMBRE` varchar(40) NOT NULL,
  `PRIMER_APELLIDO` varchar(30) NOT NULL,
  `SEGUNDO_APELLIDO` varchar(30) NOT NULL,
  `F_NACIMIENTO` date NOT NULL,
  `CORREO` varchar(100) NOT NULL,
  `NOMBRE_USUARIO` varchar(20) NOT NULL,
  `CONTRASENA` char(255) NOT NULL,
  `BLOQUEADO` tinyint(1) NOT NULL,
  `F_BAJA` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_USUARIO`, `NOMBRE`, `PRIMER_APELLIDO`, `SEGUNDO_APELLIDO`, `F_NACIMIENTO`, `CORREO`, `NOMBRE_USUARIO`, `CONTRASENA`, `BLOQUEADO`, `F_BAJA`) VALUES
(13, 'CAROL', 'CAROL', 'CAROL', '0000-00-00', 'carol@ar.com', 'Carolie', '$2y$12$2E9PnQiQ0qTQhhy5Z1UixOnZvtxwI8JhfITRzahoWfzNJjSX275r2', 0, NULL),
(14, 'CAROL', 'CAROL', 'CAROL', '0000-00-00', 'carol@a.com', 'Car', '$2y$12$YX4UB1Kh.MEkOTK/iz7YaeRNgc2aLKgRhBGbK7noa6p2IRc2EhDd2', 0, NULL),
(15, 'CAROL', 'CAROL', 'CAROL', '0000-00-00', 'carol@ap.com', 'Caroo', '$2y$12$/vG.cdVZPjrJZxBXDCZuD.XQsDipd.gh1XHTVS1cmnoxwTYbZTLza', 0, NULL),
(16, 'CAROL', 'CAROL', 'CAROL', '0000-00-00', 'carol@api.com', 'Carool', '$2y$12$GlNpzOzn.Y751q.QAoMMl.8IRcDU1QvaRaw8xPJin5KRwe.i3vxR.', 0, NULL),
(17, 'Pepe', 'Pepe', 'Pepe', '1991-12-12', 'carol@aGr.com', 'Caroli', '$2y$12$P5SpH859eKPWJWUFC001AeAuZ/bAxx/G4TpQ3ITNDRq6SgIIM58Ou', 0, NULL),
(18, 'Pepe', 'Pepe', 'Pepe', '0000-00-00', 'carol@saGur.com', 'Carolinqo', '$2y$12$sz9O728G5611YNqkebxbLu61H6EueUjWoixURdva0YUS1F4Am.rdO', 0, NULL),
(19, 'Pepe', 'Pepe', 'Pepe', '0000-00-00', 'carol@shaGur.com', 'Carolinqor', '$2y$12$Q0JX9I2hc16/xmW/TUtpleg9ecFxSVlfBzP1feRSw/XjuyHRRWWIK', 127, NULL),
(20, 'Pepe', 'Pepe', 'Pepe', '1991-12-12', 'carol@shauGIur.com', 'CarolinqorIy', '$2y$12$KiWr16Ksh4gtoCfzs0ZcD.OQfxfvv5DrlC7yorW1slhd80KO.4dI6', 0, NULL),
(21, 'Pepe', 'Pepe', 'Pepe', '1991-12-12', 'carol@shauGiIur.com', 'CarolinqiorIy', '$2y$12$3B7CoQd99ngVIHLBprboJeV/DtLPro2IYrdYFXAxRf99ZWVcOKKF.', 0, NULL),
(22, 'Pepe', 'Pepe', 'Pepe', '1991-12-12', 'carol@shaucGiIur.com', 'CarolicnqiorIy', '$2y$12$/8KiOXYqOrJkX6yPv3K0oux62tBanqCXUP8xXRATziyFIk4Kdwr86', 0, NULL),
(23, 'Pepe', 'Pepe', 'Pepe', '1991-12-12', 'carol@shauceGiIur.com', 'CarolicnqiorIyw', '$2y$12$GV8OP7wlE6mvhqbRiktHzu438GrY8tSGeeJd6bZ907lY0nD7zYh/W', 0, NULL),
(24, 'Pepe', 'Pepe', 'Pepe', '1991-12-12', 'carol@shaudceGiIur.com', 'CarolicnqiorIryw', '$2y$12$wMeMLKw8abj6TlbPdQMgIOOQg7gz5Ik4OxbAMii362ci9pWFi.hE2', 1, NULL),
(27, 'a', 'b', 'c', '1995-08-07', 'ca@ca.es', 'dasd', '$2y$12$dXs83IOrDGDYZ85PRvgL3O221IsiRXfMJ3zM5lPS7yOH/aCNZZ8/y', 0, NULL),
(29, 'q', 'w', 'e', '1992-08-08', 'zxc@zxc.es', 'zxc', '$2y$12$00aRXAAe.WTj4Iw6ej8zTeC65kVEiox/AQoT3yQexaCNJxQsSwdYm', 0, NULL),
(45, 'asdasd', 'sdfsdlfjsd', 'sdlfjsldjf', '1212-12-12', 'carolitn@es.com', 'asdasd', '$2y$12$rV2NloCLQRRwXVaG5/viAueWHqv6NWdjxw.FgZ6q3l8s08VCg6Tla', 0, NULL),
(46, 'prueba', 'prueba', 'prueba', '1212-12-12', 'carol@shaudce.com', 'Cas', '$2y$12$nbDDzPfMX.9hC4yBy0jgJ.YI6ROwepTZUtaB4RjWaMVzRza1s4.7u', 0, '2017-09-29'),
(47, 'aaaa', 'ssssss', 'zzzzz', '1212-12-12', 'carol@carolo.com', 'casdq', '$2y$12$o66LcHR.hnvfe3JUUIVMEus2FPqB9UWQjLIS5QL.clai/qLLx1bOe', 0, NULL),
(48, 'aaaa', 'ssssss', 'zzzzz', '1212-12-12', 'carol@cearolo.com', 'caesdq', '2y12MmZkopC8eY.nn1Z9xetLy.JVsmTrgaI5KFF.LGZWvvMaJEY1p84uC', 0, NULL),
(53, 'aaaa', 'ssssss', 'zzzzz', '1212-12-12', 'carol@ceparolo.com', 'capesdq', '2y12MmZkopC8eY.nn1Z9xetLy.JVsmTrgaI5KFF.LGZWvvMaJEY1p84uC', 0, NULL),
(55, 'aaaa', 'ssssss', 'zzzzz', '1212-12-12', 'carol@ceaYUrolo.com', 'caeUYsdq', '2y12MmZkopC8eY.nn1Z9xetLy.JVsmTrgaI5KFF.LGZWvvMaJEY1p84uC', 0, NULL),
(60, 'aaaa', 'ssssss', 'zzzzz', '1212-12-12', 'caroql@cearolo.com', 'caewesdq', '2y12MmZkopC8eYnn1Z9xetLy.JVsmTrgaI5KFF.LGZWvvMaJEY1p84uC', 0, NULL),
(61, 'aaaa', 'ssssss', 'zzzzz', '1212-12-12', 'carol@ceassrolo.com', 'caesssdq', '$2y$12$6XkXe3hOvJj3QWjjOLNWZuEO//UXbK.0CWVJXPR4jaG3aZStS3yNe', 0, '2017-09-29'),
(62, 'pepito', 'p', 'ppp', '1212-12-12', 'pepito@p.es', 'pepito', '$2y$12$wim/4uubKtlr4SP4U8tplOWjPEfhLdYbw.h9pjNRLZ8uK.GEJe11e', 0, NULL),
(63, 'Cesar', 'Augustus', 'Primero', '1212-12-12', 'cesar@cesar.es', 'Cesitar', '$2y$12$cgadz5uOee.o0BrfdPTQae6baKACg5b3HGO0SlRVw3TbfIRGB/1ti', 0, NULL),
(64, 'María Teresa', 'Poveda', 'Jimenez', '1956-07-07', 'm@m.es', 'Ma', '$2y$12$5vViJ1tvGzEZ0d67wkQKFuAlKqF9248P7xe5wUIw/elNVmIZG9Tci', 0, NULL),
(65, 'qweqwe', 'eqweqwe', 'eqweqwe', '1854-07-07', 'aas@ea.es', 'PO', '$2y$12$gECIs9SpGW/Eoahkz4ijo.3YadGf4YNazwzhBDdjHwu2hjiUSzfa.', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_sigue_estanteria`
--

CREATE TABLE `usuario_sigue_estanteria` (
  `ID` int(10) UNSIGNED NOT NULL,
  `ID_ESTANTERIA` int(10) UNSIGNED NOT NULL,
  `ID_USUARIO` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  ADD KEY `ID_USUARIO` (`ID_USUARIO`),
  ADD KEY `ID_CLUB` (`ID_CLUB`) USING BTREE;

--
-- Indices de la tabla `control_votacion`
--
ALTER TABLE `control_votacion`
  ADD PRIMARY KEY (`ID_CONTROL_VOTACION`),
  ADD KEY `ID_LIBRO_PROPUESTO` (`ID_LIBRO_PROPUESTO`),
  ADD KEY `ID_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `estanteria`
--
ALTER TABLE `estanteria`
  ADD PRIMARY KEY (`ID_ESTANTERIA`),
  ADD KEY `CREADA_POR` (`CREADA_POR`);

--
-- Indices de la tabla `invitacion`
--
ALTER TABLE `invitacion`
  ADD PRIMARY KEY (`ID_INVITACION`);

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
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`ID_PERMISO`);

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
  MODIFY `ID_ALUMNO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `categoria_libro`
--
ALTER TABLE `categoria_libro`
  MODIFY `ID_CATEGORIA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `club_lectura`
--
ALTER TABLE `club_lectura`
  MODIFY `ID_CLUB` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT de la tabla `comentario_club`
--
ALTER TABLE `comentario_club`
  MODIFY `ID_COMENTARIO_CLUB` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `control_votacion`
--
ALTER TABLE `control_votacion`
  MODIFY `ID_CONTROL_VOTACION` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `estanteria`
--
ALTER TABLE `estanteria`
  MODIFY `ID_ESTANTERIA` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `invitacion`
--
ALTER TABLE `invitacion`
  MODIFY `ID_INVITACION` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `ID_LIBRO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `libro_anadido`
--
ALTER TABLE `libro_anadido`
  MODIFY `ID_LIBRO_ANADIDO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `libro_propuesto`
--
ALTER TABLE `libro_propuesto`
  MODIFY `ID_LIBRO_PROPUESTO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `miembro_club`
--
ALTER TABLE `miembro_club`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `ID_PAIS` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;
--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `ID_PERMISO` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `ID_PROFESOR` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de la tabla `rel_libro_estanteria`
--
ALTER TABLE `rel_libro_estanteria`
  MODIFY `ID_REL_LIBRO_ESTANTERIA` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `ID_RESENA` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `titulacion`
--
ALTER TABLE `titulacion`
  MODIFY `ID_TITULACION` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_USUARIO` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
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
-- Filtros para la tabla `control_votacion`
--
ALTER TABLE `control_votacion`
  ADD CONSTRAINT `control_votacion_ibfk_1` FOREIGN KEY (`ID_LIBRO_PROPUESTO`) REFERENCES `libro_propuesto` (`ID_LIBRO_PROPUESTO`),
  ADD CONSTRAINT `control_votacion_ibfk_2` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `estanteria`
--
ALTER TABLE `estanteria`
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
  ADD CONSTRAINT `usuario_sigue_estanteria_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`),
  ADD CONSTRAINT `usuario_sigue_estanteria_ibfk_2` FOREIGN KEY (`ID_ESTANTERIA`) REFERENCES `estanteria` (`ID_ESTANTERIA`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
