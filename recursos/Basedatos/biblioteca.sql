CREATE DATABASE  IF NOT EXISTS `biblioteca` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `biblioteca`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: biblioteca
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `autor`
--

DROP TABLE IF EXISTS `autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `autor` (
  `autor_id` int(11) NOT NULL AUTO_INCREMENT,
  `autor_nombre` varchar(100) NOT NULL,
  `autor_nacionalidad` varchar(50) DEFAULT NULL,
  `autor_fecha_nacimiento` date DEFAULT NULL,
  PRIMARY KEY (`autor_id`),
  UNIQUE KEY `nombre` (`autor_nombre`),
  KEY `idx_autor_nombre` (`autor_nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ejemplar`
--

DROP TABLE IF EXISTS `ejemplar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ejemplar` (
  `ejemplar_id` int(11) NOT NULL AUTO_INCREMENT,
  `ejemplar_libro_id` int(11) NOT NULL,
  `ejemplar_estado` enum('disponible','prestado','en_mora','perdido') DEFAULT 'disponible',
  PRIMARY KEY (`ejemplar_id`),
  KEY `libro_id` (`ejemplar_libro_id`),
  KEY `idx_ejemplar_estado` (`ejemplar_estado`),
  CONSTRAINT `ejemplar_ibfk_1` FOREIGN KEY (`ejemplar_libro_id`) REFERENCES `libro` (`libro_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `libro`
--

DROP TABLE IF EXISTS `libro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `libro` (
  `libro_id` int(11) NOT NULL AUTO_INCREMENT,
  `libro_titulo` varchar(255) NOT NULL,
  `libro_isbn` varchar(13) NOT NULL,
  `libro_copias_totales` int(11) DEFAULT 0,
  `libro_copias_disponibles` int(11) DEFAULT 0,
  PRIMARY KEY (`libro_id`),
  UNIQUE KEY `isbn` (`libro_isbn`),
  KEY `idx_libro_titulo` (`libro_titulo`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `libroautor_libroautor`
--

DROP TABLE IF EXISTS `libroautor_libroautor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `libroautor_libroautor` (
  `libroautor_id` int(11) NOT NULL AUTO_INCREMENT,
  `libroautor_libro_id` int(11) NOT NULL,
  `autor_id` int(11) NOT NULL,
  PRIMARY KEY (`libroautor_id`),
  KEY `autor_id` (`autor_id`),
  KEY `libroautor_ibfk_1` (`libroautor_libro_id`),
  CONSTRAINT `libroautor_ibfk_1` FOREIGN KEY (`libroautor_libro_id`) REFERENCES `libro` (`libro_id`) ON DELETE CASCADE,
  CONSTRAINT `libroautor_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `autor` (`autor_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prestamo`
--

DROP TABLE IF EXISTS `prestamo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestamo` (
  `prestamo_id` int(11) NOT NULL AUTO_INCREMENT,
  `prestamo_prestatario_id` int(11) NOT NULL,
  `prestamo_bibliotecario_id` int(11) NOT NULL,
  `prestamo_fecha_prestamo` datetime DEFAULT current_timestamp(),
  `prestamo_fecha_devolucion_prevista` datetime NOT NULL,
  `prestamo_fecha_devolucion_real` datetime DEFAULT NULL,
  PRIMARY KEY (`prestamo_id`),
  KEY `prestatario_id` (`prestamo_prestatario_id`),
  KEY `bibliotecario_id` (`prestamo_bibliotecario_id`),
  CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`prestamo_prestatario_id`) REFERENCES `prestatario` (`prestatario_id`),
  CONSTRAINT `prestamo_ibfk_2` FOREIGN KEY (`prestamo_bibliotecario_id`) REFERENCES `usuario` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prestamoejemplar`
--

DROP TABLE IF EXISTS `prestamoejemplar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestamoejemplar` (
  `prestamoejemplar_id` int(11) NOT NULL AUTO_INCREMENT,
  `prestamoejemplar_prestamo_id` int(11) NOT NULL,
  `prestamoejemplar_ejemplar_id` int(11) NOT NULL,
  PRIMARY KEY (`prestamoejemplar_id`),
  KEY `ejemplar_id` (`prestamoejemplar_ejemplar_id`),
  KEY `prestamoejemplar_ibfk_1` (`prestamoejemplar_prestamo_id`),
  CONSTRAINT `prestamoejemplar_ibfk_1` FOREIGN KEY (`prestamoejemplar_prestamo_id`) REFERENCES `prestamo` (`prestamo_id`) ON DELETE CASCADE,
  CONSTRAINT `prestamoejemplar_ibfk_2` FOREIGN KEY (`prestamoejemplar_ejemplar_id`) REFERENCES `ejemplar` (`ejemplar_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prestatario`
--

DROP TABLE IF EXISTS `prestatario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestatario` (
  `prestatario_id` int(11) NOT NULL AUTO_INCREMENT,
  `prestatario_nombre` varchar(100) NOT NULL,
  `prestatario_identificacion` varchar(20) NOT NULL,
  `prestatario_fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`prestatario_id`),
  UNIQUE KEY `identificacion` (`prestatario_identificacion`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL AUTO_INCREMENT,
  `rol_nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`rol_id`),
  UNIQUE KEY `nombre` (`rol_nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_nombre` varchar(100) NOT NULL,
  `usuario_email` varchar(100) NOT NULL,
  `usuario_contrasena` varchar(255) NOT NULL,
  `usuario_rol_id` int(11) NOT NULL,
  `usuario_fecha_registro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `email` (`usuario_email`),
  KEY `rol_id` (`usuario_rol_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`usuario_rol_id`) REFERENCES `rol` (`rol_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-20 23:39:48
