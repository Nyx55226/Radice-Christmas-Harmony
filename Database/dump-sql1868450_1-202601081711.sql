-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: sql1868450_1
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `_view`
--

DROP TABLE IF EXISTS `_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `_view` (
  `link` varchar(200) NOT NULL,
  `indice` int(11) DEFAULT 0,
  PRIMARY KEY (`link`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_view`
--

LOCK TABLES `_view` WRITE;
/*!40000 ALTER TABLE `_view` DISABLE KEYS */;
INSERT INTO `_view` VALUES ('radicechristmasharmony/',7);
/*!40000 ALTER TABLE `_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accessi`
--

DROP TABLE IF EXISTS `accessi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accessi` (
  `id_accesso` int(11) NOT NULL AUTO_INCREMENT,
  `id_utente` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `orario` time NOT NULL,
  PRIMARY KEY (`id_accesso`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `accessi_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accessi`
--

LOCK TABLES `accessi` WRITE;
/*!40000 ALTER TABLE `accessi` DISABLE KEYS */;
/*!40000 ALTER TABLE `accessi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `like_lavori`
--

DROP TABLE IF EXISTS `like_lavori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `like_lavori` (
  `id_utente` int(11) NOT NULL,
  `id_lavoro` int(11) NOT NULL,
  PRIMARY KEY (`id_utente`,`id_lavoro`),
  KEY `id_lavoro` (`id_lavoro`),
  CONSTRAINT `like_lavori_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`),
  CONSTRAINT `like_lavori_ibfk_2` FOREIGN KEY (`id_lavoro`) REFERENCES `rch_lavoro` (`id_lavoro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `like_lavori`
--

LOCK TABLES `like_lavori` WRITE;
/*!40000 ALTER TABLE `like_lavori` DISABLE KEYS */;
/*!40000 ALTER TABLE `like_lavori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rch_lavoro`
--

DROP TABLE IF EXISTS `rch_lavoro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rch_lavoro` (
  `id_lavoro` int(11) NOT NULL,
  `descrizione` varchar(150) NOT NULL,
  `classe` varchar(50) NOT NULL,
  `link` varchar(200) DEFAULT NULL,
  `id_tipologia` int(11) NOT NULL,
  `giorno` int(11) NOT NULL,
  `link_lavoro` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_lavoro`),
  KEY `id_tipologia` (`id_tipologia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rch_lavoro`
--

LOCK TABLES `rch_lavoro` WRITE;
/*!40000 ALTER TABLE `rch_lavoro` DISABLE KEYS */;
INSERT INTO `rch_lavoro` VALUES (9,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,1,''),(10,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,2,''),(11,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,3,''),(12,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,4,''),(13,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,5,''),(14,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,6,''),(15,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,7,''),(16,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,8,''),(17,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,9,''),(18,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,10,''),(19,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,11,''),(20,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,12,''),(21,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,13,''),(22,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,14,''),(25,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,15,''),(29,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,16,''),(33,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,23,''),(37,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,17,''),(41,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,18,''),(45,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',4,19,''),(46,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,20,''),(49,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,24,''),(53,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,21,''),(57,'TEST','TEST','http://localhost/dashboard/radice-christmas-harmony/img_lavori/lavori.png',2,22,'');
/*!40000 ALTER TABLE `rch_lavoro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rch_tipo_lavoro`
--

DROP TABLE IF EXISTS `rch_tipo_lavoro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rch_tipo_lavoro` (
  `id_tipologia` int(11) NOT NULL,
  `descrizione` varchar(20) NOT NULL,
  PRIMARY KEY (`id_tipologia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rch_tipo_lavoro`
--

LOCK TABLES `rch_tipo_lavoro` WRITE;
/*!40000 ALTER TABLE `rch_tipo_lavoro` DISABLE KEYS */;
INSERT INTO `rch_tipo_lavoro` VALUES (1,'Poesia'),(2,'Video'),(3,'Brano'),(4,'Foto');
/*!40000 ALTER TABLE `rch_tipo_lavoro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utenti` (
  `id_utente` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(300) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_utente`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sql1868450_1'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-08 17:11:26
