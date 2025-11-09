-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: egtiz_db
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.24.04.2

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
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `image` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel_cache_1c2e9d49636dd51eb89c31752404a688ead0d8c7','i:1;',1762703708),('laravel_cache_1c2e9d49636dd51eb89c31752404a688ead0d8c7:timer','i:1762703708;',1762703708),('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab','i:6;',1762701282),('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1762701282;',1762701282),('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba','i:1;',1762702592),('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba:timer','i:1762702592;',1762702592),('laravel_cache_91032ad7bbcb6cf72875e8e8207dcfba80173f7c','i:7;',1762703808),('laravel_cache_91032ad7bbcb6cf72875e8e8207dcfba80173f7c:timer','i:1762703808;',1762703808),('laravel_cache_admin@egtize.com|127.0.0.1','i:1;',1762597044),('laravel_cache_admin@egtize.com|127.0.0.1:timer','i:1762597044;',1762597044),('laravel_cache_avatar_2bd6e3c7a48decd4f8e957bb783b880b','s:2390:\"data:image/png;base64,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\";',1762262696),('laravel_cache_avatar_82742abb30d0ead82ba55d2f6c38c8bf','s:1142:\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAC+UlEQVR4nO2dPW4bMRSEn4TAARKkiIMEqRIELnIAuXPlK7jJTXQIXyF30CGMtHsCde6cwoUrB46xLow1JJr7Q/Lxcbiar19xNZ+G5Apa7aJtWyE4vCl9AqE0m3XwJ2h1cbnIcS45WCA3JCb8qaBKghKSU8AYKIIghJQU4VJaTDEhSBL6KCHHXEgNIlwsxSytBhKpU4aI7XmbNKRWET5ytyWrkDmJcMklJouQOYtw0RZjuoaQcVQbckjNcNFqilpDDlmGiN77VxFy6DI6NHJIFkIZ+6TmkSSEMvyk5BIthDKGic0nSghlTCMmp2AhlBFGaF68MARj8oUhm5HOlItHNgSMSULYDh2m5MiGgDEqhO3QZSxPNgSMQSFsRx6Gcu0VQhl56cuXUxYYXiFshw2+nNkQMExuRzj+tpIfp7/UXu/u71a2f37DjanBq4ZwurLFzdv8hp2b7ZU8PtxHHfvp+6m8fX9cxZix7AmxaMfN9koe7u+ijv3w+SRaiPWYITSbddt9E8xFHQwKAYNCwHgRwt1VWbr82RAwKAQMCgGDQsBYinBBR6HZrFs2BAwKAYNCwKAQMCgEDAoBg0LAoBAwKAQMCgGDQsCgEDCWIuX/+JE8s7q4XLAhYFAIGBQCBoWA8SKEC3tZ+FNSUMx//f7l5Ewe//+LOvbo3cdqxozFXMjXn+fWQxYZM5a9KYvrSBl2czdpyO11I7fXjcVQRcfU4NWizpbY4ubNXRYYFAKGVwinLRt8ObMhYPQKYUvy0pfvYEMoJQ9DuXLKAmNUCFuiy1iebAgYk4SwJTrwj5QrJPgZVLwfMZyQGYYNASNYCNeTMELzimoIpUwjJqfoKYtShonNJ2kNoRQ/KbkkL+qUsk9qHiq7LEp5RiMHtW3voUvRev98fHcifHz3zMnSkI45NyXXFJ1VSMecxOReK02EdNQsxmrTYrqG1LoTszxv04bsUkNbSnyAignZBUlO6RZDCOkoKaa0iA4oIS45BaEIcIEW4iNGEmr4Pp4AtTRSzJn0XbMAAAAASUVORK5CYII=\";',1762256275),('laravel_cache_avatar_884f5af2d4427be7bf4147e383e24752','s:3122:\"data:image/png;base64,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\";',1762262814),('laravel_cache_avatar_88b4fa2eb0009d7801b07f64aadeaa17','s:2778:\"data:image/png;base64,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\";',1762683524),('laravel_cache_avatar_a1d74735676e4579bbf31d4a92f4bfa8','s:2550:\"data:image/png;base64,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\";',1762783763),('laravel_cache_avatar_e26fd97b8a65f5f7f46d08900f6c45f5','s:2950:\"data:image/png;base64,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\";',1762256092),('laravel_cache_egtiaz@admin.com|127.0.0.1','i:1;',1762245795),('laravel_cache_egtiaz@admin.com|127.0.0.1:timer','i:1762245795;',1762245795),('laravel_cache_f1abd670358e036c31296e66b3b66c382ac00812','i:1;',1762703671),('laravel_cache_f1abd670358e036c31296e66b3b66c382ac00812:timer','i:1762703671;',1762703671),('laravel_cache_spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:12:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:6:\"admins\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"iqamaTypes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:5:\"users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"companies\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:6:\"stages\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:15:\"paymentAccounts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:10:\"moderators\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:6:\"leaves\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:3:\"eos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:7:\"reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:5:\"roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super-admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"moderator\";s:1:\"c\";s:3:\"web\";}}}',1762772209);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `banner_image` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (4,'{\"ar\": \"اجتياز\", \"en\": \"egtiaz\"}','{\"ar\": \"لبمي سبهخت\", \"en\": \"sdjlg fjdfhj sfhlji\"}','companies/Surv5wbUo2rJKSYJfZEwdYQNAH84YLCDvCIxKOG2.jpg','companies/CZVKXoyA0aH9AV83al3HwCslbY2WdpMst8btqbC7.jpg','active','2025-08-19 10:54:53','2025-08-19 10:54:53'),(5,'{\"ar\": \"Jesse Andrews\", \"en\": \"Neil Dickson\"}','{\"ar\": \"Sint corrupti arch\", \"en\": \"Adipisicing consequa\"}','companies/ypb8ZJF9zIaH0eeQym52i1QidoHJ5yOA2uiubLGC.jpg','companies/DrflWqiViE0MhGXsuBUNNcdmLkTPPi6eflSbxsaU.jpg','active','2025-08-23 07:10:35','2025-08-23 07:10:35'),(6,'{\"ar\": \"Ross Thompson\", \"en\": \"Oren Finley\"}','{\"ar\": \"In quis ad tempor is\", \"en\": \"Repudiandae quos qui\"}','companies/j7nvTA0EXk7thEv66982P7JBJW8Wl9wNFj1yfZTi.jpg','companies/rybwoyHakE1fBWnEbEftjNyMZkAL6TSuKcoXp5Gp.jpg','inactive','2025-08-23 07:10:55','2025-09-25 06:51:13'),(7,'{\"ar\": \"Brett Taylor\", \"en\": \"Katell Mclaughlin\"}','{\"ar\": \"Voluptatem Nam dese\", \"en\": \"Voluptas optio eius\"}','companies/apkGM3jS1iJvVAKcYHNktPuy0GrOTnhB541Cgdvz.jpg','companies/Z4tARm5vDAzlG43NH03HFg0yF4poqI1OHDoG5Y0g.jpg','active','2025-08-23 07:11:14','2025-09-25 06:51:11'),(8,'{\"ar\": \"حلول تكنوفا\",\"en\": \"TechNova Solutions\"}','{\"ar\": \"شركة تطوير برمجيات مبتكرة مدعومة بالذكاء الاصطناعي متخصصة في حلول المؤسسات\",\"en\": \"Innovative AI-powered software development company specializing in enterprise solutions\"}',NULL,NULL,'active','2025-10-01 09:38:07','2025-10-01 09:38:08'),(9,'{\"ar\": \"الابتكارات الكمومية\",\"en\": \"Quantum Innovations\"}','{\"ar\": \"شركة بحث وتطوير تركز على الحوسبة الكمومية والتقنيات المتقدمة\",\"en\": \"Research and development company focused on quantum computing and advanced technologies\"}',NULL,NULL,'active','2025-10-01 09:40:07','2025-10-01 09:40:09');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_files`
--

DROP TABLE IF EXISTS `employee_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_files_employee_id_foreign` (`employee_id`),
  CONSTRAINT `employee_files_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_files`
--

LOCK TABLES `employee_files` WRITE;
/*!40000 ALTER TABLE `employee_files` DISABLE KEYS */;
INSERT INTO `employee_files` VALUES (1,7,'employee_files/Aquila MitchellFINAL LOGO IJTIAZ.pdf','2025-08-23 05:21:56','2025-08-23 05:21:56'),(2,7,'employee_files/Aquila Mitchellreadme.pdf','2025-08-23 05:21:56','2025-08-23 05:21:56'),(3,7,'employee_files/Aquila MitchellCustomers - Suppliers Reports - ABA DIRGAM CO..pdf','2025-08-23 05:21:56','2025-08-23 05:21:56'),(5,8,'employee_files/Kiayada Cochranreadme.pdf','2025-08-23 07:12:59','2025-08-23 07:12:59'),(7,9,'employee_files/Owen AshleyWhatsApp Image 2025-08-18 at 17.20.45_0abd7e6f.jpg','2025-08-23 07:13:28','2025-08-23 07:13:28'),(8,31,'employee_files/CA-1doctor.jpg','2025-11-06 07:42:25','2025-11-06 07:42:25'),(9,8,'employee_files/dqa8BdJ3bEgk67qHH2mB_1762426074.jpg','2025-11-06 08:47:54','2025-11-06 08:47:54');
/*!40000 ALTER TABLE `employee_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_stage_files`
--

DROP TABLE IF EXISTS `employee_stage_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_stage_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_stage_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_stage_files_employee_stage_id_foreign` (`employee_stage_id`),
  CONSTRAINT `employee_stage_files_employee_stage_id_foreign` FOREIGN KEY (`employee_stage_id`) REFERENCES `employee_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_stage_files`
--

LOCK TABLES `employee_stage_files` WRITE;
/*!40000 ALTER TABLE `employee_stage_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_stage_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_stages`
--

DROP TABLE IF EXISTS `employee_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_stages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `stage_id` bigint unsigned NOT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL,
  `done_by` bigint unsigned DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `currently_type` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_stages_employee_id_foreign` (`employee_id`),
  KEY `employee_stages_stage_id_foreign` (`stage_id`),
  KEY `employee_stages_done_by_foreign` (`done_by`),
  CONSTRAINT `employee_stages_done_by_foreign` FOREIGN KEY (`done_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employee_stages_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_stages_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_stages`
--

LOCK TABLES `employee_stages` WRITE;
/*!40000 ALTER TABLE `employee_stages` DISABLE KEYS */;
INSERT INTO `employee_stages` VALUES (1,7,3,'completed','2025-11-03 12:01:17',15,NULL,NULL,1,'2025-08-23 05:21:56','2025-11-03 12:01:17'),(2,7,4,'completed','2025-11-03 12:13:40',15,NULL,NULL,1,'2025-08-23 05:21:56','2025-11-03 12:13:40'),(3,8,3,'completed','2025-10-02 05:44:30',NULL,NULL,NULL,1,'2025-08-23 07:12:59','2025-10-02 05:44:30'),(4,8,4,'completed','2025-10-02 05:41:31',NULL,NULL,NULL,1,'2025-08-23 07:12:59','2025-10-02 05:41:31'),(5,9,3,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:13:28','2025-08-23 07:13:28'),(6,9,4,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:13:28','2025-08-23 07:13:28'),(7,10,2,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:13:51','2025-08-23 07:13:51'),(8,10,7,'completed','2025-10-02 05:57:01',NULL,NULL,NULL,1,'2025-08-23 07:13:51','2025-10-02 05:57:01'),(53,10,3,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 05:21:56','2025-08-23 05:21:56'),(54,10,4,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 05:21:56','2025-08-23 05:21:56'),(55,21,3,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:12:59','2025-08-23 07:12:59'),(56,21,4,'completed','2025-10-02 06:58:05',11,NULL,NULL,1,'2025-08-23 07:12:59','2025-10-02 06:58:05'),(57,22,3,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:13:28','2025-08-23 07:13:28'),(58,22,4,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:13:28','2025-08-23 07:13:28'),(59,23,2,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:13:51','2025-08-23 07:13:51'),(60,23,7,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:13:51','2025-08-23 07:13:51'),(61,24,3,'completed','2025-10-04 05:10:05',11,NULL,NULL,1,'2025-08-23 07:14:15','2025-10-04 05:10:05'),(62,24,8,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:14:15','2025-08-23 07:14:15'),(63,25,2,'completed','2025-10-02 06:47:16',NULL,NULL,NULL,1,'2025-08-23 07:14:42','2025-10-02 06:47:16'),(64,25,7,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:14:42','2025-08-23 07:14:42'),(65,26,3,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:15:08','2025-08-23 07:15:08'),(66,26,8,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:15:08','2025-08-23 07:15:08'),(67,27,4,'completed','2025-10-04 05:37:09',11,NULL,NULL,1,'2025-08-23 07:15:35','2025-10-04 05:37:09'),(68,27,7,'completed','2025-10-02 06:55:47',NULL,NULL,NULL,1,'2025-08-23 07:15:35','2025-10-02 06:55:47'),(69,28,2,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:16:02','2025-08-23 07:16:02'),(70,28,4,'completed','2025-10-02 07:15:49',11,NULL,NULL,1,'2025-08-23 07:16:02','2025-10-02 07:15:49'),(71,29,3,'completed','2025-10-02 04:46:21',NULL,NULL,NULL,1,'2025-08-23 07:16:29','2025-10-02 04:46:21'),(72,29,8,'completed','2025-10-04 05:12:40',11,NULL,NULL,1,'2025-08-23 07:16:29','2025-10-04 05:12:40'),(73,30,2,'completed','2025-10-02 07:10:57',11,NULL,NULL,1,'2025-08-23 07:16:55','2025-10-02 07:10:57'),(74,30,7,'pending',NULL,NULL,NULL,NULL,1,'2025-08-23 07:16:55','2025-08-23 07:16:55'),(75,31,3,'pending',NULL,NULL,NULL,NULL,1,'2025-11-06 07:42:25','2025-11-06 07:42:25'),(76,31,4,'pending',NULL,NULL,NULL,NULL,1,'2025-11-06 07:42:25','2025-11-06 07:42:25');
/*!40000 ALTER TABLE `employee_stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(8,2) DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('m','f') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'm',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `company_id` bigint unsigned NOT NULL,
  `iqama_type_id` bigint unsigned NOT NULL,
  `expired_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employees_company_id_foreign` (`company_id`),
  KEY `employees_iqama_type_id_foreign` (`iqama_type_id`),
  CONSTRAINT `employees_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employees_iqama_type_id_foreign` FOREIGN KEY (`iqama_type_id`) REFERENCES `iqama_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (7,'Aquila Mitchell','qefykijeb@mailinator.com','+1 (437) 932-7464',5000.00,'Atque qui ducimus a','employees/pJ4GWC5O30MPBZVUvtJ4R6ShoK0E8BIq19gKkqKp.jpg','passports/ERsNrcgc48MhsFcfd3DO9noRECAk6ntgI4URwJZ8.jpg','326','f','active',4,1,'1972-08-03','2025-08-23 02:21:56','2025-09-25 04:13:57'),(8,'Kiayada Cochran','mizuhasyc@mailinator.com','+1 (289) 104-9943',5000.00,'Hic officia cupidata','employees/YjSNcjDx6n8B05PabNOlc80HSfWEYv38aPd6znAg.jpg','passports/48n7wXsc8KqZ5901qGTGCZAuIZHU3F55kvjxOere.jpg','824','m','active',4,1,'1971-04-07','2025-08-23 04:12:59','2025-08-23 04:12:59'),(9,'Owen Ashley','beru@mailinator.com','+1 (417) 564-4928',5000.00,'Provident rerum qua','employees/lVki5RbUxdb9Hgie4S2rH4szFSCVJodqwdaduKgv.jpg','passports/rrmZtwjYHcEWimUVlhSJ8juE9ulskd9pHBfSCxLO.jpg','998','f','active',4,1,'2009-10-30','2025-08-23 04:13:27','2025-09-15 06:44:03'),(10,'Rhea Conrad','jawocav@mailinator.com','+1 (515) 474-9474',5000.00,'Quibusdam magna cons',NULL,NULL,'597','m','active',4,3,'2008-06-30','2025-08-23 04:13:51','2025-08-23 04:13:51'),(21,'سارة أحمد','sara.ahmed@mailinator.com','+966 55 123 4567',5000.00,'شارع الملك فهد، الرياض',NULL,NULL,'AB123456','f','active',8,1,'2026-12-31','2025-08-20 03:15:22','2025-08-20 03:15:22'),(22,'أحمد المنصوري','ahmed.mansouri@mailinator.com','+966 55 234 5678',5000.00,'حي النخيل، جدة',NULL,NULL,'CD789012','m','active',9,3,'2025-11-15','2025-08-21 08:30:45','2025-08-21 08:30:45'),(23,'فاطمة العلي','fatima.alali@mailinator.com','+966 55 345 6789',5000.00,'حي الصحافة، الرياض',NULL,NULL,'EF345678','f','inactive',8,5,'2024-09-30','2025-08-19 05:20:33','2025-08-22 10:45:12'),(24,'محمد حسن','mohammed.hassan@mailinator.com','+966 55 456 7890',5000.00,'حي العليا، الرياض',NULL,NULL,'GH901234','m','active',9,6,'2026-03-20','2025-08-18 02:45:15','2025-08-18 02:45:15'),(25,'نورة السعد','nora.alsaad@mailinator.com','+966 55 567 8901',5000.00,'حي الياسمين، الدمام',NULL,NULL,'IJ567890','f','active',8,1,'2025-07-10','2025-08-22 07:10:28','2025-08-22 07:10:28'),(26,'خالد الحربي','khaled.alharbi@mailinator.com','+966 55 678 9012',5000.00,'حي النور، مكة',NULL,NULL,'KL123789','m','active',9,3,'2026-08-25','2025-08-17 04:55:42','2025-08-23 03:30:18'),(27,'لينا القحطاني','leena.alqahtani@mailinator.com','+966 55 789 0123',5000.00,'حي الروضة، الخبر',NULL,NULL,'MN456123','f','active',8,5,'2025-12-05','2025-08-16 09:25:37','2025-08-16 09:25:37'),(28,'عبدالله الغامدي','abdullah.alghamdi@mailinator.com','+966 55 890 1234',5000.00,'حي الشفا، الطائف',NULL,NULL,'OP789456','m','inactive',9,6,'2024-12-15','2025-08-15 06:40:55','2025-08-21 08:15:29'),(29,'ريم العتيبي','reem.outefi@mailinator.com','+966 55 901 2345',5000.00,'حي الورود، أبها',NULL,NULL,'QR321654','f','active',8,1,'2026-05-18','2025-08-14 11:35:24','2025-08-14 11:35:24'),(30,'ياسر الشمراني','yasser.alshamrani@mailinator.com','+966 55 012 3456',5000.00,'حي الفيصلية، جدة',NULL,NULL,'ST987321','m','active',9,3,'2025-10-30','2025-08-13 05:05:19','2025-08-23 02:20:47');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `end_of_services`
--

DROP TABLE IF EXISTS `end_of_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `end_of_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `joining_date` date NOT NULL,
  `leaving_date` date NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `gross_salary` decimal(10,2) DEFAULT NULL,
  `notice_period_days` int NOT NULL DEFAULT '0',
  `incentive` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rewards` decimal(10,2) NOT NULL DEFAULT '0.00',
  `other_additions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cash_advance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `petty_cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fines` decimal(10,2) NOT NULL DEFAULT '0.00',
  `compensation_notice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `other_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `annual_leave_balance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `end_of_services_user_id_foreign` (`user_id`),
  CONSTRAINT `end_of_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `end_of_services`
--

LOCK TABLES `end_of_services` WRITE;
/*!40000 ALTER TABLE `end_of_services` DISABLE KEYS */;
INSERT INTO `end_of_services` VALUES (1,12,'2025-11-01','2025-11-30',54.00,8654.00,0,654.00,546.00,45.00,54.00,654.00,54.00,20.00,0.00,236.00,887.80,'2025-11-09 07:27:17','2025-11-09 07:29:43');
/*!40000 ALTER TABLE `end_of_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `connection` text COLLATE utf8mb4_general_ci NOT NULL,
  `queue` text COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iqama_types`
--

DROP TABLE IF EXISTS `iqama_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iqama_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iqama_types`
--

LOCK TABLES `iqama_types` WRITE;
/*!40000 ALTER TABLE `iqama_types` DISABLE KEYS */;
INSERT INTO `iqama_types` VALUES (1,'{\"ar\": \"الاقامة الذهبية\", \"en\": \"Golden\"}','{\"ar\": \"سيل خحيتلا يسحبخت\", \"en\": \"dfoih sdopgj,srgjpd\"}','2025-08-17 15:20:39','2025-08-17 15:23:11'),(3,'{\"ar\": \"كادر طبي\", \"en\": \"medical\"}','{\"ar\": \"يسال هيل بهلت سبهخا يباحت\", \"en\": \"skg slgfdi sfgh sdg\"}','2025-08-18 05:12:19','2025-08-18 05:12:19'),(5,'{\"ar\": \"Amela Parrish\", \"en\": \"Kylan Bonner\"}','{\"ar\": \"Consequatur Hic ex\", \"en\": \"Aute voluptate quae\"}','2025-08-23 07:11:30','2025-08-23 07:11:30'),(6,'{\"ar\": \"Petra Puckett\", \"en\": \"Shelley Cline\"}','{\"ar\": \"Sed eiusmod nisi iur\", \"en\": \"Voluptas tempor itaq\"}','2025-08-23 07:11:41','2025-08-23 07:11:41');
/*!40000 ALTER TABLE `iqama_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_general_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_types`
--

DROP TABLE IF EXISTS `leave_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leave_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `leave_types_chk_1` CHECK (json_valid(`name`)),
  CONSTRAINT `leave_types_chk_2` CHECK (json_valid(`description`))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_types`
--

LOCK TABLES `leave_types` WRITE;
/*!40000 ALTER TABLE `leave_types` DISABLE KEYS */;
INSERT INTO `leave_types` VALUES (1,'{\"ar\": \"asdfadsf\", \"en\": \"asdfasdf\"}','{\"ar\": \"asdfasdf\"}',1,'2025-09-27 12:50:30','2025-09-27 12:50:30'),(2,'{\"ar\": \"asdfasdf\", \"en\": \"asdfasdf\"}','{\"ar\": \"asdfasdf\", \"en\": \"asdfasdfasdf\"}',1,'2025-09-27 12:51:25','2025-09-27 12:51:25'),(3,'{\"ar\": \"شسيبشسيبش\", \"en\": \"asdfasdfasdf\"}','{\"ar\": \"شسيبشسيبشسيب\", \"en\": \"asdfasdfasdfadsf\"}',1,'2025-09-27 12:53:59','2025-09-27 12:53:59');
/*!40000 ALTER TABLE `leave_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_06_10_123508_create_personal_access_tokens_table',1),(5,'2025_06_10_123743_add_phone_column_to_users_table',1),(6,'2025_06_10_150001_create_permission_tables',1),(7,'2025_06_11_102247_add_notification_columns_to_users_table',1),(8,'2025_06_19_153600_create_banners_table',1),(11,'2025_08_17_155246_create_companies_table',2),(12,'2025_08_17_160636_add_moderator_company_id_column_to_users_table',2),(13,'2025_08_17_170602_create_wallets_table',3),(14,'2025_08_17_175536_create_iqama_types_table',4),(15,'2025_08_17_182607_create_employees_table',5),(18,'2025_08_18_175259_create_stages_table',6),(19,'2025_08_20_141524_create_employee_files_table',7),(20,'2025_08_21_233802_create_employee_stages_table',8),(21,'2025_08_23_151950_create_payment_accounts_table',9),(22,'2025_08_24_165234_create_payment_acount_users_table',10),(23,'2025_08_25_172109_create_employee_stage_files_table',11),(24,'2025_09_25_110859_add_column_cost_to_stages_table',12),(25,'2025_09_27_075010_create_official_leaves_table',13),(26,'2025_09_27_133329_create_leave_types_table',14),(27,'2025_09_25_121048_create_transaction_companies_table',15),(28,'2025_09_25_122838_create_transactions_table',16),(29,'2025_10_02_124448_create_salaries_table',17),(30,'2025_10_04_090336_add_columnsalary_salary_to_users_table',18),(31,'2025_10_04_095140_add_column_salarycoupon_id_to_table_bookings',19),(32,'2025_10_22_080244_create_end_of_services_table',19),(33,'2025_11_09_111738_create_wallet_transactions_table',20);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
INSERT INTO `model_has_permissions` VALUES (1,'App\\Models\\User',20),(3,'App\\Models\\User',20),(1,'App\\Models\\User',21),(3,'App\\Models\\User',21);
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(3,'App\\Models\\User',15),(2,'App\\Models\\User',20),(2,'App\\Models\\User',21);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `official_leaves`
--

DROP TABLE IF EXISTS `official_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `official_leaves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('annual','sick','maternity','paternity','unpaid','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_taken` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `official_leaves_approved_by_foreign` (`approved_by`),
  KEY `official_leaves_user_id_status_index` (`user_id`,`status`),
  KEY `official_leaves_start_date_end_date_index` (`start_date`,`end_date`),
  CONSTRAINT `official_leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `official_leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `official_leaves`
--

LOCK TABLES `official_leaves` WRITE;
/*!40000 ALTER TABLE `official_leaves` DISABLE KEYS */;
INSERT INTO `official_leaves` VALUES (2,10,'unpaid','2025-11-10','2025-12-01',22,'testing for new','test','approved',11,'2025-11-09 07:36:51','2025-11-09 07:37:08');
/*!40000 ALTER TABLE `official_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_account_users`
--

DROP TABLE IF EXISTS `payment_account_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_account_users` (
  `payment_account_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`payment_account_id`,`user_id`),
  KEY `payment_account_users_user_id_foreign` (`user_id`),
  CONSTRAINT `payment_account_users_payment_account_id_foreign` FOREIGN KEY (`payment_account_id`) REFERENCES `payment_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_account_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_account_users`
--

LOCK TABLES `payment_account_users` WRITE;
/*!40000 ALTER TABLE `payment_account_users` DISABLE KEYS */;
INSERT INTO `payment_account_users` VALUES (1,9),(1,11),(4,11),(5,11),(1,15),(1,16),(1,20),(4,20),(1,21),(4,21);
/*!40000 ALTER TABLE `payment_account_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_accounts`
--

DROP TABLE IF EXISTS `payment_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `balance` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_accounts`
--

LOCK TABLES `payment_accounts` WRITE;
/*!40000 ALTER TABLE `payment_accounts` DISABLE KEYS */;
INSERT INTO `payment_accounts` VALUES (1,'{\"ar\": \"الخزينة الرئيسية\", \"en\": \"main wallet\"}','{\"ar\": \"دي كل الناس بتدغع منها\", \"en\": \"all people can pay from\"}',954598,'2025-08-24 13:25:06','2025-11-09 07:12:22'),(3,'{\"ar\": \"الخزينة الفرعية\", \"en\": \"secondary wallet\"}','{\"en\": null}',0,'2025-08-24 13:29:51','2025-08-24 13:29:51'),(4,'{\"en\":\"private mohamed salah\",\"ar\":\"خاص م .محمد صلاح\"}','{\"en\":null}',0,'2025-08-28 11:34:44','2025-08-28 11:34:44'),(5,'{\"en\":\"hfis\",\"ar\":\"ahmed maalawi\"}','{\"en\":null}',0,'2025-09-25 07:00:26','2025-09-25 07:00:26');
/*!40000 ALTER TABLE `payment_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'admins','web','2025-11-09 08:55:55','2025-11-09 08:55:55'),(2,'iqamaTypes','web','2025-11-09 08:55:56','2025-11-09 08:55:56'),(3,'employees','web','2025-11-09 08:55:56','2025-11-09 08:55:56'),(4,'users','web','2025-11-09 08:55:56','2025-11-09 08:55:56'),(5,'companies','web','2025-11-09 08:55:57','2025-11-09 08:55:57'),(6,'stages','web','2025-11-09 08:55:57','2025-11-09 08:55:57'),(7,'paymentAccounts','web','2025-11-09 08:55:57','2025-11-09 08:55:57'),(8,'moderators','web','2025-11-09 08:55:58','2025-11-09 08:55:58'),(9,'leaves','web','2025-11-09 08:55:58','2025-11-09 08:55:58'),(10,'eos','web','2025-11-09 08:55:59','2025-11-09 08:55:59'),(11,'reports','web','2025-11-09 08:55:59','2025-11-09 08:55:59'),(12,'roles','web','2025-11-09 08:55:59','2025-11-09 08:55:59');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_general_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',15,'auth_token','2a997452bc77a7747ee1b9371c259a593ef8f9e39a808f03621bd39f83d835c3','[\"*\"]',NULL,NULL,'2025-11-04 09:45:35','2025-11-04 09:45:35'),(2,'App\\Models\\User',15,'auth_token','c573c608597aac2244be59505b2e8e6f90c6a912a93a3059d0b961a6834e1957','[\"*\"]',NULL,NULL,'2025-11-04 09:46:13','2025-11-04 09:46:13'),(3,'App\\Models\\User',15,'auth_token','f5f3bc2b79427a9b248cc21b5eb89bc5d3c466d727b5cd2c8a67f7f36d068361','[\"*\"]',NULL,NULL,'2025-11-04 09:54:12','2025-11-04 09:54:12'),(4,'App\\Models\\User',15,'auth_token','bc651e1edbd434ea351897ab95bda5cb94aea1de976d52436775140bdb64fb9e','[\"*\"]','2025-11-05 10:38:06',NULL,'2025-11-04 10:01:01','2025-11-05 10:38:06'),(5,'App\\Models\\User',15,'auth_token','1ac06ce1fd774e2bc2ca343ca8ec84bca0970e2c6353ab4015cac509e589d0d5','[\"*\"]',NULL,NULL,'2025-11-05 10:38:50','2025-11-05 10:38:50'),(7,'App\\Models\\User',15,'auth_token','71cace37dfd886b2988c436c59bebbcd635758917d06c66b04e4b1ff3967faff','[\"*\"]',NULL,NULL,'2025-11-06 12:02:51','2025-11-06 12:02:51'),(8,'App\\Models\\User',15,'auth_token','6ad4ca1c998b8017fe53dd9e94cd2ce02e1ca788a687410aca45cf5417a0da7e','[\"*\"]',NULL,NULL,'2025-11-06 12:04:28','2025-11-06 12:04:28'),(9,'App\\Models\\User',15,'auth_token','05c3e27a40d582625de657cabfc32c268d3f01945b87f80e104cd561f16afb07','[\"*\"]','2025-11-06 14:09:32',NULL,'2025-11-06 13:10:47','2025-11-06 14:09:32'),(10,'App\\Models\\User',15,'auth_token','fb4f4f7cdce060f32c8631788706fb29abae048f3de3f07803f83fa0f679b634','[\"*\"]','2025-11-08 06:58:11',NULL,'2025-11-08 06:57:58','2025-11-08 06:58:11'),(11,'App\\Models\\User',1,'auth_token','bb6088e8cbbe8ad705e0731a34b885c918e13cd55d908d2248dc1c05dd3a0a54','[\"*\"]','2025-11-08 11:57:33',NULL,'2025-11-08 07:01:16','2025-11-08 11:57:33'),(12,'App\\Models\\User',1,'auth_token','b825acb02c6253f0c485aa6952cee8f71cb6336ce6fbbbd088aa75d166fa7cfc','[\"*\"]',NULL,NULL,'2025-11-09 08:16:59','2025-11-09 08:16:59'),(13,'App\\Models\\User',1,'auth_token','23d583487a2bb2005b8804b033750f656a5ba165befa43f01689c6f0027b88a4','[\"*\"]',NULL,NULL,'2025-11-09 08:19:06','2025-11-09 08:19:06'),(14,'App\\Models\\User',1,'auth_token','93ffa768e33426f09ab9946b73ce3f3f3e060b44d6bbeadd506616aea3a06b62','[\"*\"]',NULL,NULL,'2025-11-09 08:19:25','2025-11-09 08:19:25'),(15,'App\\Models\\User',1,'auth_token','83d37e07c5ebe814c0e00e2dcda51517675a53c1a3f794b3194c0ef3f246ee3b','[\"*\"]',NULL,NULL,'2025-11-09 08:20:16','2025-11-09 08:20:16'),(16,'App\\Models\\User',1,'auth_token','1c765cda45118507a2bac7c587b1f2b7e6b5aca3114b8e394dacc3d3756ed8a1','[\"*\"]',NULL,NULL,'2025-11-09 08:20:56','2025-11-09 08:20:56'),(17,'App\\Models\\User',1,'auth_token','ae0389c79db7b66055b147793e450dca4582bf0baffdd4e69682f4edc4be988a','[\"*\"]',NULL,NULL,'2025-11-09 08:21:01','2025-11-09 08:21:01'),(18,'App\\Models\\User',1,'auth_token','81d693a4332e9aff0e8afe908871b050c77a136093a128e5aec301888b187ed2','[\"*\"]',NULL,NULL,'2025-11-09 08:23:51','2025-11-09 08:23:51'),(19,'App\\Models\\User',1,'auth_token','4b12894e0a4c12f7a4196cb92f88a356748aa37329806afdf047f3e954efa8ee','[\"*\"]',NULL,NULL,'2025-11-09 08:24:26','2025-11-09 08:24:26'),(20,'App\\Models\\User',1,'auth_token','9f2db056057eb3def16b09c635878f2c0adf92ef1ac04e56548d1e489ba96720','[\"*\"]',NULL,NULL,'2025-11-09 08:24:41','2025-11-09 08:24:41'),(21,'App\\Models\\User',1,'auth_token','1814590d02ca6d835ada38b2aefdad1ce09212f15e40dfe854c509d3b7892195','[\"*\"]',NULL,NULL,'2025-11-09 08:24:56','2025-11-09 08:24:56'),(22,'App\\Models\\User',1,'auth_token','a17c7d1f1f7f22ecada8a61a0ff9e927605b8086c917e84f049821789d1f670f','[\"*\"]','2025-11-09 08:51:22',NULL,'2025-11-09 08:25:25','2025-11-09 08:51:22'),(23,'App\\Models\\User',1,'auth_token','ba74bf355161e0312d55836004328e2f6d494d4adc4f9a7833cf40eda98bb7c0','[\"*\"]',NULL,NULL,'2025-11-09 08:41:39','2025-11-09 08:41:39'),(24,'App\\Models\\User',1,'auth_token','62c1057eb228d99750f30bdfbe35b4d1805f3fb1ab021c8a73bdd3ad3686d38b','[\"*\"]',NULL,NULL,'2025-11-09 08:48:18','2025-11-09 08:48:18'),(25,'App\\Models\\User',15,'auth_token','ff3573d3ed43a4cfc390bf3c210dd26eb81867a45b71d7bf5764948d5ba7c835','[\"*\"]',NULL,NULL,'2025-11-09 08:49:55','2025-11-09 08:49:55'),(26,'App\\Models\\User',15,'auth_token','6b04e309e444fb721113b129da14254d10e0b8ce4436108ff369d392f56c0282','[\"*\"]',NULL,NULL,'2025-11-09 08:54:00','2025-11-09 08:54:00'),(27,'App\\Models\\User',15,'auth_token','cfafdf6e0fd5dfd52480c9bdb3e516c1ad9f9bbe5bf6724c5f4782a7f5154feb','[\"*\"]',NULL,NULL,'2025-11-09 08:56:31','2025-11-09 08:56:31'),(28,'App\\Models\\User',15,'auth_token','02b581c548f53264fd25a52c4f3e27f6eea09da5005addb7605c8e7f0405e523','[\"*\"]','2025-11-09 12:03:36',NULL,'2025-11-09 08:59:05','2025-11-09 12:03:36'),(29,'App\\Models\\User',15,'auth_token','d0eb508102d41dfcba40c5b34796887d3834ca5a6152a5e7f213e7516a37fb5e','[\"*\"]',NULL,NULL,'2025-11-09 10:39:57','2025-11-09 10:39:57'),(30,'App\\Models\\User',1,'auth_token','11ca1bf69540dbbc4d38be09f5cc20aa0730a54c481b3a2cab56aa79f950f134','[\"*\"]',NULL,NULL,'2025-11-09 10:41:26','2025-11-09 10:41:26'),(31,'App\\Models\\User',1,'auth_token','5d9da2b3942654fc6ad39abc2d28df58fd015365e188b7906ebcfbd50ba92bdf','[\"*\"]',NULL,NULL,'2025-11-09 10:48:01','2025-11-09 10:48:01'),(32,'App\\Models\\User',1,'auth_token','974f2839ff20b2d6bdac5c3097b342e014f2f9296944b9c421336b94d46c8449','[\"*\"]','2025-11-09 12:09:59',NULL,'2025-11-09 10:48:34','2025-11-09 12:09:59'),(34,'App\\Models\\User',1,'auth_token','e30fbcefb7afb87eeba5bbfb7b61375d10a77c73f09a7d7194e8188d5a76590c','[\"*\"]','2025-11-09 12:09:22',NULL,'2025-11-09 12:04:25','2025-11-09 12:09:22'),(35,'App\\Models\\User',15,'auth_token','a0c9b10e20aff3d6d8cbbe2cd8d9d1277ce2da0682f898d5b1acd73e7ce9300b','[\"*\"]','2025-11-09 12:48:21',NULL,'2025-11-09 12:22:09','2025-11-09 12:48:21'),(36,'App\\Models\\User',15,'auth_token','3ce5deaa30b4a86302f958ffec9e6553553522bf5c937e7b97de95b93c23f77b','[\"*\"]','2025-11-09 12:42:51',NULL,'2025-11-09 12:23:29','2025-11-09 12:42:51'),(38,'App\\Models\\User',15,'auth_token','71463e0fdeeea07769823326f1f3857378156560d07dd58bbe0910a30dd10c01','[\"*\"]','2025-11-09 13:05:03',NULL,'2025-11-09 12:55:52','2025-11-09 13:05:03'),(40,'App\\Models\\User',15,'auth_token','a0f02d5fc8a7d1805d0f186f84bbe4a98f9336176f0ae798e6fbd4fda7dfedfc','[\"*\"]','2025-11-09 13:53:31',NULL,'2025-11-09 13:35:32','2025-11-09 13:53:31'),(41,'App\\Models\\User',20,'auth_token','9748cba91bbd5c540a9c4d97049affb922008f07a167c78d4422c313376d125f','[\"*\"]',NULL,NULL,'2025-11-09 13:51:53','2025-11-09 13:51:53'),(42,'App\\Models\\User',1,'auth_token','a7481a34ea404a19f4077e7dec13d7b19f4bbd61f6b8d82e9b7c5057a8a88c00','[\"*\"]',NULL,NULL,'2025-11-09 13:52:18','2025-11-09 13:52:18'),(43,'App\\Models\\User',20,'auth_token','23e2f9a0c2e95348a1ba1bf1335e9f866205011a9848d23cca7f8560897a62a7','[\"*\"]',NULL,NULL,'2025-11-09 13:52:25','2025-11-09 13:52:25'),(44,'App\\Models\\User',20,'auth_token','26b2e34d576c23207b3fcd9b8ab832be590abb8714554476c3bca0d65fda2472','[\"*\"]','2025-11-09 13:56:44',NULL,'2025-11-09 13:54:09','2025-11-09 13:56:44');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(2,3),(3,3),(6,3),(11,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super-admin','web','2025-11-09 08:55:55','2025-11-09 08:55:55'),(2,'admin','web','2025-11-09 08:55:55','2025-11-09 08:55:55'),(3,'moderator','web','2025-11-09 08:55:55','2025-11-09 08:55:55');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salaries`
--

DROP TABLE IF EXISTS `salaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `salaries_user_id_month_unique` (`user_id`,`month`),
  KEY `salaries_month_status_user_id_index` (`month`,`status`,`user_id`),
  CONSTRAINT `salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salaries`
--

LOCK TABLES `salaries` WRITE;
/*!40000 ALTER TABLE `salaries` DISABLE KEYS */;
INSERT INTO `salaries` VALUES (1,7,5000.00,'2025-11','pending',NULL,'2025-11-09 06:52:11','2025-11-09 06:52:11'),(2,8,5000.00,'2025-11','pending',NULL,'2025-11-09 06:52:11','2025-11-09 06:52:11'),(3,9,5000.00,'2025-11','pending',NULL,'2025-11-09 06:52:11','2025-11-09 06:52:11'),(4,10,5000.00,'2025-11','paid','2025-11-09 07:12:22','2025-11-09 06:52:12','2025-11-09 07:12:22');
/*!40000 ALTER TABLE `salaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('aNWiT3uoQPEuP1g1MOk4rgecJPihUpF0sDX3KBE8',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibHBpVUVrZk84TjRZVzFkMWdlSUJ4ck9yUlVDMGtQcmlLaFVic2NpcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly9lZ3RpYXoudGVzdC9hZG1pbi9oci9zYWxhcmllcy9jb21wYW55LWJhbGFuY2UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1762616009),('DBXK3kjmQMVnreDn9U6fCdCgoiKimNy8t4yZxY0M',NULL,'192.168.1.54','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0laaXFwdlY0VDJmYWh0Q2J6eDFwUkZ5WUFpZjdMODR4U1loWXlBeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xOTIuMTY4LjEuNDI6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762684620),('odZXO5Bj5uLNMvxdxu394PyOSCitYfsLskUzw8AQ',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoid215R3N0ZTNjcVVWTk9wQkR3NmhlT0ZvbEVHelJ6MWRzRjlRVUhuZyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMwOiJodHRwOi8vZWd0aWF6LnRlc3QvYWRtaW4vcm9sZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MjI6IlBIUERFQlVHQkFSX1NUQUNLX0RBVEEiO2E6MDp7fX0=',1762701608),('OLkIxepWft26LTKojPvUb4rY8pTS590VwJPLFGhE',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHk2bThuZlpQNzJ0dlA3UUVLSnBTelhYaUlIblQ0MDJzNU1hSGhJdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9lZ3RpYXoudGVzdC9hZG1pbi9yb2xlcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1762685935),('ySp1gizvLm2lRMZi9jZFnRSqjw3l3sD9TBzuVoZ1',15,'192.168.1.54','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWjUxWHlTc3c0TnE0OVJ3cFduRnpOdkFtempEZ3dwZ1lidjJ2N1pwbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xOTIuMTY4LjEuNDI6ODAwMC9hZG1pbi9lbXBsb3llZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxNTtzOjIyOiJQSFBERUJVR0JBUl9TVEFDS19EQVRBIjthOjA6e319',1762700065);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stages`
--

DROP TABLE IF EXISTS `stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `iqama_type_id` bigint unsigned NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `order` smallint NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `cost` decimal(6,2) DEFAULT '0.00',
  `estimated_time_in_days` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stages_iqama_type_id_foreign` (`iqama_type_id`),
  CONSTRAINT `stages_iqama_type_id_foreign` FOREIGN KEY (`iqama_type_id`) REFERENCES `iqama_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stages`
--

LOCK TABLES `stages` WRITE;
/*!40000 ALTER TABLE `stages` DISABLE KEYS */;
INSERT INTO `stages` VALUES (2,3,'{\"ar\": \"تحضير الورق\", \"en\": \"paper preparation\"}','{\"ar\": \"dkjhgf adksjgh sdgoih\", \"en\": \"dkjhgf adksjgh sdgoih\"}',1,500,0.00,30,'stages/u1NCbtg3Xh4Ig64g3siQE9LynfjUJzCaczE5nGQq.jpg',NULL,'stages/4nN9AS1HAUQbmPrwslbBmsShS0kY6CCCz6DgroWK.pdf','2025-08-19 10:19:30','2025-08-20 11:12:50'),(3,1,'{\"ar\": \"تحاليل طبية\", \"en\": \"medical examinations\"}','{\"ar\": \"يلس شيس سسايب\", \"en\": \"sdgd fjhd dfkh fsdb\"}',1,600,0.00,NULL,'stages/FlXBrJZVnlPdHrHTuhizoatq9Cg09QT510XELnlF.jpg',NULL,'stages/u4lDP2W0a01n3x9lzq232KroEWmGP2kSUDqxsnS9.pdf','2025-08-19 11:07:10','2025-08-19 11:07:10'),(4,1,'{\"ar\": \"رخصة القيادة\", \"en\": \"Driving license\"}','{\"ar\": \"rsdhjdjyf\", \"en\": \"fdhdjfy\"}',1,50,0.00,NULL,NULL,NULL,NULL,'2025-08-20 10:23:41','2025-08-20 10:23:41'),(7,3,'{\"ar\": \"استخراج التامينات\", \"en\": \"Extracting insurance\"}','{\"ar\": \"Inventore provident\", \"en\": \"Reiciendis cillum ve\"}',2,526,0.00,21,NULL,NULL,NULL,'2025-08-20 11:12:42','2025-08-20 11:13:07'),(8,6,'{\"ar\":\"step 1\",\"en\":\"step 1\"}','{\"ar\":\"step 1 description\",\"en\":\"step 1 description\"}',1,100,250.50,5,NULL,NULL,NULL,'2025-09-25 08:15:07','2025-09-25 08:28:52');
/*!40000 ALTER TABLE `stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_companies`
--

DROP TABLE IF EXISTS `transaction_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_companies`
--

LOCK TABLES `transaction_companies` WRITE;
/*!40000 ALTER TABLE `transaction_companies` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_payment_account_id` bigint unsigned NOT NULL,
  `to_wallet_id` bigint unsigned DEFAULT NULL,
  `type` enum('stage_payment','salary_payment','refund','charge') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stage_payment',
  `transactionable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transactionable_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `payment_account_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `from_balance_before` decimal(8,2) NOT NULL,
  `from_balance_after` decimal(8,2) NOT NULL,
  `status` enum('pending','completed','failed','refund','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_transaction_id_unique` (`transaction_id`),
  KEY `transactions_from_payment_account_id_foreign` (`from_payment_account_id`),
  KEY `transactions_to_wallet_id_foreign` (`to_wallet_id`),
  KEY `transactions_transactionable_type_transactionable_id_index` (`transactionable_type`,`transactionable_id`),
  KEY `transactions_payment_account_id_foreign` (`payment_account_id`),
  KEY `transactions_transactionable_id_transactionable_type_index` (`transactionable_id`,`transactionable_type`),
  KEY `transactions_type_status_index` (`type`,`status`),
  KEY `transactions_user_id_status_index` (`user_id`,`status`),
  KEY `transactions_created_by_created_at_index` (`created_by`,`created_at`),
  KEY `transactions_transaction_id_index` (`transaction_id`),
  KEY `transactions_created_at_index` (`created_at`),
  CONSTRAINT `transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_from_payment_account_id_foreign` FOREIGN KEY (`from_payment_account_id`) REFERENCES `payment_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_payment_account_id_foreign` FOREIGN KEY (`payment_account_id`) REFERENCES `payment_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_to_wallet_id_foreign` FOREIGN KEY (`to_wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'2311e8d3-2ae6-4126-ad92-4766a42e9d89',1,NULL,'salary_payment','App\\Models\\Salary',1,7,1,11,5000.00,980248.00,975248.00,'completed','Salary payment for 2025-01 - Iona Petty',NULL,'2025-10-04 06:36:15','2025-10-04 06:36:15','2025-10-04 06:36:15'),(2,'abd5e9c2-a30e-4479-8117-5790661e07cd',1,NULL,'salary_payment','App\\Models\\Salary',2,8,1,11,5000.00,975248.00,970248.00,'completed','Salary payment for 2025-01 - Wing Moore',NULL,'2025-10-04 06:41:27','2025-10-04 06:41:27','2025-10-04 06:41:27'),(3,'898325c4-211c-4078-9f45-81bcad3a2adf',1,NULL,'salary_payment','App\\Models\\Salary',3,9,1,11,5000.00,970248.00,965248.00,'completed','Salary payment for 2025-01 - Eagan Dean',NULL,'2025-10-04 06:41:27','2025-10-04 06:41:27','2025-10-04 06:41:27'),(4,'afdc275c-b2dc-4f11-a16a-b2abd914490b',1,NULL,'salary_payment','App\\Models\\Salary',9,7,1,11,5000.00,965248.00,960248.00,'completed','Salary payment for 2025-02 - Iona Petty',NULL,'2025-10-04 08:02:36','2025-10-04 08:02:36','2025-10-04 08:02:36'),(5,'9dd933a8-c301-4ae4-aa69-a29969b20190',1,NULL,'stage_payment','App\\Models\\EmployeeStage',1,15,1,15,600.00,960248.00,959648.00,'completed','Stage payment for medical examinations - Employee: Aquila Mitchell',NULL,'2025-11-03 12:01:17','2025-11-03 12:01:17','2025-11-03 12:01:17'),(6,'e2e4700f-4a51-4199-b28a-e721a15b150f',1,NULL,'stage_payment','App\\Models\\EmployeeStage',2,15,1,15,50.00,959648.00,959598.00,'completed','Stage payment for Driving license - Employee: Aquila Mitchell',NULL,'2025-11-03 12:13:40','2025-11-03 12:13:40','2025-11-03 12:13:40'),(7,'7123d36e-6cab-4cbd-a1d3-8227d391ec9b',1,NULL,'salary_payment','App\\Models\\Salary',4,10,1,11,5000.00,959598.00,954598.00,'completed','Salary payment for 2025-11 - سارة جونسون',NULL,'2025-11-09 07:12:21','2025-11-09 07:12:21','2025-11-09 07:12:21');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `salary` decimal(8,2) NOT NULL DEFAULT '0.00',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `fcm_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `moderator_company_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_moderator_company_id_foreign` (`moderator_company_id`),
  CONSTRAINT `users_moderator_company_id_foreign` FOREIGN KEY (`moderator_company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@egtiaz.com',0.00,NULL,'$2y$12$pq.yFlT20/5WCUJUpewEa.g2J/f3WjQ4cea52QEtf62.WWsc8TBwq',NULL,'2025-08-17 12:21:08','2025-08-28 10:49:45','users/68ad775f21646.png','active',NULL,NULL),(7,'Iona Petty','hapo@mailinator.com',5000.00,NULL,'$2y$12$hdlHiaxc/bzGccCsCjhvEeld0qAW1aMaZ529MHUr7kBJVlnbAmBFC',NULL,'2025-08-25 13:33:45','2025-08-26 05:59:11','users/68ad775f21646.png','active',NULL,NULL),(8,'Wing Moore','gagugu@mailinator.com',5000.00,NULL,'$2y$12$E4Byxw1yIUJ8VCLs3jxqDOMf2dO8auHWpxVgY4yuusy83tODOLFva',NULL,'2025-08-25 13:33:57','2025-08-25 13:33:57','users/68ac9074d8b4f.png','active',NULL,NULL),(9,'Eagan Dean','cahywisuf@mailinator.com',5000.00,NULL,'$2y$12$9NG1uBsSFzbfrxbGleo6o.wQJ/92MpTbAS/IYzlBsVjUMvQbfuho.',NULL,'2025-08-26 05:59:01','2025-08-26 05:59:01','users/68ad77541553b.png','active',NULL,NULL),(10,'سارة جونسون','sarah.moderators@technova-solutions.com',5000.00,NULL,'$2y$12$pq.yFlT20/5WCUJUpewEa.g2J/f3WjQ4cea52QEtf62.WWsc8TBwq',NULL,'2025-10-01 09:43:29','2025-10-01 09:43:31','users/68ad775f21646.png','active',NULL,NULL),(11,'أحمد المنصوري','ahmed.moderation@quantum-innovations.tech',5000.00,NULL,'$2y$12$pq.yFlT20/5WCUJUpewEa.g2J/f3WjQ4cea52QEtf62.WWsc8TBwq',NULL,'2025-10-01 09:43:29','2025-11-03 11:06:15','admins/6908941344bf1.png','active',NULL,NULL),(12,'إيميلي تشين','emily.community@ecofuture-dynamics.com',5000.00,NULL,'$2y$12$pq.yFlT20/5WCUJUpewEa.g2J/f3WjQ4cea52QEtf62.WWsc8TBwq',NULL,'2025-10-01 09:43:29','2025-10-01 09:43:31','users/68ad775f21646.png','active',NULL,NULL),(13,'محمد حسن','mohammed.admin@neuralink-systems.ai',5000.00,NULL,'$2y$12$pq.yFlT20/5WCUJUpewEa.g2J/f3WjQ4cea52QEtf62.WWsc8TBwq',NULL,'2025-10-01 09:43:29','2025-10-01 09:43:31','users/68ad775f21646.png','active',NULL,NULL),(14,'جينيفر مارتينيز','jennifer.moderators@cybershield-security.net',5000.00,NULL,'$2y$12$pq.yFlT20/5WCUJUpewEa.g2J/f3WjQ4cea52QEtf62.WWsc8TBwq',NULL,'2025-10-01 09:43:29','2025-10-01 09:43:31','users/68ad775f21646.png','active',NULL,NULL),(15,'moderator1','moderator.egtiaz@gmail.com',0.00,NULL,'$2y$12$qOU6dvunCnp1gJeRQztgtepg4uqBsDXk3HmYNcvh97xWRfQTWatGW',NULL,'2025-11-03 09:34:52','2025-11-03 11:32:30','users/6908aeee170c1.png','active',NULL,4),(16,'test moderator','test.moderator@gmail.com',0.00,NULL,'$2y$12$neLxtM2QAtZC7DlWP1YlluY602d.KC54L4ePNOryUqitFSk2obUJ6',NULL,'2025-11-03 11:24:56','2025-11-03 11:29:42','users/6908ae46bc17c.png','active',NULL,4),(20,'Ahmed Nasser','ahmed.nasser@example.com',0.00,NULL,'$2y$12$pq.yFlT20/5WCUJUpewEa.g2J/f3WjQ4cea52QEtf62.WWsc8TBwq',NULL,'2025-11-09 12:09:24','2025-11-09 12:09:24','users/6910a09364b97.png','active',NULL,NULL),(21,'Ahmed Nasser','ahsmed.nasser@example.com',0.00,NULL,'$2y$12$pq.yFlT20/5WCUJUpewEa.g2J/f3WjQ4cea52QEtf62.WWsc8TBwq',NULL,'2025-11-09 12:10:00','2025-11-09 12:10:00','users/6910a0b7c6517.png','active',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merchant_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `status` enum('pending','completed','failed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_link` text COLLATE utf8mb4_unicode_ci,
  `qr_code` text COLLATE utf8mb4_unicode_ci,
  `ndc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallet_transactions_payment_id_unique` (`payment_id`),
  UNIQUE KEY `wallet_transactions_merchant_transaction_id_unique` (`merchant_transaction_id`),
  KEY `wallet_transactions_payment_id_index` (`payment_id`),
  KEY `wallet_transactions_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_transactions`
--

LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
INSERT INTO `wallet_transactions` VALUES (1,15,'32225be6-3de2-483e-beee-509e1342f7cc','WALLET_15_1762696400_69109cd0b99a4',50.00,'SAR','pending','https://eu-test.oppwa.com/paymentlink/32225be6-3de2-483e-beee-509e1342f7cc',NULL,NULL,NULL,NULL,'2025-11-09 11:53:21','2025-11-09 11:53:21'),(2,15,'6464bbb6-64d1-4320-af9c-5bcdbe1e913f','WALLET_15_1762698235_6910a3fb4c350',50.00,'SAR','pending','https://eu-test.oppwa.com/paymentlink/6464bbb6-64d1-4320-af9c-5bcdbe1e913f',NULL,NULL,NULL,NULL,'2025-11-09 12:23:56','2025-11-09 12:23:56'),(3,15,'b0ede445-a34a-474c-9ddf-8053afaf8288','WALLET_15_1762699372_6910a86c4d94e',50.00,'SAR','failed','https://eu-test.oppwa.com/paymentlink/b0ede445-a34a-474c-9ddf-8053afaf8288',NULL,NULL,'{\"ndc\": \"8ac7a4ca841c938901841dfa0f9303d6_54e243a02eeb494cbdcf140f6c632722\", \"result\": {\"code\": \"200.300.404\", \"description\": \"invalid or missing parameter - (opp) No payment session found for the requested id - are you mixing test/live servers or have you paid more than 30min ago?\"}, \"timestamp\": \"2025-11-09 14:43:48+0000\", \"buildNumber\": \"8d30e9c3e15c81a3c7238175470bd4407b99090f@2025-11-07 16:53:38 +0000\"}',NULL,'2025-11-09 12:42:52','2025-11-09 12:43:48'),(4,15,'7aa458b9-0cf8-4650-822e-dcc5780d7598','WALLET_15_1762703137_6910b7211ae19',1.00,'SAR','failed','https://eu-test.oppwa.com/paymentlink/7aa458b9-0cf8-4650-822e-dcc5780d7598',NULL,NULL,'{\"ndc\": \"8ac7a4ca841c938901841dfa0f9303d6_f7cef4b4e50f4869ba69610296de9ec6\", \"result\": {\"code\": \"200.300.404\", \"description\": \"invalid or missing parameter - (opp) No payment session found for the requested id - are you mixing test/live servers or have you paid more than 30min ago?\"}, \"timestamp\": \"2025-11-09 15:49:00+0000\", \"buildNumber\": \"8d30e9c3e15c81a3c7238175470bd4407b99090f@2025-11-07 16:53:38 +0000\"}',NULL,'2025-11-09 13:45:37','2025-11-09 13:49:00'),(5,15,'aa2a1dc8-0535-4731-8df9-c9eec720ad7b','WALLET_15_1762703612_6910b8fc27ad3',1.00,'SAR','pending','https://eu-test.oppwa.com/paymentlink/aa2a1dc8-0535-4731-8df9-c9eec720ad7b',NULL,NULL,NULL,NULL,'2025-11-09 13:53:32','2025-11-09 13:53:32');
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `balance` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallets_company_id_foreign` (`company_id`),
  CONSTRAINT `wallets_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES (3,4,150,'2025-08-19 10:54:53','2025-11-03 12:13:40'),(4,5,0,'2025-08-23 07:10:35','2025-08-23 07:10:35'),(5,6,0,'2025-08-23 07:10:55','2025-08-23 07:10:55'),(6,7,0,'2025-08-23 07:11:14','2025-08-23 07:11:14'),(7,9,10000,'2025-10-01 10:31:43','2025-10-01 10:31:43'),(8,8,989250,'2025-10-01 10:31:43','2025-10-04 05:37:09');
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-09 17:57:41
