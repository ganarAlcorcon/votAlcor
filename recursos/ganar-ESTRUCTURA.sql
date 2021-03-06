-- Fecha de dump: mié abr 8 12:49:43 CEST 2015
-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ganar
-- ------------------------------------------------------
-- Server version	5.5.41-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `CANDIDATOS`
--

DROP TABLE IF EXISTS `CANDIDATOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CANDIDATOS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE_COMPLETO` varchar(150) NOT NULL,
  `BIOGRAFIA` varchar(4000) NOT NULL,
  `MOTIVACIONES` varchar(4000) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CENSO`
--

DROP TABLE IF EXISTS `CENSO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CENSO` (
  `NOMBRE` varchar(50) NOT NULL,
  `APELLIDO1` varchar(50) NOT NULL,
  `APELLIDO2` varchar(50) DEFAULT NULL,
  `NIF` char(10) DEFAULT NULL,
  `FECHA_NACIMIENTO` date NOT NULL,
  `ID_VOTACION` int(11) NOT NULL,
  `HA_VOTADO` timestamp NULL DEFAULT NULL,
  `ID_MESA` int(11) DEFAULT NULL,
  UNIQUE KEY `NIF_UNIQUE` (`NIF`),
  UNIQUE KEY `NOMBRE_UNIQUE` (`NOMBRE`,`APELLIDO1`,`APELLIDO2`,`FECHA_NACIMIENTO`),
  KEY `fk_CENSO_1_idx` (`ID_MESA`),
  KEY `fk_CENSO_2_idx` (`ID_VOTACION`),
  CONSTRAINT `fk_CENSO_1` FOREIGN KEY (`ID_MESA`) REFERENCES `MESAS` (`ID`),
  CONSTRAINT `fk_CENSO_2` FOREIGN KEY (`ID_VOTACION`) REFERENCES `VOTACIONES` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MESAS`
--

DROP TABLE IF EXISTS `MESAS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MESAS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(50) NOT NULL,
  `LUGAR` varchar(200) NOT NULL,
  `DESCRIPCION` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PERMISOS`
--

DROP TABLE IF EXISTS `PERMISOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PERMISOS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(10) NOT NULL,
  `DESCRIPCION` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PERM_SIMP`
--

DROP TABLE IF EXISTS `PERM_SIMP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PERM_SIMP` (
  `ID_PERMISO` int(11) NOT NULL,
  `ID_SIMPATIZANTE` int(11) NOT NULL,
  PRIMARY KEY (`ID_PERMISO`,`ID_SIMPATIZANTE`),
  KEY `fk_PERM_SIMP_2_idx` (`ID_SIMPATIZANTE`),
  CONSTRAINT `fk_PERM_SIMP_1` FOREIGN KEY (`ID_PERMISO`) REFERENCES `PERMISOS` (`ID`),
  CONSTRAINT `fk_PERM_SIMP_2` FOREIGN KEY (`ID_SIMPATIZANTE`) REFERENCES `SIMPATIZANTES` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SIMPATIZANTES`
--

DROP TABLE IF EXISTS `SIMPATIZANTES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SIMPATIZANTES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(50) NOT NULL,
  `APELLIDO1` varchar(50) NOT NULL,
  `APELLIDO2` varchar(50) DEFAULT NULL,
  `NIF` char(10) DEFAULT NULL,
  `FECHA_NACIMIENTO` date NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `TELEFONO` char(9) NOT NULL,
  `PASSWORD` char(45) DEFAULT NULL,
  `EMAIL_V` char(1) NOT NULL DEFAULT 'N' COMMENT 'Email verificado, S o N',
  `DOCUMENTO_V` char(1) NOT NULL DEFAULT 'N' COMMENT 'Documento verificado, S o N',
  `PERSONA_V` char(1) NOT NULL DEFAULT 'N' COMMENT 'Persona verificada físicamente, S o N',
  `FECHA_ALTA` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FECHA_BAJA` timestamp NULL DEFAULT NULL,
  `IP_REGISTRO` char(16) NOT NULL,
  `PUERTO_REGISTRO` int(6) NOT NULL,
  `COOKIE` char(24) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `EMAIL_UNIQUE` (`EMAIL`),
  UNIQUE KEY `TELEFONO_UNIQUE` (`TELEFONO`),
  UNIQUE KEY `NIF_UNIQUE` (`NIF`),
  UNIQUE KEY `NOMBRE_UNIQUE` (`NOMBRE`,`APELLIDO1`,`APELLIDO2`,`FECHA_NACIMIENTO`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `VERIFICACIONES`
--

DROP TABLE IF EXISTS `VERIFICACIONES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VERIFICACIONES` (
  `ID_SIMP` int(11) NOT NULL,
  `CLAVE` char(72) NOT NULL COMMENT 'Del tipo 456F2312-A2E76SE7-45612312-A2E76SE7-4061B312-A2E76SE7-45612CD2-A2E76SE7',
  `TIPO` char(2) NOT NULL COMMENT 'Tipo de verificación E: email, T: Teléfono',
  PRIMARY KEY (`ID_SIMP`,`CLAVE`),
  CONSTRAINT `fk_VERI_SIMP` FOREIGN KEY (`ID_SIMP`) REFERENCES `SIMPATIZANTES` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `VOTACIONES`
--

DROP TABLE IF EXISTS `VOTACIONES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VOTACIONES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(45) NOT NULL,
  `DESCRIPCION` varchar(200) DEFAULT NULL,
  `INICIO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `FIN` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `VOTOS`
--

DROP TABLE IF EXISTS `VOTOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VOTOS` (
  `ID_SIMPATIZANTE` int(11) NOT NULL,
  `CABEZA_LISTA` varchar(3) DEFAULT NULL,
  `RESTO_LISTA` varchar(52) DEFAULT NULL,
  PRIMARY KEY (`ID_SIMPATIZANTE`),
  CONSTRAINT `fk_VOTOS_1` FOREIGN KEY (`ID_SIMPATIZANTE`) REFERENCES `SIMPATIZANTES` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-08 12:49:43
