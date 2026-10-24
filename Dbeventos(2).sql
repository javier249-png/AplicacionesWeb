-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         13.0.2-MariaDB - MariaDB Server
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.20.0.7320
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para bdeventos
CREATE DATABASE IF NOT EXISTS `bdeventos` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_uca1400_ai_ci */;
USE `bdeventos`;

-- Volcando estructura para tabla bdeventos.comuna
CREATE TABLE IF NOT EXISTS `comuna` (
  `id_comuna` int(11) NOT NULL,
  `nombre_comuna` varchar(45) NOT NULL,
  `id_region` int(11) NOT NULL,
  PRIMARY KEY (`id_comuna`,`id_region`),
  KEY `fk_comuna_region_idx` (`id_region`),
  CONSTRAINT `fk_comuna_region` FOREIGN KEY (`id_region`) REFERENCES `region` (`id_region`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.comuna: ~1 rows (aproximadamente)
INSERT INTO `comuna` (`id_comuna`, `nombre_comuna`, `id_region`) VALUES
	(1, 'Lota', 1);

-- Volcando estructura para tabla bdeventos.evento
CREATE TABLE IF NOT EXISTS `evento` (
  `id_evento` int(11) NOT NULL COMMENT 'clave única de valor numérico.',
  `cant_invitados` varchar(45) NOT NULL COMMENT 'Se especifica en valor numérico la cantidad de personas que asistirán al evento.',
  `estado` varchar(10) NOT NULL COMMENT 'se registra como activo o finalizado',
  `descripcion` varchar(128) NOT NULL,
  `fecha_evento` date NOT NULL,
  `hora` datetime NOT NULL,
  `id_tipo_evento` int(11) NOT NULL,
  `id_organizador` int(11) NOT NULL,
  `id_recinto` int(11) NOT NULL,
  `id_comuna` int(11) NOT NULL,
  `id_region` int(11) NOT NULL,
  PRIMARY KEY (`id_evento`,`id_tipo_evento`,`id_organizador`,`id_recinto`,`id_comuna`,`id_region`),
  KEY `fk_evento_tipo_evento1_idx` (`id_tipo_evento`),
  KEY `fk_evento_organizador1_idx` (`id_organizador`),
  KEY `fk_evento_recinto1_idx` (`id_recinto`,`id_comuna`,`id_region`),
  CONSTRAINT `fk_evento_organizador1` FOREIGN KEY (`id_organizador`) REFERENCES `organizador` (`id_organizador`),
  CONSTRAINT `fk_evento_recinto1` FOREIGN KEY (`id_recinto`, `id_comuna`, `id_region`) REFERENCES `recinto` (`id_recinto`, `id_comuna`, `id_region`),
  CONSTRAINT `fk_evento_tipo_evento1` FOREIGN KEY (`id_tipo_evento`) REFERENCES `tipo_evento` (`id_tipo_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.evento: ~6 rows (aproximadamente)
INSERT INTO `evento` (`id_evento`, `cant_invitados`, `estado`, `descripcion`, `fecha_evento`, `hora`, `id_tipo_evento`, `id_organizador`, `id_recinto`, `id_comuna`, `id_region`) VALUES
	(201, '80', 'activo', 'Conferencia de Inteligencia Artificial y Web', '2026-11-05', '2026-10-15 03:34:00', 1, 1, 1, 1, 1),
	(567, '67', 'activo', 'cft', '2026-10-07', '2026-10-04 16:02:00', 1, 1, 1, 1, 1),
	(628, '50', 'activo', 'Feria tecnológica CFTLA', '2026-11-20', '2026-11-20 10:00:00', 1, 1, 1, 1, 1),
	(669, '50', 'activo', 'Feria tecnológica CFTLA', '2026-11-20', '2026-11-20 10:00:00', 1, 1, 1, 1, 1),
	(764, '50', 'activo', 'Feria tecnológica CFTLA', '2026-11-20', '2026-11-20 10:00:00', 1, 1, 1, 1, 1),
	(778, '50', 'activo', 'owo', '2026-10-17', '2026-10-17 10:30:00', 1, 1, 1, 1, 1);

-- Volcando estructura para tabla bdeventos.invitado
CREATE TABLE IF NOT EXISTS `invitado` (
  `id_invitado` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `id_tipo_invitado` int(11) NOT NULL,
  PRIMARY KEY (`id_invitado`,`id_tipo_invitado`),
  KEY `fk_invitado_tipo_invitado1_idx` (`id_tipo_invitado`),
  CONSTRAINT `fk_invitado_tipo_invitado1` FOREIGN KEY (`id_tipo_invitado`) REFERENCES `tipo_invitado` (`id_tipo_invitado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.invitado: ~0 rows (aproximadamente)

-- Volcando estructura para tabla bdeventos.invitado_has_evento
CREATE TABLE IF NOT EXISTS `invitado_has_evento` (
  `id_invitado` int(11) NOT NULL,
  `id_tipo_invitado` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `id_tipo_evento` int(11) NOT NULL,
  PRIMARY KEY (`id_invitado`,`id_tipo_invitado`,`id_evento`,`id_tipo_evento`),
  KEY `fk_invitado_has_evento_evento1_idx` (`id_evento`,`id_tipo_evento`),
  KEY `fk_invitado_has_evento_invitado1_idx` (`id_invitado`,`id_tipo_invitado`),
  CONSTRAINT `fk_invitado_has_evento_evento1` FOREIGN KEY (`id_evento`, `id_tipo_evento`) REFERENCES `evento` (`id_evento`, `id_tipo_evento`),
  CONSTRAINT `fk_invitado_has_evento_invitado1` FOREIGN KEY (`id_invitado`, `id_tipo_invitado`) REFERENCES `invitado` (`id_invitado`, `id_tipo_invitado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.invitado_has_evento: ~0 rows (aproximadamente)

-- Volcando estructura para tabla bdeventos.organizador
CREATE TABLE IF NOT EXISTS `organizador` (
  `id_organizador` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `apellido` varchar(15) NOT NULL,
  `cargo` varchar(40) NOT NULL,
  PRIMARY KEY (`id_organizador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.organizador: ~1 rows (aproximadamente)
INSERT INTO `organizador` (`id_organizador`, `nombre`, `apellido`, `cargo`) VALUES
	(1, 'Patricia', 'Albornoz', 'Docente');

-- Volcando estructura para tabla bdeventos.recinto
CREATE TABLE IF NOT EXISTS `recinto` (
  `id_recinto` int(11) NOT NULL,
  `nombre_recinto` varchar(25) NOT NULL,
  `capacidad_personas` int(11) NOT NULL,
  `id_comuna` int(11) NOT NULL,
  `id_region` int(11) NOT NULL,
  PRIMARY KEY (`id_recinto`,`id_comuna`,`id_region`),
  KEY `fk_recinto_comuna1_idx` (`id_comuna`,`id_region`),
  CONSTRAINT `fk_recinto_comuna1` FOREIGN KEY (`id_comuna`, `id_region`) REFERENCES `comuna` (`id_comuna`, `id_region`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.recinto: ~1 rows (aproximadamente)
INSERT INTO `recinto` (`id_recinto`, `nombre_recinto`, `capacidad_personas`, `id_comuna`, `id_region`) VALUES
	(1, 'Auditorio CFTLA', 200, 1, 1);

-- Volcando estructura para tabla bdeventos.region
CREATE TABLE IF NOT EXISTS `region` (
  `id_region` int(11) NOT NULL,
  `nombre_region` varchar(20) NOT NULL,
  PRIMARY KEY (`id_region`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.region: ~1 rows (aproximadamente)
INSERT INTO `region` (`id_region`, `nombre_region`) VALUES
	(1, 'Biobío');

-- Volcando estructura para tabla bdeventos.tipo_evento
CREATE TABLE IF NOT EXISTS `tipo_evento` (
  `id_tipo_evento` int(11) NOT NULL,
  `nombre_tipo` varchar(45) NOT NULL,
  `descripcion_tipo` varchar(100) NOT NULL,
  PRIMARY KEY (`id_tipo_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.tipo_evento: ~1 rows (aproximadamente)
INSERT INTO `tipo_evento` (`id_tipo_evento`, `nombre_tipo`, `descripcion_tipo`) VALUES
	(1, 'Académico', 'Talleres y charlas');

-- Volcando estructura para tabla bdeventos.tipo_invitado
CREATE TABLE IF NOT EXISTS `tipo_invitado` (
  `id_tipo_invitado` int(11) NOT NULL,
  `nombre_tipo` varchar(45) NOT NULL,
  `descripcion_tipo` varchar(100) NOT NULL,
  PRIMARY KEY (`id_tipo_invitado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- Volcando datos para la tabla bdeventos.tipo_invitado: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
