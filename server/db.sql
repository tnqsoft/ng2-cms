/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.7.16-0ubuntu0.16.04.1 : Database - ng2cms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ng2cms` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ng2cms`;

/*Table structure for table `migration_versions` */

DROP TABLE IF EXISTS `migration_versions`;

CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `migration_versions` */

insert  into `migration_versions`(`version`) values ('20160923141633');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_timeout` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`email`,`password`,`reset_token`,`reset_timeout`,`is_active`,`created_at`,`updated_at`,`roles`) values (1,'customer','customer@tnqsoft.com','$2y$13$X5hdan/8sMmpNCEmuB/jRupJmhVYgKCQOchJDIMhw01yVzYr5wU9G',NULL,NULL,1,'2017-01-06 11:55:38',NULL,'[\"ROLE_USER\"]'),(2,'admin','admin@tnqsoft.com','$2y$13$Q67dKiF6eRrGvwMJ9bJDQut1zMK/YjgjwpJqkUmL4/1Df8GahPLRq',NULL,NULL,1,'2017-01-06 11:55:38',NULL,'[\"ROLE_SUPER_ADMIN\"]');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
