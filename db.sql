-- Adminer 4.8.4 MySQL 5.7.40 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `acessos`;
CREATE TABLE `acessos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mac` varchar(17) DEFAULT NULL,
  `dia_hora` datetime DEFAULT NULL,
  `gateway_node` varchar(15) DEFAULT NULL,
  `localizacao` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `anunciantes`;
CREATE TABLE `anunciantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `saldo` int(11) NOT NULL,
  `cep` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `anunciantes` (`id`, `nome`, `email`, `celular`, `saldo`, `cep`) VALUES
(1,	'Pizzas Papa Bem',	'teste@anunciante.com',	'21987654321',	500,	'24130400'),
(2,	'Mercado Amigão da Área ',	'teste@anunciante.com',	'21987654321',	500,	'24130400'),
(3,	'Farmacinha da Vovó ',	'teste@anunciante.com',	'21987654321',	500,	'24130400'),
(4,	'Drogarias Cura Tudo',	'',	'',	250,	'24130400');

DROP TABLE IF EXISTS `anuncios`;
CREATE TABLE `anuncios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_anunciante` int(11) NOT NULL,
  `texto` varchar(110) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_envio` datetime DEFAULT NULL,
  `cep` int(20) DEFAULT NULL,
  `enviado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `anuncios` (`id`, `id_anunciante`, `texto`, `data_cadastro`, `data_envio`, `cep`, `enviado`) VALUES
(1,	1,	' Pizza Gigante R$39,99 - Toda segunda-feira',	'2019-09-02 23:34:10',	'2019-09-12 00:45:17',	24130400,	0),
(2,	2,	'Refrigerante 2L Apenas R$3,99 HOJE',	'2019-09-05 22:16:12',	'2019-09-10 21:40:45',	24130400,	0),
(3,	3,	'Dipirona 500mg por R$2,99',	'2019-09-05 22:17:32',	'2019-09-10 21:50:06',	24130400,	0),
(4,	4,	'Oferta Relampago FRALDAS TRIPLA PROT por R$15,90 ate 28/09. Consulte ',	'2019-09-10 22:57:22',	NULL,	24130400,	0);

DROP TABLE IF EXISTS `cadastros`;
CREATE TABLE `cadastros` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `celular` varchar(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cadastros` (`id`, `celular`, `email`, `nome`, `cep`) VALUES
(47,	'21990761345',	'frimes@gmail.com',	'Filipe',	'24130400');

DROP TABLE IF EXISTS `relatorios`;
CREATE TABLE `relatorios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data_hora` datetime DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `sensor` int(10) unsigned DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL,
  `status` varchar(5) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `triagem_id` int(10) unsigned NOT NULL,
  `data_envio` timestamp NULL DEFAULT NULL,
  `enviado` varchar(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `relatorios_uk` (`data_hora`,`cep`,`sensor`,`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sensores_agua`;
CREATE TABLE `sensores_agua` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dia_hora` datetime DEFAULT NULL,
  `estado` enum('D','L') NOT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(5) DEFAULT NULL,
  `sensor` int(11) DEFAULT NULL,
  `pressao` varchar(6) DEFAULT NULL,
  `vazao` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sensores_luz`;
CREATE TABLE `sensores_luz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dia_hora` datetime DEFAULT NULL,
  `estado` enum('D','L') NOT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(5) DEFAULT NULL,
  `sensor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sistema`;
CREATE TABLE `sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info` varchar(50) DEFAULT NULL,
  `dado` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sistema` (`id`, `info`, `dado`) VALUES
(1,	'sms_s',	'Comux01$'),
(2,	'sms_on',	'1'),
(3,	'email_s',	'Comux01$'),
(4,	'demo_on',	'1');

DROP TABLE IF EXISTS `triagem`;
CREATE TABLE `triagem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data_hora` datetime DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `sensor` int(10) unsigned DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL,
  `status` varchar(5) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sensores_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `triagem_uk` (`data_hora`,`cep`,`sensor`,`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_uindex` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `senha`) VALUES
(1,	'admin',	'4297f44b13955235245b2497399d7a93'),
(2,	'user',	'4297f44b13955235245b2497399d7a93');

-- 2024-12-19 19:18:30
