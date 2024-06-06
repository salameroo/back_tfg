-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para cargram
/*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `railway`;

-- Volcando estructura para tabla cargram.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_post_id_foreign` (`post_id`),
  KEY `comments_user_id_foreign` (`user_id`),
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.comments: ~0 rows (aproximadamente)

-- Volcando estructura para tabla cargram.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla cargram.follows
CREATE TABLE IF NOT EXISTS `follows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `followed_user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `follows_user_id_foreign` (`user_id`),
  KEY `follows_followed_user_id_foreign` (`followed_user_id`),
  CONSTRAINT `follows_followed_user_id_foreign` FOREIGN KEY (`followed_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `follows_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.follows: ~8 rows (aproximadamente)
INSERT INTO `follows` (`id`, `user_id`, `followed_user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, NULL, NULL),
	(2, 2, 1, NULL, NULL),
	(3, 2, 2, NULL, NULL),
	(4, 3, 1, NULL, NULL),
	(5, 5, 1, NULL, NULL),
	(6, 6, 1, NULL, NULL),
	(7, 7, 1, NULL, NULL),
	(8, 1, 7, NULL, NULL),
	(9, 7, 6, NULL, NULL),
	(10, 10, 1, NULL, NULL);

-- Volcando estructura para tabla cargram.images
CREATE TABLE IF NOT EXISTS `images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `images_post_id_foreign` (`post_id`),
  CONSTRAINT `images_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.images: ~25 rows (aproximadamente)
INSERT INTO `images` (`id`, `post_id`, `url`, `created_at`, `updated_at`) VALUES
	(8, 4, '/storage/post_1717155687_6659b7671e483.webp', '2024-05-31 09:41:27', '2024-05-31 09:41:27'),
	(13, 4, '/storage/post_1717399946_665d718a1ea78.webp', '2024-06-03 05:32:26', '2024-06-03 05:32:26'),
	(15, 6, '/storage/post_1717400194_665d72820eba9.webp', '2024-06-03 05:36:34', '2024-06-03 05:36:34'),
	(16, 7, '/storage/post_1717400329_665d7309de732.webp', '2024-06-03 05:38:50', '2024-06-03 05:38:50'),
	(17, 8, '/storage/post_1717411310_665d9deed7d55.webp', '2024-06-03 08:41:51', '2024-06-03 08:41:51'),
	(18, 9, '/storage/post_1717415959_665db0177acc8.webp', '2024-06-03 09:59:19', '2024-06-03 09:59:19'),
	(19, 10, '/storage/post_1717416172_665db0ec6ccd0.webp', '2024-06-03 10:02:52', '2024-06-03 10:02:52'),
	(20, 11, '/storage/post_1717416371_665db1b36193c.webp', '2024-06-03 10:06:11', '2024-06-03 10:06:11'),
	(21, 12, '/storage/post_1717416477_665db21dcef6e.webp', '2024-06-03 10:07:58', '2024-06-03 10:07:58'),
	(22, 16, '/storage/post_1717417082_665db47aa959c.webp', '2024-06-03 10:18:02', '2024-06-03 10:18:02'),
	(23, 17, '/storage/post_1717417141_665db4b5be171.webp', '2024-06-03 10:19:02', '2024-06-03 10:19:02'),
	(24, 18, '/storage/post_1717417470_665db5fe3c084.webp', '2024-06-03 10:24:30', '2024-06-03 10:24:30'),
	(25, 19, '/storage/post_1717417532_665db63c1e272.webp', '2024-06-03 10:25:32', '2024-06-03 10:25:32'),
	(26, 19, '/storage/post_1717417532_665db63c52e86.webp', '2024-06-03 10:25:32', '2024-06-03 10:25:32'),
	(27, 20, '/storage/post_1717417676_665db6cc20cf4.webp', '2024-06-03 10:27:56', '2024-06-03 10:27:56'),
	(28, 21, '/storage/post_1717417789_665db73d97fc2.webp', '2024-06-03 10:29:49', '2024-06-03 10:29:49'),
	(29, 22, '/storage/post_1717417867_665db78b3fa09.webp', '2024-06-03 10:31:07', '2024-06-03 10:31:07'),
	(30, 23, '/storage/post_1717417983_665db7ffe9986.webp', '2024-06-03 10:33:04', '2024-06-03 10:33:04'),
	(31, 24, '/storage/post_1717418724_665dbae460a90.webp', '2024-06-03 10:45:24', '2024-06-03 10:45:24'),
	(32, 25, '/storage/post_1717418765_665dbb0d02d7b.webp', '2024-06-03 10:46:05', '2024-06-03 10:46:05'),
	(33, 25, '/storage/post_1717418765_665dbb0d2fe9d.webp', '2024-06-03 10:46:05', '2024-06-03 10:46:05'),
	(34, 26, '/storage/post_1717418838_665dbb56df053.webp', '2024-06-03 10:47:19', '2024-06-03 10:47:19'),
	(35, 26, '/storage/post_1717418839_665dbb570d89e.webp', '2024-06-03 10:47:19', '2024-06-03 10:47:19'),
	(36, 26, '/storage/post_1717418839_665dbb5732d93.webp', '2024-06-03 10:47:19', '2024-06-03 10:47:19'),
	(37, 27, '/storage/post_1717418927_665dbbaf7d581.webp', '2024-06-03 10:48:47', '2024-06-03 10:48:47'),
	(38, 28, '/storage/post_1717418997_665dbbf548c0d.webp', '2024-06-03 10:49:57', '2024-06-03 10:49:57'),
	(39, 29, '/storage/post_1717419031_665dbc173da0c.webp', '2024-06-03 10:50:31', '2024-06-03 10:50:31'),
	(40, 30, '/storage/post_1717591499_66605dcbc9f2d.webp', '2024-06-05 10:44:59', '2024-06-05 10:44:59');

-- Volcando estructura para tabla cargram.likes
CREATE TABLE IF NOT EXISTS `likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `likes_post_id_foreign` (`post_id`),
  KEY `likes_user_id_foreign` (`user_id`),
  CONSTRAINT `likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.likes: ~4 rows (aproximadamente)
INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(2, 7, 1, '2024-06-03 08:41:01', '2024-06-03 08:41:01'),
	(3, 8, 1, '2024-06-03 08:42:04', '2024-06-03 08:42:04'),
	(4, 8, 3, '2024-06-03 10:03:27', '2024-06-03 10:03:27'),
	(5, 9, 6, '2024-06-03 10:28:13', '2024-06-03 10:28:13'),
	(6, 8, 6, '2024-06-03 10:28:15', '2024-06-03 10:28:15'),
	(7, 30, 10, '2024-06-05 10:45:14', '2024-06-05 10:45:14');

-- Volcando estructura para tabla cargram.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.messages: ~5 rows (aproximadamente)
INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'aowdubhaowidk', '2024-06-03 10:55:17', '2024-06-03 10:55:17'),
	(2, 1, 7, 'Hola tio que tal ?', '2024-06-04 06:13:18', '2024-06-04 06:13:18'),
	(3, 1, 7, 'axasczs', '2024-06-04 06:45:03', '2024-06-04 06:45:03'),
	(4, 7, 1, 'Pues la verdad que bastante bien no me puedo quejar', '2024-06-04 09:36:58', '2024-06-04 09:36:58'),
	(5, 7, 1, 'Buenos dias por la mañanaaa', '2024-06-04 10:26:25', '2024-06-04 10:26:25');

-- Volcando estructura para tabla cargram.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.migrations: ~5 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2024_05_09_082919_create_users_table', 1),
	(6, '2024_05_09_120428_create_posts_table', 1),
	(7, '2024_05_16_115146_create_follows_table', 1),
	(8, '2024_05_16_212825_create_images_table', 1),
	(9, '2024_05_18_200040_create_likes_table', 1),
	(10, '2024_05_18_200052_create_comments_table', 1),
	(11, '2024_05_20_074242_add_latitude_and_longitude_to_posts_table', 1),
	(12, '2024_05_20_112317_add_profile_image_to_users_table', 1),
	(13, '2024_05_24_062526_create_messages_table', 1),
	(14, '2024_05_29_112408_add_profile_photo_to_users_table', 1);

-- Volcando estructura para tabla cargram.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla cargram.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.personal_access_tokens: ~1 rows (aproximadamente)
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(10, 'App\\Models\\User', 4, 'authToken', '2701e00e0b0f1f4289feb2be38f6fb1a82c67545536670ea3eac0d930b70eb99', '["*"]', '2024-06-03 10:05:08', NULL, '2024-06-03 10:05:05', '2024-06-03 10:05:08'),
	(19, 'App\\Models\\User', 7, 'authToken', '3d775066818fb09326e0fb35f742c9a7eb800d91f7378bcc09f2809a4a91c16b', '["*"]', '2024-06-05 08:42:02', NULL, '2024-06-04 06:45:50', '2024-06-05 08:42:02');

-- Volcando estructura para tabla cargram.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `tag_ppl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `aliasLocation` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.posts: ~19 rows (aproximadamente)
INSERT INTO `posts` (`id`, `user_id`, `title`, `description`, `tag_ppl`, `location`, `created_at`, `updated_at`, `latitude`, `longitude`, `aliasLocation`) VALUES
	(4, 1, 'pues nada un bugatis', 'Asdasd', '@angel_salamero', NULL, '2024-06-03 05:32:26', '2024-06-03 05:32:26', 40.41604466, -3.70445251, NULL),
	(6, 1, 'Audi bate records!!', 'sdpsad', '@', 'Lat: 41.66662859024897, Lng: -1.0426712036132815', '2024-06-03 05:36:33', '2024-06-03 05:36:33', 41.66662859, -1.04267120, NULL),
	(7, 1, 'AMG-GT en los alpes', 'Que os parece este gt black series ?', '@angel_salamero', 'Lat: 45.90903254254447, Lng: 9.173412322998049', '2024-06-03 05:38:49', '2024-06-03 05:38:49', 45.90903254, 9.17341232, NULL),
	(8, 1, 'Un coche Japonés', 'Pues nada un japo mas', '@angel_salamero', 'Lat: 35.36893037007795, Lng: 138.75663757324222', '2024-06-03 08:41:50', '2024-06-03 08:41:50', 35.36893037, 138.75663757, NULL),
	(9, 1, 'Gtr 2', 'Pues una foto de un gtr sencillito.', '@angel_salamero', 'Lat: 36.42123064438361, Lng: 139.1858768463135', '2024-06-03 09:59:19', '2024-06-03 09:59:19', 36.42123064, 139.18587685, NULL),
	(10, 3, 'Pa cuando una kdd ?', 'Pues para cuando se anime mi gente.', '@angel_salamero', 'Lat: 36.601885708744234, Lng: -6.223754882812501', '2024-06-03 10:02:52', '2024-06-03 10:02:52', 36.60188571, -6.22375488, NULL),
	(11, 3, 'Tux', 'Tux os vigila de cerca así que tener cuidado lo que hacéis.', '@angel_salamero', 'Lat: 38.89614326949337, Lng: -77.03699111938478', '2024-06-03 10:06:11', '2024-06-03 10:06:11', 38.89614327, -77.03699112, NULL),
	(12, 3, 'Reunión de coches legendaria', 'Pues una reunión bastante loca, imaginaros ahí, del rollo Fast & Furious.', '@angel_salamero', 'Lat: 38.11213941025767, Lng: 13.348388671875002', '2024-06-03 10:07:57', '2024-06-03 10:07:57', 38.11213941, 13.34838867, NULL),
	(16, 5, 'Porsche VS Bmw', 'Con cual os quedais, el gt3 rs o el m2 competition ??', '@angel_salamero', 'Lat: 51.514324570534846, Lng: -0.08806228637695314', '2024-06-03 10:18:02', '2024-06-03 10:18:02', 51.51432457, -0.08806229, NULL),
	(17, 5, 'Tokio X Coches', 'Pues un post de Tokio kio', '@angel_salamero', 'Lat: 35.94688293218141, Lng: 137.63671875000003', '2024-06-03 10:19:01', '2024-06-03 10:19:01', 35.94688293, 137.63671875, NULL),
	(18, 6, 'Porque ya no ):', 'Porque ya no fabrican estos coches tio?', '@angel_salamero', 'Pirineos, España', '2024-06-03 10:24:30', '2024-06-03 10:24:30', 42.58240120, 0.53882717, NULL),
	(19, 6, 'La bugatti tio', 'Esto es una autentica barbarie', '@angel_salamero', 'Lat: 46.95776134668866, Lng: 1.8896484375000002', '2024-06-03 10:25:32', '2024-06-03 10:25:32', 46.95776135, 1.88964844, NULL),
	(20, 6, 'Like a bosch', 'Lavadora inteligente, like a bosch\nsu consumo inteligente, like a bosch', '@angel_salamero', 'Bosch, Hohbuch, Happenbach, Abstatt, Verwaltungsverband Schozach-Bottwartal, Landkreis Heilbronn, Baden-Wurtemberg, 74232, Alemania', '2024-06-03 10:27:56', '2024-06-03 10:27:56', 49.07809450, 9.30461699, NULL),
	(21, 7, 'Cambia de color con agua', 'Pues parece que esto cambia de color con el agua xd', '@angel_salamero', 'Lat: 25.775044649995174, Lng: -80.19315719604492', '2024-06-03 10:29:49', '2024-06-03 10:29:49', 25.77504465, -80.19315720, NULL),
	(22, 7, 'Me encanta la F1!', 'Algun apasionad@e del motor por aqui ?', '@angel_salamero', 'F1, Gómez Carreño, Viña del Mar, Provincia de Valparaíso, Región de Valparaíso, Chile', '2024-06-03 10:31:07', '2024-06-03 10:31:07', -32.98699130, -71.52962475, NULL),
	(23, 7, 'Jett o Raze ??', 'Importantisima pregunta', '@angel_salamero', 'Lat: 50.0536119068041, Lng: 14.414062500000002', '2024-06-03 10:33:03', '2024-06-03 10:33:03', 50.05361191, 14.41406250, NULL),
	(24, 8, 'Fondazo de pantaia', 'Habeis visto este pedazo fondo pantalla ?', '@angel_salamero', 'Lat: 36.16407212502812, Lng: -115.13877868652345', '2024-06-03 10:45:24', '2024-06-03 10:45:24', 36.16407213, -115.13877869, NULL),
	(25, 8, 'Menudo ferrata looko', 'Habeis visto este pedazo ferrari 452 pista enzo pro max plus?', '@angel_salamero', 'Ferrari, Lughezzano, Bosco Chiesanuova, Verona, Véneto, 37021, Italia', '2024-06-03 10:46:04', '2024-06-03 10:46:04', 45.59452180, 11.00198150, NULL),
	(26, 8, 'Atentos al chevroloko', 'Habeis visto este pedazo chevroloko camaro coreografo maximus praim??', '@angel_salamero', 'Lat: 27.947099367319762, Lng: -15.589599609375002', '2024-06-03 10:47:18', '2024-06-03 10:47:18', 27.94709937, -15.58959961, NULL),
	(27, 9, 'Citroenazo', 'Pa los q digan q Citröen no sabe hacer coches.', '@angel_salamero', 'Citroen, Русе, 7000, Bulgaria', '2024-06-03 10:48:47', '2024-06-03 10:48:47', 43.81355340, 25.98036108, NULL),
	(28, 9, 'supra x gtr', 'solo japonesess', '@angel_salamero', 'Lat: -33.89549722712386, Lng: 151.17187500000003', '2024-06-03 10:49:57', '2024-06-03 10:49:57', -33.89549723, 151.17187500, NULL),
	(29, 9, 'gtr x supra', 'Pues nada nuevo solo autos', '@angel_salamero', 'Lat: 43.34116005412307, Lng: 142.73437500000003', '2024-06-03 10:50:31', '2024-06-03 10:50:31', 43.34116005, 142.73437500, NULL),
	(30, 10, 'Aprobados!', 'Pues nuestros alumnos de este año son unas maquinas de programar y aunque lo han pasado mal, todos aprueban!!!!', '@angel_salamero', 'Lat: 41.90305660226908, Lng: 0.18474519252777102', '2024-06-05 10:44:59', '2024-06-05 10:44:59', 41.90305660, 0.18474519, NULL);

-- Volcando estructura para tabla cargram.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla cargram.users: ~8 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `profile_image`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'angel_salamero', 'a@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$SDp98qWa57eg0X2Ac0515uJXxuqWahavy3nJFu.VOMaqTBGdG6Cy.', NULL, '2024-05-31 17:06:57', '2024-06-03 08:34:17'),
	(2, 'jose_subias', 'j@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$MM5lpmIIZ8WeEu5O4a/LxO/0WfloLtNaZ1fBgdiSxbAohCKjp6IAm', NULL, '2024-05-31 17:07:57', '2024-05-31 17:07:57'),
	(3, 'pedro_subias', 'pedrosubias@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$y6pf.GdN/PzM3C4IIJnkhuCgpPUvhp0t/HekFSfnhk726MiXJcNb.', NULL, '2024-06-03 10:01:52', '2024-06-03 10:01:52'),
	(4, 'jose_perales', 'joseperales@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$h.GuRQfmNoeZYJvBHHSyOuJ2NBR/VdOD9Y771WLMi4nGMYb8FXKB.', NULL, '2024-06-03 10:05:05', '2024-06-03 10:05:05'),
	(5, 'juan_gargallo', 'juangargallo@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$zHAbDZ7f/hDsgLDrpMtMB.YvZgWuGMynmSg9rqcwaJ9lGt4yQWlqe', NULL, '2024-06-03 10:16:43', '2024-06-03 10:16:43'),
	(6, 'bbonso_', 'bonso@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$6oAsrNGMHob7xmrfCIRBoOhQAFqRbVDS4K/Lkxyy3RV/EI0AGZJx6', NULL, '2024-06-03 10:23:00', '2024-06-03 10:23:00'),
	(7, 'jaime_fnz', 'jaimeferrer@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$rqjPbJDpOec7K95MdS6XiuDS2TkwuTSST0VjPcWzOjGn1T6ojCV2S', NULL, '2024-06-03 10:29:08', '2024-06-03 10:29:08'),
	(8, 'nicolas_perera', 'nicolasperera@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$QImmzM7Rg/dbitrGZx409uTmcbr776lhg0hcnA.ROt8zmWcGUSFZa', NULL, '2024-06-03 10:44:43', '2024-06-03 10:44:43'),
	(9, 'alvaro_manaliu', 'alvaromanaliu@gmail.com', NULL, 'profile_photos/lopyUIweEQn0xLprYYXHAKR8VLMmgF9K9H5IHElF.webp', NULL, '$2y$12$.XRie8t8P7ickZMNGoCFleujTrksG4.th2BihYLlqF5GzuhiWH5zu', NULL, '2024-06-03 10:48:09', '2024-06-03 10:48:09'),
	(10, 'profesor_mordefuentes', 'profesormordefuentes@gmail.com', NULL, NULL, NULL, '$2y$12$vEOs9gX.PUJfLSU141NKJ..6wh43iLnO6/qC5vh1FhfJ3GtXzXDa6', NULL, '2024-06-05 10:43:21', '2024-06-05 10:43:21');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
