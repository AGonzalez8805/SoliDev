-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: SoliDev
-- ------------------------------------------------------
-- Server version	9.3.0

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
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `content` longtext NOT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `cover_image` varchar(255) DEFAULT NULL,
  `allow_comments` tinyint(1) DEFAULT '1',
  `featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excerpt` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_ibfk_1` (`author_id`),
  CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`users_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
INSERT INTO `blog` VALUES (2,4,'Les nouveautés CSS en 2024 : container queries, subgrid et plus encore','css','<p>&lt;h2&gt;Introduction&lt;/h2&gt;</p><p>&lt;p&gt;Le CSS évolue constamment pour répondre aux besoins des développeurs front-end. L’année 2024 marque une étape importante avec l’adoption de fonctionnalités attendues depuis longtemps.&lt;/p&gt;</p><p><br></p><p>&lt;h2&gt;Container Queries&lt;/h2&gt;</p><p>&lt;p&gt;Les &lt;strong&gt;container queries&lt;/strong&gt; permettent d’appliquer des styles en fonction de la taille du conteneur parent plutôt que de la fenêtre du navigateur. Cela donne plus de flexibilité pour créer des composants réellement réutilisables.&lt;/p&gt;</p><p><br></p><p>&lt;pre&gt;&lt;code class=\"language-css\"&gt;</p><p>.card {</p><p>&nbsp; container-type: inline-size;</p><p>}</p><p><br></p><p>@container (min-width: 500px) {</p><p>&nbsp; .card .details {</p><p>&nbsp; &nbsp; display: flex;</p><p>&nbsp; &nbsp; gap: 1rem;</p><p>&nbsp; }</p><p>}</p><p>&lt;/code&gt;&lt;/pre&gt;</p><p><br></p><p>&lt;h2&gt;Subgrid&lt;/h2&gt;</p><p>&lt;p&gt;La propriété &lt;code&gt;subgrid&lt;/code&gt; est désormais largement supportée. Elle permet à un élément enfant d’hériter de la grille définie par son parent, facilitant la création de mises en page complexes.&lt;/p&gt;</p><p><br></p><p>&lt;pre&gt;&lt;code class=\"language-css\"&gt;</p><p>.parent {</p><p>&nbsp; display: grid;</p><p>&nbsp; grid-template-columns: 1fr 2fr 1fr;</p><p>}</p><p><br></p><p>.child {</p><p>&nbsp; display: grid;</p><p>&nbsp; grid-template-columns: subgrid;</p><p>}</p><p>&lt;/code&gt;&lt;/pre&gt;</p><p><br></p><p>&lt;h2&gt;Nouvelles pseudo-classes&lt;/h2&gt;</p><p>&lt;p&gt;De nouvelles pseudo-classes comme &lt;code&gt;:has()&lt;/code&gt; simplifient l’écriture de sélecteurs conditionnels :&lt;/p&gt;</p><p><br></p><p>&lt;pre&gt;&lt;code class=\"language-css\"&gt;</p><p>article:has(img) {</p><p>&nbsp; border: 1px solid #ccc;</p><p>&nbsp; padding: 1rem;</p><p>}</p><p>&lt;/code&gt;&lt;/pre&gt;</p><p><br></p><p>&lt;h2&gt;Conclusion&lt;/h2&gt;</p><p>&lt;p&gt;Ces nouveautés ouvrent la voie à des pratiques de design plus modulaires et maintenables. Si vous travaillez sur des interfaces complexes, prenez le temps de tester ces fonctionnalités, elles vont transformer votre façon d’écrire du CSS.&lt;/p&gt;</p><div><br></div>','published',NULL,1,0,'2025-09-25 18:03:01','2025-09-25 18:03:01','En 2024, le langage CSS continue d’évoluer rapidement avec l’arrivée de fonctionnalités puissantes comme les container queries, le support natif de subgrid et de nouvelles pseudo-classes. Dans cet article, découvrez comment ces nouveautés peuvent simplifier la création de layouts modernes et responsives.');
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_activities`
--

DROP TABLE IF EXISTS `user_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_activities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` enum('snippet','comment','project','like','other') NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`users_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_activities`
--

LOCK TABLES `user_activities` WRITE;
/*!40000 ALTER TABLE `user_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `users_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','utilisateur') DEFAULT 'utilisateur',
  `registrationDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `github_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `bio` text,
  `skills` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`users_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Gonzalez','Amelie','solidev.dev@gmail.com',NULL,'$2y$12$hbISvWysMQkH.c7pPPtap.M/BwCJDaQURW30zeL3sVD9YS3rGh1gG','admin','2025-09-06 16:04:43',NULL,NULL,NULL,NULL,NULL),(2,'Bois','Julie','julie.bois@mail.com',NULL,'$2y$10$SEjwQKBb4aYViFxADQB6gORKhfNNgflFOZ0XZEZNFZ67BoKIDJaAu','utilisateur','2025-09-06 22:17:34',NULL,NULL,NULL,NULL,NULL),(3,'Martin','Luc','luc.martin@mail.fr',NULL,'$2y$10$CgoRWZz8nE7PSdiok.2M0u6bEWHAXcf30PuzZoNwQBWc4QiWI4mS.','utilisateur','2025-09-07 22:42:41',NULL,NULL,NULL,NULL,NULL),(4,'ziani','safia','thoklo@hotmail.fr','user_4_68d5194fbdbb6.jpg','$2y$10$kaSCPFMaiL6mcUh5Fjpc5eRPc0LmodQs98VSsJ4gy5wyWMbDnHIqO','utilisateur','2025-09-21 16:01:12','','','','Je suis là pour tester la plateforme ',''),(5,'petit','lucas','lucas.petit@mail.com','user_5_68d52e4f57216.jpg','$2y$10$RCuHrvGzxcjYzrOA6nipwe5YZDcJSJrPGPLeaQfJngjfpGmRFUE1K','utilisateur','2025-09-25 11:57:15','','','','','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-26 15:14:24