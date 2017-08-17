﻿-- SQL BY RUSBE ARTEAGA
INSERT INTO `menus` VALUES ('2', 'Procesos', 'fa fa-cogs', '1', '2017-05-26 18:56:58', null, '1', null);
INSERT INTO `opciones` VALUES ('5', '2', 'Carga - Alumnos', 'proceso.carga.cargaralumno', 'fa fa-sitemap', '1', '2017-05-26 19:03:08', null, '1', null);
INSERT INTO `privilegios_opciones` VALUES ('5', '1', '5', '1', '2017-05-26 19:05:13', null, '1', null);

CREATE TABLE `alumnos_historico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alumno_id` int(11) NOT NULL,
  `campo_1` varchar(255) DEFAULT NULL,
  `campo_2` varchar(255) DEFAULT NULL,
  `campo_3` varchar(255) DEFAULT NULL,
  `campo_4` varchar(255) DEFAULT NULL,
  `campo_5` varchar(255) DEFAULT NULL,
  `campo_6` varchar(255) DEFAULT NULL,
  `campo_7` varchar(255) DEFAULT NULL,
  `campo_8` varchar(255) DEFAULT NULL,
  `campo_9` varchar(255) DEFAULT NULL,
  `campo_10` varchar(255) DEFAULT NULL,
  `campo_11` varchar(255) DEFAULT NULL,
  `campo_12` varchar(255) DEFAULT NULL,
  `campo_13` varchar(255) DEFAULT NULL,
  `campo_14` varchar(255) DEFAULT NULL,
  `campo_15` varchar(255) DEFAULT NULL,
  `campo_16` varchar(255) DEFAULT NULL,
  `campo_17` varchar(255) DEFAULT NULL,
  `campo_18` varchar(255) DEFAULT NULL,
  `campo_19` varchar(255) DEFAULT NULL,
  `campo_20` varchar(255) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `persona_id_created_at` int(11) NOT NULL,
  `persona_id_updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


ALTER TABLE alumnos add tipo_reg char(2) NOT NULL AFTER distrito;

CREATE TABLE `clientes_accesos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `key` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `persona_id_created_at` int(11) NOT NULL,
  `persona_id_updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `certificaciones`.`clientes_accesos` (`id`, `nombre`, `descripcion`, `key`, `url`, `ip`, `estado`, `created_at`, `updated_at`, `persona_id_created_at`, `persona_id_updated_at`) 
VALUES ('1', 'Software 1', 'Cliente de prueba rest full', '173449ebbda62b67a0d9bc645e6dbba7', NULL, NULL, '1', '2017-08-15 15:21:35', NULL, '1', NULL);

-- 