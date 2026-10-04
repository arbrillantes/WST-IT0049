-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: electric_company
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Puihaha','Administrator','admin@puihaha.example',NULL,'','','','','$2y$10$sxaSB1oI6RKAXNwgSNI63eullb/IyMJCK2vWwSZK/CAjLOt7joVz2','admin',1,0,'2026-10-04 15:52:25','2026-10-04 15:52:25','admin'),(6,'Angelo','Brillantes','lowee580@gmail.com','(096) 736-51454','Calumpang','QC','QC','12312','$2y$10$oCGf9y92LJFQ3baIGWpOk.RUjeMq8VfHsIMU89vEwJrDEKWZb0v5i','customer',1,0,'2026-10-04 16:02:14','2026-10-04 16:02:14',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_accounts`
--

DROP TABLE IF EXISTS `customer_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_number` varchar(50) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `meter_number` varchar(50) DEFAULT NULL,
  `connection_type` varchar(20) NOT NULL DEFAULT 'residential',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `customer_name` (`customer_name`),
  KEY `connection_type` (`connection_type`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_accounts`
--

LOCK TABLES `customer_accounts` WRITE;
/*!40000 ALTER TABLE `customer_accounts` DISABLE KEYS */;
INSERT INTO `customer_accounts` VALUES (1,'EC-2024-0001','John Smith','123 Main Street, Downtown','555-0101','john.smith@email.com','MTR-001','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(2,'EC-2024-0002','Sarah Johnson','456 Oak Avenue, Suburb','555-0102','sarah.j@email.com','MTR-002','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(3,'EC-2024-0003','ABC Corporation','789 Business Blvd, City Center','555-\r\n0103','contact@abc.com','MTR-003','commercial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(4,'EC-2024-0004','Michael Brown','321 Pine Road, Eastside','555-0104','mbrown@email.com','MTR-004','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(5,'EC-2024-0005','Tech Industries Inc','555 Industrial Park, Zone A','555-\r\n0105','info@techindustries.com','MTR-005','industrial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(6,'EC-2024-0006','Emily Davis','678 Maple Drive, Westside','555-0106','emily.d@email.com','MTR-006','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(7,'EC-2024-0007','Green Mart Store','890 Commerce Street, Plaza','555-\r\n0107','greenmart@email.com','MTR-007','commercial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(8,'EC-2024-0008','Robert Wilson','234 Cedar Lane, Northside','555-0108','rwilson@email.com','MTR-008','residential','inactive','2025-10-22 01:01:51','2025-10-22 01:01:51'),(9,'EC-2024-0009','Manufacturing Co','432 Factory Road, Industrial Zone','555-0109','info@mfgco.com','MTR-009','industrial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(10,'EC-2024-0010','Lisa Anderson','567 Birch Street, Southside','555-0110','landerson@email.com','MTR-010','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(11,'EC-2024-0011','David Martinez','890 Elm Avenue, Central','555-0111','dmartinez@email.com','MTR-011','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(12,'EC-2024-0012','Retail Plaza LLC','123 Shopping Center, Mall District','555-0112','contact@retailplaza.com','MTR-012','commercial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(13,'EC-2024-0013','Jennifer Taylor','456 Spruce Road, Hillside','555-0113','jtaylor@email.com','MTR-013','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(14,'EC-2024-0014','Heavy Industries Ltd','789 Manufacturing Ave, Zone B','555-0114','info@heavyind.com','MTR-014','industrial','suspended','2025-10-22 01:01:51','2025-10-22 01:01:51'),(15,'EC-2024-0015','Thomas White','321 Willow Lane, Riverside','555-0115','twhite@email.com','MTR-015','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(16,'EC-2024-0016','Office Complex Inc','654 Corporate Drive, Business Park','555-0116','admin@officecomplex.com','MTR-016','commercial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(17,'EC-2024-0017','Patricia Harris','987 Ash Street, Lakeside','555-0117','pharris@email.com','MTR-017','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(18,'EC-2024-0018','Auto Manufacturing','246 Assembly Line Rd, Industrial\r\nPark','555-0118','contact@automfg.com','MTR-018','industrial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(19,'EC-2024-0019','Christopher Lee','135 Poplar Avenue, Garden District','555-0119','clee@email.com','MTR-019','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(20,'EC-2024-0020','Shopping Center Co','468 Retail Blvd, Downtown','555-\r\n0120','info@shopcenter.com','MTR-020','commercial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(21,'EC-2024-0021','Nancy Clark','579 Hickory Drive, Parkside','555-0121','nclark@email.com','MTR-021','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(22,'EC-2024-0022','Steel Works Inc','802 Foundry Road, Industrial Zone C','555-0122','contact@steelworks.com','MTR-022','industrial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(23,'EC-2024-0023','Daniel Lewis','913 Sycamore Lane, Meadow View','555-\r\n0123','dlewis@email.com','MTR-023','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(24,'EC-2024-0024','Restaurant Group LLC','246 Dining Street, Food District','555-0124','info@restgroup.com','MTR-024','commercial','active','2025-10-22 01:01:51','2025-10-22 01:01:51'),(25,'EC-2024-0025','Karen Walker','357 Magnolia Road, Sunset Hills','555-\r\n0125','kwalker@email.com','MTR-025','residential','active','2025-10-22 01:01:51','2025-10-22 01:01:51');
/*!40000 ALTER TABLE `customer_accounts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-04 16:07:58
