-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2024 at 06:15 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umq`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_product`
--

CREATE TABLE `cart_product` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `provider_id` bigint(20) NOT NULL,
  `counter` float NOT NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_product`
--

INSERT INTO `cart_product` (`id`, `user_id`, `product_id`, `provider_id`, `counter`, `updated_at`) VALUES
(52, 77, 3, 7, 2, '2023-09-16 21:01:59'),
(61, 53, 1, 7, 1, '2023-09-18 20:36:35'),
(67, 77, 2, 7, 1, '2023-10-25 13:41:19'),
(68, 16, 1, 7, 2, '2023-11-03 23:11:46'),
(70, 88, 4, 5, 2, '2023-11-10 10:39:58'),
(71, 53, 2, 7, 1, '2023-11-14 05:02:30'),
(72, 79, 2, 7, 1, '2023-11-27 22:59:34'),
(73, 100, 3, 7, 1, '2023-12-30 05:52:39');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` bigint(20) NOT NULL,
  `name_en` varchar(1000) DEFAULT NULL,
  `name_ar` varchar(1000) DEFAULT NULL,
  `description_en` varchar(3000) DEFAULT NULL,
  `description_ar` varchar(3000) DEFAULT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name_en`, `name_ar`, `description_en`, `description_ar`, `hidden`, `status`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Glass', 'نظارة غوص', 'Glass all colors', 'نظارات جميع الالوان', 0, 1, NULL, '2023-10-23 23:24:11', '2023-10-16 23:24:08'),
(2, 'Fines', 'زعانف', 'Fines All Colors', 'زعانف كل الالوان', 0, 1, NULL, '2023-10-17 23:24:02', '2023-10-16 23:24:05'),
(9, 'glass', 'نضارة', NULL, NULL, 0, 1, NULL, '2023-12-30 05:34:06', '2023-12-30 05:34:06');

-- --------------------------------------------------------

--
-- Table structure for table `chat_message`
--

CREATE TABLE `chat_message` (
  `id` bigint(11) NOT NULL,
  `text` text DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `video` varchar(256) DEFAULT NULL,
  `recorder` varchar(256) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'meta of files',
  `status_read` varchar(100) NOT NULL DEFAULT 'send' COMMENT 'wait, send, received, readed',
  `deleted` tinyint(1) NOT NULL,
  `senderId` bigint(11) NOT NULL,
  `receiverId` bigint(11) NOT NULL,
  `group_key` varchar(100) DEFAULT NULL,
  `messageIdFollowed` bigint(11) NOT NULL DEFAULT 0,
  `reply` bigint(11) NOT NULL DEFAULT 0 COMMENT 'message id reply to',
  `fcm_status` tinyint(1) NOT NULL DEFAULT 0,
  `fcm_message_id` text NOT NULL COMMENT '	response return from FCM api',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_message`
--

INSERT INTO `chat_message` (`id`, `text`, `image`, `video`, `recorder`, `meta`, `status_read`, `deleted`, `senderId`, `receiverId`, `group_key`, `messageIdFollowed`, `reply`, `fcm_status`, `fcm_message_id`, `created_at`, `updated_at`) VALUES
(66, '6', NULL, NULL, NULL, NULL, 'readed', 0, 16, 77, '16_77', 0, 0, 1, '{\"message_id\":7851842297004708124}', '2022-06-29 16:37:07', '2022-06-29 16:38:18'),
(67, 'hi', NULL, NULL, NULL, NULL, 'readed', 0, 77, 16, '16_77', 0, 0, 1, '{\"message_id\":45406733314082008}', '2022-06-29 16:38:21', '2023-11-04 14:06:29'),
(68, 'reply', NULL, NULL, NULL, NULL, 'readed', 0, 16, 77, '16_77', 0, 0, 1, '{\"message_id\":7600150567588316817}', '2022-06-29 16:38:26', '2022-06-29 16:40:47'),
(69, '2', NULL, NULL, NULL, NULL, 'readed', 0, 77, 16, '16_77', 0, 0, 1, '{\"message_id\":8616342005604425048}', '2022-06-29 16:38:32', '2023-11-04 14:06:29'),
(70, 'reply', NULL, NULL, NULL, NULL, 'readed', 0, 16, 77, '16_77', 0, 0, 1, '{\"message_id\":4155376171663923674}', '2022-06-29 16:38:35', '2022-06-29 16:40:47'),
(71, '3', NULL, NULL, NULL, NULL, 'readed', 0, 77, 16, '16_77', 0, 0, 1, '{\"message_id\":8204819108613093797}', '2022-06-29 16:38:41', '2023-11-04 14:06:29'),
(72, 'reply', NULL, NULL, NULL, NULL, 'readed', 0, 16, 77, '16_77', 0, 0, 1, '{\"message_id\":8885188663894260698}', '2022-06-29 16:38:44', '2022-06-29 16:40:47'),
(73, 'how are u', NULL, NULL, NULL, NULL, 'readed', 0, 77, 16, '16_77', 0, 0, 1, '{\"message_id\":8412869331199808696}', '2022-06-29 16:38:58', '2023-11-04 14:06:29'),
(74, 'fine', NULL, NULL, NULL, NULL, 'readed', 0, 16, 77, '16_77', 0, 0, 1, '{\"message_id\":2565812999518727874}', '2022-06-29 16:39:09', '2022-06-29 16:40:47'),
(77, NULL, 'https://umq.app/php/public/images/gallery_image/gallery1.jpg', NULL, NULL, NULL, 'readed', 0, 77, 16, '16_77', 0, 0, 1, '{\"message_id\":1302750349244126394}', '2022-06-29 16:40:26', '2023-11-04 14:06:29'),
(78, 'السلام عليكم', NULL, NULL, NULL, NULL, 'readed', 0, 15, 16, '15_16', 0, 0, 1, '{\"message_id\":634522465879990913}', '2022-06-29 18:47:18', '2023-11-11 14:34:43'),
(79, NULL, 'https://umq.app/php/public/images/gallery_image/gallery1.jpg', NULL, NULL, NULL, 'readed', 0, 15, 16, '15_16', 0, 0, 1, '{\"message_id\":37162302846485130}', '2022-06-29 18:47:33', '2023-11-11 14:34:43'),
(84, NULL, 'https://umq.app/php/public/images/gallery_image/gallery1.jpg', NULL, NULL, NULL, 'send', 0, 78, 15, '15_78', 0, 0, 1, '{\"message_id\":4071655974080487333}', '2022-06-30 14:22:43', '2022-06-30 14:22:43'),
(85, 'hi john', NULL, NULL, NULL, NULL, 'send', 0, 53, 18, '18_53', 0, 0, 1, '{\"message_id\":1397492521769104666}', '2023-08-22 17:13:29', '2023-08-22 17:13:29'),
(86, 'welcome', NULL, NULL, NULL, NULL, 'readed', 0, 77, 16, '16_77', 0, 0, 1, '{\"message_id\":7666932167565497065}', '2023-09-16 21:29:31', '2023-11-04 14:06:29'),
(87, 'hi 1', NULL, NULL, NULL, NULL, 'readed', 0, 77, 16, '16_77', 0, 0, 1, '{\"message_id\":8043603641390674362}', '2023-09-16 21:47:21', '2023-11-04 14:06:29'),
(88, '3', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":4567563821287029198}', '2023-09-16 21:53:40', '2023-09-16 21:53:40'),
(89, 'postman 4', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5195046782409252890}', '2023-09-16 21:55:56', '2023-09-16 21:55:56'),
(90, 'postman 5', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7686762526225949721}', '2023-09-16 21:58:05', '2023-09-16 21:58:05'),
(91, 'postman 6', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1056478415868504457}', '2023-09-16 22:01:32', '0000-00-00 00:00:00'),
(92, 'postman 7', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2976763761534476163}', '2023-09-16 22:04:11', '0000-00-00 00:00:00'),
(93, 'postman 8', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3212613598697177402}', '2023-09-16 22:10:38', '0000-00-00 00:00:00'),
(94, 'postman 9', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3954436683540632151}', '2023-09-16 22:14:36', '0000-00-00 00:00:00'),
(95, 'postman 10', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8133338493514063820}', '2023-09-16 22:19:40', '0000-00-00 00:00:00'),
(96, 'postman 11', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":9223167289172260023}', '2023-09-16 22:37:56', '0000-00-00 00:00:00'),
(97, 'postman 12', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1297359651065322005}', '2023-09-17 00:53:47', '0000-00-00 00:00:00'),
(98, 'postman 12', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2076405736323764375}', '2023-09-17 00:54:32', '0000-00-00 00:00:00'),
(99, 'postman 12', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1194172650539882900}', '2023-09-17 00:55:09', '0000-00-00 00:00:00'),
(100, 'postman 13', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8608997060556408786}', '2023-09-17 00:55:47', '0000-00-00 00:00:00'),
(101, 'postman 14', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1666327808497936371}', '2023-09-17 00:58:40', '0000-00-00 00:00:00'),
(102, 'postman 14', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5448433284315467168}', '2023-09-17 01:00:53', '0000-00-00 00:00:00'),
(103, 'postman 15', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6802957116755334263}', '2023-09-17 16:04:08', '0000-00-00 00:00:00'),
(104, 'postman 16', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6825372998615168023}', '2023-09-17 16:34:21', '0000-00-00 00:00:00'),
(105, 'postman 17', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8550776770298094495}', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'postman 17', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8655799039935527352}', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'postman 17', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8751515509722100463}', '2023-09-17 16:43:45', '2023-09-17 17:15:40'),
(108, 'postman 17', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6908937467138190858}', '2023-09-17 17:05:30', '2023-09-17 17:15:40'),
(109, 'postman 18', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":740207966424032675}', '2023-09-17 17:15:12', '2023-09-17 17:15:40'),
(110, 'postman 19', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":191743231227394332}', '2023-09-17 17:15:23', '2023-09-17 17:15:40'),
(111, 'postman 20', NULL, NULL, NULL, NULL, 'readed', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5493723545528527939}', '2023-09-17 17:15:50', '2023-09-17 17:20:37'),
(112, '21', NULL, NULL, NULL, NULL, 'readed', 0, 77, 53, '53_77', 0, 0, 1, '{\"message_id\":1694760703350847170}', '2023-09-17 17:20:45', '2023-11-11 18:17:41'),
(113, 'hi admin', NULL, NULL, NULL, NULL, 'readed', 0, 86, 16, '16_86', 0, 0, 1, '{\"message_id\":8493644774210500107}', '2023-10-04 04:02:19', '2023-11-11 14:38:11'),
(114, 'are you avaliable', NULL, NULL, NULL, NULL, 'send', 0, 53, 18, '18_53', 0, 0, 1, '{\"message_id\":8132064106787035687}', '2023-10-04 04:06:33', '2023-10-04 04:06:33'),
(115, 'postman 4', NULL, NULL, NULL, NULL, 'send', 0, 53, 18, '18_53', 0, 0, 1, '{\"message_id\":5401286190011816733}', '2023-10-04 04:07:29', '2023-10-04 04:07:29'),
(116, 'postman 4', NULL, NULL, NULL, NULL, 'send', 0, 53, 18, '18_53', 0, 0, 1, '{\"message_id\":5165745909384140311}', '2023-10-04 04:40:13', '2023-10-04 04:40:13'),
(117, 'postman 6', NULL, NULL, NULL, NULL, 'send', 0, 53, 18, '18_53', 0, 0, 1, '{\"message_id\":1047865028646981768}', '2023-10-04 04:44:03', '2023-10-04 04:44:03'),
(118, 'postman 6', NULL, NULL, NULL, NULL, 'send', 0, 53, 18, '18_53', 0, 0, 1, '{\"message_id\":7632184564062281162}', '2023-10-04 04:46:44', '2023-10-04 04:46:44'),
(119, '1', NULL, NULL, NULL, NULL, 'readed', 0, 77, 16, '16_77', 0, 0, 1, '{\"message_id\":5232083628526358377}', '2023-10-04 05:10:03', '2023-11-04 14:06:29'),
(120, 'السلام عليكم', NULL, NULL, NULL, NULL, 'send', 0, 78, 15, '15_78', 0, 0, 0, 'api key is empty', '2023-10-05 12:21:34', '2023-10-05 12:21:34'),
(121, 'هلا', NULL, NULL, NULL, NULL, 'send', 0, 78, 15, '15_78', 0, 0, 0, 'api key is empty', '2023-10-05 12:22:21', '2023-10-05 12:22:21'),
(122, 'admin write hello', NULL, NULL, NULL, NULL, 'send', 0, 16, 77, '16_77', 0, 0, 1, '{\"message_id\":5746132476244768641}', '2023-11-04 14:50:21', '2023-11-04 14:50:21'),
(123, 'السلام عليكم', NULL, NULL, NULL, NULL, 'send', 0, 88, 15, '15_88', 0, 0, 1, '{\"message_id\":8613327262416515492}', '2023-11-05 15:42:36', '2023-11-05 15:42:36'),
(124, 'كيف حالك كبتن', NULL, NULL, NULL, NULL, 'send', 0, 88, 15, '15_88', 0, 0, 1, '{\"message_id\":1804019288000272838}', '2023-11-05 15:42:49', '2023-11-05 15:42:49'),
(125, 'السلام عليكم', NULL, NULL, NULL, NULL, 'send', 0, 88, 77, '77_88', 0, 0, 1, '{\"message_id\":8639431625588875315}', '2023-11-05 15:45:03', '2023-11-05 15:45:03'),
(126, '👍🏻', NULL, NULL, NULL, NULL, 'send', 0, 88, 77, '77_88', 0, 0, 1, '{\"message_id\":2717265772561007514}', '2023-11-05 15:45:11', '2023-11-05 15:45:11'),
(127, 'ازيك', NULL, NULL, NULL, NULL, 'readed', 0, 79, 16, '16_79', 0, 0, 1, '{\"message_id\":3350175634358916318}', '2023-11-11 14:37:11', '2023-11-11 14:37:52'),
(128, 'hi', NULL, NULL, NULL, NULL, 'readed', 0, 79, 77, '77_79', 0, 0, 1, '{\"message_id\":3374971225460833426}', '2023-11-11 15:06:49', '2023-11-11 15:07:25'),
(129, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__17_25_19__7f11500f6911815245b77f13a48443f2.png', NULL, NULL, NULL, 'readed', 0, 77, 79, '77_79', 0, 0, 1, '{\"message_id\":8800434105398677811}', '2023-11-11 17:25:20', '2023-11-11 17:26:27'),
(130, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__17_34_18__12dbd18d2360e471d320ef30938c56e8.jpg', NULL, NULL, NULL, 'readed', 0, 77, 79, '77_79', 0, 0, 1, '{\"message_id\":5670068744629094367}', '2023-11-11 17:34:18', '2023-11-11 17:44:18'),
(131, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__17_45_08__4fcd334631f1fc4460f1ba0199b083c2.jpg', NULL, NULL, NULL, 'readed', 0, 79, 77, '77_79', 0, 0, 1, '{\"message_id\":616750703712565981}', '2023-11-11 17:45:09', '2023-11-11 17:58:33'),
(132, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__17_45_36__09a84aa41dbc29a477291619fe7feb89.png', NULL, NULL, NULL, 'readed', 0, 79, 77, '77_79', 0, 0, 1, '{\"message_id\":7610357416897332196}', '2023-11-11 17:45:36', '2023-11-11 17:58:33'),
(133, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__18_01_42__d632f10112614e3b0e02220fe4738fb6.m4a', NULL, 'send', 0, 79, 77, '77_79', 0, 0, 1, '{\"message_id\":2284151352271083161}', '2023-11-11 18:01:43', '2023-11-11 18:01:43'),
(134, 'hi', NULL, NULL, NULL, NULL, 'readed', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":4498777285160709944}', '2023-11-11 18:10:28', '2023-11-11 18:10:45'),
(135, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__18_10_50__61f5a211e9ed25b167d4cc715e1e62ef.m4a', NULL, 'readed', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":6857773319141905152}', '2023-11-11 18:10:51', '2023-11-11 18:12:20'),
(136, '22', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1598544871668781274}', '2023-11-11 18:17:53', '2023-11-11 18:17:53'),
(137, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__18_18_03__6ab11943f264ec3dc91595493d67de16.jpg', NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2647315878837040223}', '2023-11-11 18:18:03', '2023-11-11 18:18:03'),
(138, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__18_18_17__3990dbb6e695cc24742cef5c4c0df59e.jpg', NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8701586375459458199}', '2023-11-11 18:18:17', '2023-11-11 18:18:17'),
(139, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__18_19_00__ce0c0bfccd294591507dfe6ff56b8f0c.jpg', NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7304243316404782894}', '2023-11-11 18:19:00', '2023-11-11 18:19:00'),
(140, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__18_19_49__ce0c0bfccd294591507dfe6ff56b8f0c.jpg', NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":136252972756208763}', '2023-11-11 18:19:50', '2023-11-11 18:19:50'),
(141, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__18_20_08__ce0c0bfccd294591507dfe6ff56b8f0c.jpg', NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8217380385067531431}', '2023-11-11 18:20:08', '2023-11-11 18:20:08'),
(142, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__18_20_20__ce0c0bfccd294591507dfe6ff56b8f0c.jpg', NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1184819991867651992}', '2023-11-11 18:20:21', '2023-11-11 18:20:21'),
(143, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__19_58_34__6d725aec066d865bc3883a8a0f80d5ae.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5780870270096841624}', '2023-11-11 19:58:34', '2023-11-11 19:58:34'),
(144, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__19_58_53__6d725aec066d865bc3883a8a0f80d5ae.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":566779945513097857}', '2023-11-11 19:58:54', '2023-11-11 19:58:54'),
(145, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__19_59_27__6d725aec066d865bc3883a8a0f80d5ae.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8582506408829761025}', '2023-11-11 19:59:28', '2023-11-11 19:59:28'),
(146, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_02_02__7c97bf505309176b733ffe2fcf5d5c18.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6325663170968692722}', '2023-11-11 20:02:03', '2023-11-11 20:02:03'),
(147, 't', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7482002447020054127}', '2023-11-11 20:02:11', '2023-11-11 20:02:11'),
(148, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_02_20__1b147263db7576d8e6b6626909ced15f.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7814273351847170173}', '2023-11-11 20:02:20', '2023-11-11 20:02:20'),
(149, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_07_48__cb3d70f50006acdfa9c478fbccc0471f.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3004561367523649217}', '2023-11-11 20:07:48', '2023-11-11 20:07:48'),
(150, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_08_01__d5f2390d3d0664cc5fb503ce5222ddb4.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3110509472061063922}', '2023-11-11 20:08:01', '2023-11-11 20:08:01'),
(151, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_10_51__6550b3fa8d6b4278231c14179d761f00.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":4167451460446675492}', '2023-11-11 20:10:51', '2023-11-11 20:10:51'),
(152, 'y', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1777657578958962218}', '2023-11-11 20:11:06', '2023-11-11 20:11:06'),
(153, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_11_08__5399799a215987866087d29ab7cb5658.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2459719939653261520}', '2023-11-11 20:11:08', '2023-11-11 20:11:08'),
(154, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_11_35__b96d4e70bd5735f3ada3372fd2c30e99.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3371808493178918174}', '2023-11-11 20:11:36', '2023-11-11 20:11:36'),
(155, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_12_30__754e634e74a80cc4fe3e5a99be2bde2c.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8178218181217347516}', '2023-11-11 20:12:31', '2023-11-11 20:12:31'),
(156, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_13_48__4ace765ab0c6a97a4e14c64d33c82ada.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":4130679750150495563}', '2023-11-11 20:13:48', '2023-11-11 20:13:48'),
(157, 'hh', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7804022202882468896}', '2023-11-11 20:16:06', '2023-11-11 20:16:06'),
(158, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_16_09__6fed4cba143d502940ac8ef69467ba61.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6204981301173910496}', '2023-11-11 20:16:10', '2023-11-11 20:16:10'),
(159, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_16_42__451c117d30c578f7b97053900d41cb78.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2644489886714239944}', '2023-11-11 20:16:43', '2023-11-11 20:16:43'),
(160, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_23_10__63f202c9c1b64df69803ad4c7687a033.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5273177971392393860}', '2023-11-11 20:23:11', '2023-11-11 20:23:11'),
(161, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_23_58__ee97d78c0dc9fc7ff7aa32c5d9ef0d6a.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3833386536838010186}', '2023-11-11 20:23:59', '2023-11-11 20:23:59'),
(162, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_29_49__1b710f992ff92786a8e0918e010f9f56.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1280163296910052126}', '2023-11-11 20:29:50', '2023-11-11 20:29:50'),
(163, 'hh', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2655344457439849358}', '2023-11-11 20:31:20', '2023-11-11 20:31:20'),
(164, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_31_30__ff908ed43710e6288a677ea9d2be4d74.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":4858047432888413984}', '2023-11-11 20:31:31', '2023-11-11 20:31:31'),
(165, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_32_25__a22f64e78894bf64538d258b7ce8b698.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7955525350583090319}', '2023-11-11 20:32:25', '2023-11-11 20:32:25'),
(166, 'hj', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7956523281197838733}', '2023-11-11 20:45:26', '2023-11-11 20:45:26'),
(167, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_45_33__2eb64e6ca01540c4632c219cbf2a412d.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5682744176484896245}', '2023-11-11 20:45:34', '2023-11-11 20:45:34'),
(168, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_45_46__2eb64e6ca01540c4632c219cbf2a412d.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7100952452117753233}', '2023-11-11 20:45:47', '2023-11-11 20:45:47'),
(169, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_48_30__e80c7781d843f88b6a06b9dd387f4412.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6452397542740025206}', '2023-11-11 20:48:31', '2023-11-11 20:48:31'),
(170, 'bh', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":9144893211942190337}', '2023-11-11 20:49:03', '2023-11-11 20:49:03'),
(171, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_50_02__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":9070031153142497902}', '2023-11-11 20:50:03', '2023-11-11 20:50:03'),
(172, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_56_31__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2564900253434938505}', '2023-11-11 20:56:32', '2023-11-11 20:56:32'),
(173, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_57_38__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3371846571487222706}', '2023-11-11 20:57:38', '2023-11-11 20:57:38'),
(174, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_58_26__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":705452870200624861}', '2023-11-11 20:58:26', '2023-11-11 20:58:26'),
(175, 'gh', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6939057145399956036}', '2023-11-11 20:58:57', '2023-11-11 20:58:57'),
(176, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__20_59_02__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3135418816102177931}', '2023-11-11 20:59:02', '2023-11-11 20:59:02'),
(177, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_00_16__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3094170929611316803}', '2023-11-11 21:00:16', '2023-11-11 21:00:16'),
(178, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_01_10__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1670225792886928703}', '2023-11-11 21:01:10', '2023-11-11 21:01:10'),
(179, 'h', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5327764289467076595}', '2023-11-11 21:01:19', '2023-11-11 21:01:19'),
(180, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_01_22__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3407316613137698834}', '2023-11-11 21:01:23', '2023-11-11 21:01:23'),
(181, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_02_27__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":152864185946401691}', '2023-11-11 21:02:27', '2023-11-11 21:02:27'),
(182, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_03_02__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6765199206342649005}', '2023-11-11 21:03:03', '2023-11-11 21:03:03'),
(183, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_06_53__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6525087433855975943}', '2023-11-11 21:06:53', '2023-11-11 21:06:53'),
(184, 'h', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7940951577636525778}', '2023-11-11 21:06:58', '2023-11-11 21:06:58'),
(185, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_07_10__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5454943745223119121}', '2023-11-11 21:07:10', '2023-11-11 21:07:10'),
(186, 'h', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6346471237707996380}', '2023-11-11 21:16:18', '2023-11-11 21:16:18'),
(187, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_16_20__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":876393741488883496}', '2023-11-11 21:16:20', '2023-11-11 21:16:20'),
(188, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_16_48__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":9144372539977533906}', '2023-11-11 21:16:49', '2023-11-11 21:16:49'),
(189, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_19_48__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2650952147742112943}', '2023-11-11 21:19:48', '2023-11-11 21:19:48'),
(190, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_19_59__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3150035422960114938}', '2023-11-11 21:20:00', '2023-11-11 21:20:00'),
(191, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_20_22__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":823856231089915915}', '2023-11-11 21:20:22', '2023-11-11 21:20:22'),
(192, 'hy', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6532647733662332114}', '2023-11-11 21:22:03', '2023-11-11 21:22:03'),
(193, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_22_10__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1734370441094211035}', '2023-11-11 21:22:10', '2023-11-11 21:22:10'),
(194, 'y', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6564449520699583938}', '2023-11-11 21:22:28', '2023-11-11 21:22:28'),
(195, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_22_34__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6053307635630136318}', '2023-11-11 21:22:34', '2023-11-11 21:22:34'),
(196, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_22_58__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6444666178027303220}', '2023-11-11 21:22:59', '2023-11-11 21:22:59'),
(197, 'hghg', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2224150273843488508}', '2023-11-11 21:24:26', '2023-11-11 21:24:26'),
(198, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_24_33__66f0fc3ef9b04f3276e5f82dcb94e072.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8073252843781645891}', '2023-11-11 21:24:33', '2023-11-11 21:24:33'),
(199, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_26_24__87ab426495fd970d19360d59ba3e8ae1.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":793640407772090068}', '2023-11-11 21:26:25', '2023-11-11 21:26:25'),
(200, '🙏🙏', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1372859146941158669}', '2023-11-11 21:28:03', '2023-11-11 21:28:03'),
(201, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_28_15__87ab426495fd970d19360d59ba3e8ae1.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":2688531369474513698}', '2023-11-11 21:28:15', '2023-11-11 21:28:15'),
(202, 'ygg', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":9029991877376955762}', '2023-11-11 21:30:48', '2023-11-11 21:30:48'),
(203, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_31_02__990bc2f4b08726ab588a299b942b9202.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":9079508200863293095}', '2023-11-11 21:31:03', '2023-11-11 21:31:03'),
(204, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_31_57__990bc2f4b08726ab588a299b942b9202.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8542877366857446807}', '2023-11-11 21:31:58', '2023-11-11 21:31:58'),
(205, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_32_54__990bc2f4b08726ab588a299b942b9202.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5752522072256065197}', '2023-11-11 21:32:54', '2023-11-11 21:32:54'),
(206, 'hhh', NULL, NULL, NULL, NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":5266793319150037124}', '2023-11-11 21:36:17', '2023-11-11 21:36:17'),
(207, 'hhj', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":8586009013550164779}', '2023-11-11 21:39:53', '2023-11-11 21:39:53'),
(208, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_39_57__adf5d9231454bb41912ba45d5c7cc41f.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3358299606697428179}', '2023-11-11 21:39:58', '2023-11-11 21:39:58'),
(209, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_40_02__adf5d9231454bb41912ba45d5c7cc41f.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":5260757922635260679}', '2023-11-11 21:40:02', '2023-11-11 21:40:02'),
(210, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_45_07__1ecbcb0a0bdb78566635e9b13861a236.mp4', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":9129609756352131373}', '2023-11-11 21:45:07', '2023-11-11 21:45:07'),
(211, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_45_15__1ecbcb0a0bdb78566635e9b13861a236.mp4', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":6767873439780316072}', '2023-11-11 21:45:15', '2023-11-11 21:45:15'),
(212, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_45_20__1ecbcb0a0bdb78566635e9b13861a236.mp4', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":4667152100061660229}', '2023-11-11 21:45:20', '2023-11-11 21:45:20'),
(213, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_45_43__1ecbcb0a0bdb78566635e9b13861a236.mp4', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":7972837597482708129}', '2023-11-11 21:45:44', '2023-11-11 21:45:44'),
(214, 'hyy', NULL, NULL, NULL, NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":701896626271855335}', '2023-11-11 21:50:17', '2023-11-11 21:50:17'),
(215, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_50_24__78e5ecbf975e38569e46322abf109a60.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":3365843692781031935}', '2023-11-11 21:50:24', '2023-11-11 21:50:24'),
(216, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_50_31__78e5ecbf975e38569e46322abf109a60.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":2120190737198791687}', '2023-11-11 21:50:31', '2023-11-11 21:50:31'),
(217, 'trggh', NULL, NULL, NULL, NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":8011384257929364142}', '2023-11-11 21:52:46', '2023-11-11 21:52:46'),
(218, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_52_56__0ef30c4885133cc3f6fac7bd6e2f5a4a.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":4405200997900105792}', '2023-11-11 21:52:56', '2023-11-11 21:52:56'),
(219, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_53_02__0ef30c4885133cc3f6fac7bd6e2f5a4a.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":1697765080253157923}', '2023-11-11 21:53:02', '2023-11-11 21:53:02'),
(220, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_53_44__0ef30c4885133cc3f6fac7bd6e2f5a4a.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":5109260602664647706}', '2023-11-11 21:53:45', '2023-11-11 21:53:45'),
(221, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_53_49__0ef30c4885133cc3f6fac7bd6e2f5a4a.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":8470351279148105693}', '2023-11-11 21:53:49', '2023-11-11 21:53:49'),
(222, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_53_53__0ef30c4885133cc3f6fac7bd6e2f5a4a.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":7358776820315794781}', '2023-11-11 21:53:54', '2023-11-11 21:53:54'),
(223, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_54_06__0ef30c4885133cc3f6fac7bd6e2f5a4a.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":2407246410438801201}', '2023-11-11 21:54:06', '2023-11-11 21:54:06'),
(224, 'thh', NULL, NULL, NULL, NULL, 'readed', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":8494472902380040804}', '2023-11-11 21:55:45', '2023-11-18 21:34:43'),
(225, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_55_54__0ef30c4885133cc3f6fac7bd6e2f5a4a.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":6802282322105791840}', '2023-11-11 21:55:54', '2023-11-11 21:55:54'),
(226, 'tghh', NULL, NULL, NULL, NULL, 'readed', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":431911204608329484}', '2023-11-11 21:59:44', '2023-11-18 21:34:43'),
(227, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__21_59_59__7a169aacff15be2b1adf319f02038999.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":3790211612889666215}', '2023-11-11 21:59:59', '2023-11-11 21:59:59'),
(228, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_11__22_01_09__d3e28117fb1d48074b465e9c45570d4f.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":784941720250580471}', '2023-11-11 22:01:09', '2023-11-11 22:01:09'),
(229, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_12__15_45_49__0e469cb4252b6067398edeb6e11c1f70.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6228199627283944329}', '2023-11-12 15:45:51', '2023-11-12 15:45:51'),
(230, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_13__09_12_46__4922551aba427ca5190b23d6548e7605.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7075351697117821324}', '2023-11-13 09:12:47', '2023-11-13 09:12:47'),
(231, 'h', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6910364489998324713}', '2023-11-13 09:14:14', '2023-11-13 09:14:14'),
(232, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_13__09_14_18__f5e0e9fbc0f6a40f5da5e85ce35ed706.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":4178363611418690412}', '2023-11-13 09:14:19', '2023-11-13 09:14:19'),
(233, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_13__09_14_43__a17d4d42588bebb01bd115a56f099ec5.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":3520946194009397793}', '2023-11-13 09:14:43', '2023-11-13 09:14:43'),
(234, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_13__09_14_48__dea0917f2656299a8c752bcf9cb82292.mp4', NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":9190107625012703549}', '2023-11-13 09:14:48', '2023-11-13 09:14:48'),
(235, 'هواء', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":1901238030853946690}', '2023-11-13 09:14:55', '2023-11-13 09:14:55'),
(236, 'jbbbb\nvvbbb\n\n\njhhh', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":951242889125780409}', '2023-11-13 12:32:13', '2023-11-13 12:32:13'),
(237, 'one line\ntwo line', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":7783751169590607366}', '2023-11-13 12:32:32', '2023-11-13 12:32:32'),
(238, 'نوةة\nنااةة', NULL, NULL, NULL, NULL, 'send', 0, 53, 77, '53_77', 0, 0, 1, '{\"message_id\":6475779376426959013}', '2023-11-13 13:50:24', '2023-11-13 13:50:24'),
(239, 'new', NULL, NULL, NULL, NULL, 'readed', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":4320141824547690534}', '2023-11-13 14:06:00', '2023-11-18 21:34:43'),
(240, 'one\nrwthree\nfour', NULL, NULL, NULL, NULL, 'readed', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":3477990215380133040}', '2023-11-13 14:06:33', '2023-11-18 21:34:43'),
(241, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_13__14_12_49__523584cdd14e530bc6e303d479810b95.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":6724703512302436176}', '2023-11-13 14:12:49', '2023-11-13 14:12:49'),
(242, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_13__14_12_54__ee7e53016695bcc21800a27075294ffa.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":5860476117442627582}', '2023-11-13 14:12:55', '2023-11-13 14:12:55'),
(243, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_13__14_13_00__8a243a7b33990577622e6478ac216699.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":4935293550417946402}', '2023-11-13 14:13:00', '2023-11-13 14:13:00'),
(244, NULL, NULL, NULL, 'https://umq.app/php/storage/uploads/2023_11_13__14_13_21__caf4f9de47161512831c99b8ac7b2536.m4a', NULL, 'send', 0, 93, 77, '77_93', 0, 0, 1, '{\"message_id\":129417378714979428}', '2023-11-13 14:13:21', '2023-11-13 14:13:21'),
(245, 'hi', NULL, NULL, NULL, NULL, 'send', 0, 77, 93, '77_93', 0, 0, 1, '{\"message_id\":4444809006527186654}', '2023-11-18 21:35:36', '2023-11-18 21:35:36'),
(246, 'hi', NULL, NULL, NULL, NULL, 'readed', 0, 88, 78, '78_88', 0, 0, 1, '{\"message_id\":1469606773912040391}', '2023-12-31 21:35:20', '2023-12-31 21:35:51'),
(247, 'What’s up?', NULL, NULL, NULL, NULL, 'readed', 0, 88, 78, '78_88', 0, 0, 1, '{\"message_id\":6793609031818510115}', '2023-12-31 21:35:41', '2023-12-31 21:35:51'),
(248, 'good', NULL, NULL, NULL, NULL, 'readed', 0, 88, 78, '78_88', 0, 0, 1, '{\"message_id\":2265479480365833656}', '2023-12-31 21:36:07', '2023-12-31 21:36:19'),
(249, 'I’m good', NULL, NULL, NULL, NULL, 'readed', 0, 78, 88, '78_88', 0, 0, 1, '{\"message_id\":6130255045746016826}', '2023-12-31 21:36:39', '2023-12-31 21:37:06'),
(250, 'great', NULL, NULL, NULL, NULL, 'readed', 0, 78, 88, '78_88', 0, 0, 1, '{\"message_id\":2904895573490141844}', '2023-12-31 21:36:53', '2023-12-31 21:37:06'),
(251, NULL, 'https://umq.app/php/storage/uploads/2023_12_31__21_37_24__83eb45159df9406bd96df10b22ff72d5.jpg', NULL, NULL, NULL, 'readed', 0, 78, 88, '78_88', 0, 0, 1, '{\"message_id\":8533825925234609362}', '2023-12-31 21:37:24', '2023-12-31 21:37:32'),
(252, NULL, 'https://umq.app/php/storage/uploads/2023_12_31__21_37_46__2444624d87b1b883db301793c56c6b45.jpg', NULL, NULL, NULL, 'readed', 0, 78, 88, '78_88', 0, 0, 1, '{\"message_id\":68333650094572232}', '2023-12-31 21:37:46', '2024-01-25 08:34:51'),
(253, NULL, 'https://umq.app/php/storage/uploads/2023_12_31__21_38_10__b867d96ef11cba0304e6e2b970005bd1.jpg', NULL, NULL, NULL, 'readed', 0, 78, 88, '78_88', 0, 0, 1, '{\"message_id\":2566029344131773855}', '2023-12-31 21:38:10', '2024-01-25 08:34:51'),
(254, 'الوووو', NULL, NULL, NULL, NULL, 'send', 0, 97, 80, '80_97', 0, 0, 1, '{\"message_id\":303343395687133247}', '2024-01-03 10:38:48', '2024-01-03 10:38:48'),
(255, 'السلام عليكم ورحمة الله وبركاته', NULL, NULL, NULL, NULL, 'send', 0, 100, 80, '80_100', 0, 0, 1, '{\"message_id\":2953270334147430977}', '2024-01-03 10:48:39', '2024-01-03 10:48:39'),
(256, 'استاذ فايز كيف حالك\nك', NULL, NULL, NULL, NULL, 'send', 0, 100, 80, '80_100', 0, 0, 1, '{\"message_id\":3884543180713063671}', '2024-01-03 10:49:35', '2024-01-03 10:49:35'),
(257, 'الووو', NULL, NULL, NULL, NULL, 'send', 0, 97, 78, '78_97', 0, 0, 1, '{\"message_id\":99361046789984471}', '2024-01-03 16:24:51', '2024-01-03 16:24:51'),
(258, 'السلام عليكم', NULL, NULL, NULL, NULL, 'send', 0, 97, 78, '78_97', 0, 0, 1, '{\"message_id\":2286804796088892775}', '2024-01-03 16:26:11', '2024-01-03 16:26:11'),
(259, 'الوووو', NULL, NULL, NULL, NULL, 'send', 0, 101, 80, '80_101', 0, 0, 1, '{\"message_id\":78715185642013258}', '2024-01-03 16:27:45', '2024-01-03 16:27:45'),
(260, 'كيف الحال', NULL, NULL, NULL, NULL, 'send', 0, 101, 80, '80_101', 0, 0, 1, '{\"message_id\":1029349254488023040}', '2024-01-03 16:29:29', '2024-01-03 16:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `chat_user`
--

CREATE TABLE `chat_user` (
  `id` int(11) NOT NULL,
  `person_a` bigint(20) NOT NULL DEFAULT 0,
  `person_b` bigint(20) NOT NULL DEFAULT 0,
  `group_key` varchar(100) DEFAULT NULL,
  `group_type` varchar(100) NOT NULL DEFAULT 'individual',
  `lastMessageTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `blocker_person_a` tinyint(1) NOT NULL DEFAULT 0,
  `blocker_person_b` tinyint(1) NOT NULL DEFAULT 0,
  `blocker_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_user`
--

INSERT INTO `chat_user` (`id`, `person_a`, `person_b`, `group_key`, `group_type`, `lastMessageTime`, `blocker_person_a`, `blocker_person_b`, `blocker_admin`) VALUES
(2, 16, 52, '16_52', 'individual', '2022-06-29 14:53:49', 0, 0, 0),
(3, 16, 51, '16_51', 'individual', '2022-06-29 13:36:11', 0, 0, 0),
(4, 53, 16, '16_53', 'individual', '2022-06-18 15:39:31', 0, 0, 0),
(14, 16, 77, '16_77', 'individual', '2023-11-04 14:50:21', 0, 0, 0),
(15, 16, 15, '15_16', 'individual', '2022-06-29 18:48:20', 0, 0, 0),
(16, 15, 78, '15_78', 'individual', '2023-10-05 12:22:21', 0, 0, 0),
(17, 18, 53, '18_53', 'individual', '2023-10-04 04:46:44', 0, 0, 0),
(18, 77, 53, '53_77', 'individual', '2023-11-13 13:50:24', 0, 0, 0),
(19, 16, 86, '16_86', 'individual', '2023-10-04 04:02:19', 0, 0, 0),
(20, 80, 78, '78_80', 'individual', '2023-10-05 12:24:08', 0, 0, 0),
(21, 15, 88, '15_88', 'individual', '2023-11-05 15:42:49', 0, 0, 0),
(22, 77, 88, '77_88', 'individual', '2023-11-05 15:45:11', 0, 0, 0),
(23, 16, 79, '16_79', 'individual', '2023-11-11 14:37:11', 0, 0, 0),
(24, 77, 79, '77_79', 'individual', '2023-11-11 18:01:43', 0, 0, 0),
(25, 77, 93, '77_93', 'individual', '2023-11-18 21:35:36', 0, 0, 0),
(26, 78, 88, '78_88', 'individual', '2023-12-31 21:38:10', 0, 0, 0),
(27, 80, 97, '80_97', 'individual', '2024-01-03 10:38:48', 0, 0, 0),
(28, 80, 100, '80_100', 'individual', '2024-01-03 10:49:35', 0, 0, 0),
(29, 78, 97, '78_97', 'individual', '2024-01-03 16:26:11', 0, 0, 0),
(30, 80, 101, '80_101', 'individual', '2024-01-03 16:29:29', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` bigint(20) NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `lng` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `name_ar`, `name_en`, `lat`, `lng`, `status`, `created_at`, `updated_at`) VALUES
(1, 'مكة', 'Macka', '15', '16', 1, NULL, NULL),
(2, 'رياض', 'Riyad', '', '', 1, '2022-03-23 10:34:37', NULL),
(9, 'qahara', 'cairo', '', '', 0, '2022-04-20 16:28:10', '2022-04-20 16:28:10'),
(10, 'giza ar ed 2', 'giza en Ed 2', '', '', 0, '2022-04-20 19:34:17', '2022-04-20 19:34:17'),
(11, 'جدة', 'Jeddah', '', '', 1, '2022-04-30 00:10:00', '2022-04-30 00:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT 'case user login',
  `subject` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `guest_name` varchar(256) NOT NULL,
  `guest_email` varchar(256) NOT NULL,
  `guest_phone` varchar(256) NOT NULL,
  `read_status` varchar(50) NOT NULL DEFAULT 'unread' COMMENT 'unread, readed, deleted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `user_id`, `subject`, `message`, `guest_name`, `guest_email`, `guest_phone`, `read_status`, `created_at`, `updated_at`) VALUES
(12, 0, 'subject test', 'message tesrt', 'Abdallah', 'abdo@gmail.lcom', '01012356', 'unread', '2022-05-27 08:15:08', '2022-05-27 08:15:08'),
(15, 0, 'Need help', 'How to go to university', 'Domain live Name', 'Abdallah', '01025254125', 'unread', '2022-05-27 14:40:54', '2022-05-27 14:40:54'),
(16, 0, 'title mobile', 'test message', 'mobile simulator', 'mobile@gmail.com', '123455', 'unread', '2022-05-27 15:38:24', '2022-05-27 15:38:24'),
(17, 0, 'title mobile', 'test message', 'mobile simulator', 'mobile@gmail.com', '123455', 'unread', '2022-05-27 15:38:30', '2022-05-27 15:38:30'),
(18, 0, '4545dsf', '4544564', 'abdo', 'abdo@gmoic.o', 'dfsa5645', 'unread', '2022-05-27 15:40:58', '2022-05-27 15:40:58'),
(19, 0, 'IPhone Simlator test', 'message test', 'iPhone simulator', 'abdo', '010123456', 'unread', '2022-05-29 12:01:16', '2022-05-29 12:01:16'),
(20, 0, 'Need to Learn Swim', 'Hello How are you? I have a question Can i learn with my friend.', 'Mohamed Salah', 'mohamed@gmail.com', '01012345678', 'unread', '2023-10-16 20:20:05', '2023-10-16 20:20:05'),
(21, 0, 'Need to Learn Swim', 'Hello How are you? I have a question Can i learn with my friend.', 'Mohamed Salah', 'mohamed@gmail.com', '01012345678', 'unread', '2023-10-16 20:22:55', '2023-10-16 20:22:55'),
(22, 0, 'Need to Learn Swim', 'Hello How are you? I have a question Can i learn with my friend.', 'Mohamed Salah', 'mohamed@gmail.com', '01012345678', 'unread', '2023-10-16 20:29:55', '2023-10-16 20:29:55'),
(23, 0, 'need subscbe', 'can you let me subscribe fro 1 year with discount', 'abdallah', 'abdo@gmail.com', '01012345678', 'unread', '2023-10-16 21:25:31', '2023-10-16 21:25:31'),
(24, 0, 'Need to Learn Swim', 'Hello How are you? I have a question Can i learn with my friend.', 'Mohamed Salah', 'mohamed@gmail.com', '01012345678', 'unread', '2023-10-16 21:37:01', '2023-10-16 21:37:01'),
(25, 0, 'Need to Learn Swim', 'Hello How are you? I have a question Can i learn with my friend.', 'Mohamed Salah', 'mohamed@gmail.com', '01012345678', 'unread', '2023-10-16 21:37:09', '2023-10-16 21:37:09'),
(26, 0, 'Need to Learn Swim', 'Hello How are you? I have a question Can i learn with my friend.', 'Mohamed Salah', 'mohamed@gmail.com', '01012345678', 'unread', '2023-10-16 21:45:18', '2023-10-16 21:45:18'),
(27, 0, 'Need to Learn Swim', 'Hello How are you? I have a question Can i learn with my friend.', 'Mohamed Salah', 'mohamed@gmail.com', '01012345678', 'unread', '2023-10-16 21:45:22', '2023-10-16 21:45:22'),
(28, 0, 'Need to Learn Swim', 'Hello How are you? I have a question Can i learn with my friend.', 'Mohamed Salah', 'mohamed@gmail.com', '01012345678', 'unread', '2023-10-16 21:56:37', '2023-10-16 21:56:37'),
(29, 0, 'my iPhone lost', 'can you connect with provider', 'need help', 'hjjj@gmail.com', '55885588', 'unread', '2023-10-20 04:07:22', '2023-10-20 04:07:22'),
(30, 0, 'ccccc', 'لمن تصل الرسالة', 'ماهر', 'murele@yahoo.com', '0569679789', 'unread', '2023-11-10 10:45:11', '2023-11-10 10:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `fav_gallery_image`
--

CREATE TABLE `fav_gallery_image` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `gallery_id` bigint(20) NOT NULL,
  `favorite` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fav_gallery_image`
--

INSERT INTO `fav_gallery_image` (`id`, `user_id`, `gallery_id`, `favorite`) VALUES
(22, 16, 1, 1),
(24, 58, 1, 1),
(25, 58, 2, 1),
(26, 70, 1, 1),
(27, 52, 1, 1),
(28, 53, 2, 1),
(29, 16, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fav_gallery_video`
--

CREATE TABLE `fav_gallery_video` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `gallery_id` bigint(20) NOT NULL,
  `favorite` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fav_gallery_video`
--

INSERT INTO `fav_gallery_video` (`id`, `user_id`, `gallery_id`, `favorite`) VALUES
(11, 58, 1, 0),
(12, 0, 0, 0),
(13, 16, 1, 1),
(14, 53, 2, 1),
(15, 16, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fav_product`
--

CREATE TABLE `fav_product` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `favorite` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fav_product`
--

INSERT INTO `fav_product` (`id`, `user_id`, `product_id`, `favorite`) VALUES
(5, 58, 1, 0),
(6, 70, 1, 0),
(7, 52, 1, 1),
(8, 15, 2, 1),
(9, 16, 1, 1),
(10, 53, 1, 1),
(11, 77, 1, 0),
(12, 16, 16, 1),
(13, 16, 2, 1),
(14, 16, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fav_provider`
--

CREATE TABLE `fav_provider` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `provider_id` bigint(20) NOT NULL,
  `favorite` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fav_provider`
--

INSERT INTO `fav_provider` (`id`, `user_id`, `provider_id`, `favorite`) VALUES
(5, 58, 7, 1),
(6, 15, 2, 1),
(7, 16, 7, 1),
(8, 80, 46, 1),
(9, 78, 2, 1),
(10, 78, 46, 1),
(11, 16, 2, 1),
(12, 16, 45, 1),
(13, 88, 45, 1),
(14, 97, 46, 1),
(15, 101, 56, 1),
(16, 100, 56, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_image`
--

CREATE TABLE `gallery_image` (
  `id` bigint(20) NOT NULL,
  `provider_id` bigint(20) NOT NULL DEFAULT 0,
  `file` varchar(255) NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_image`
--

INSERT INTO `gallery_image` (`id`, `provider_id`, `file`, `hidden`, `published`, `created_at`, `updated_at`) VALUES
(1, 7, 'https://umq.app/php/public/images/gallery_image/gallery1.jpg', 0, 1, '2022-03-23 23:01:00', '2022-03-23 23:01:00'),
(2, 7, 'https://umq.app/php/public/images/gallery_image/gallery2.jpg', 0, 1, '2022-03-23 23:01:10', '2022-03-23 23:01:10'),
(3, 7, 'https://umq.app/php/public/images/gallery_image/gallery1.jpg', 0, 1, '2022-03-28 21:55:26', '2022-03-28 21:55:26'),
(21, 45, 'https://umq.app/php/storage/uploads/2023_10_16__11_32_50__ffdb703c632f19027ecae071c99f146f.png', 1, 0, NULL, NULL),
(22, 45, 'https://umq.app/php/storage/uploads/2023_10_16__11_49_03__ffdb703c632f19027ecae071c99f146f.png', 1, 1, NULL, NULL),
(23, 45, 'https://umq.app/php/storage/uploads/2023_10_23__14_46_21__c22cc854166bfb53551bb94003be7bd8.png', 1, 1, NULL, NULL),
(24, 0, 'https://umq.app/php/storage/uploads/2023_11_10__10_55_00__95fc7b412666986b99fbcd238b6f09c2.png', 0, 1, NULL, NULL),
(25, 53, 'https://umq.app/php/storage/uploads/2023_11_17__14_40_25__807b9ddcdda216a295a3dbefb667a7a2.jpg', 0, 1, NULL, NULL),
(26, 50, 'https://umq.app/php/storage/uploads/2023_11_20__03_50_10__9c6e8bb7aa1e9a6c06b499bc39357e8f.jpg', 0, 1, NULL, NULL),
(27, 0, 'https://umq.app/php/storage/uploads/2023_12_30__05_57_25__349b2a261ebb2b17abcd2ffc16a177bd.jpg', 0, 1, NULL, NULL),
(28, 0, 'https://umq.app/php/storage/uploads/2023_12_30__05_59_09__349b2a261ebb2b17abcd2ffc16a177bd.jpg', 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_video`
--

CREATE TABLE `gallery_video` (
  `id` bigint(20) NOT NULL,
  `provider_id` bigint(20) NOT NULL DEFAULT 0,
  `file` varchar(255) NOT NULL,
  `thump` varchar(255) NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_video`
--

INSERT INTO `gallery_video` (`id`, `provider_id`, `file`, `thump`, `hidden`, `published`, `created_at`, `updated_at`) VALUES
(1, 7, 'https://firebasestorage.googleapis.com/v0/b/scuba-6780c.appspot.com/o/example_gallery_video%2Fscuba_28mega.mp4?alt=media&token=534f4d5c-8b54-457f-a565-8d23fb542c5e', 'https://umq.app/php/public/images/logo/logo_squre.png', 0, 1, '2022-03-23 23:13:22', '2022-03-23 23:13:22'),
(2, 7, 'https://firebasestorage.googleapis.com/v0/b/scuba-6780c.appspot.com/o/example_gallery_video%2Fscuba_28mega.mp4?alt=media&token=534f4d5c-8b54-457f-a565-8d23fb542c5e', 'https://umq.app/php/public/images/logo/logo_squre.png', 0, 1, '2022-03-28 21:52:40', '2022-03-28 21:52:40'),
(3, 7, 'https://firebasestorage.googleapis.com/v0/b/scuba-6780c.appspot.com/o/example_gallery_video%2Fscuba_28mega.mp4?alt=media&token=534f4d5c-8b54-457f-a565-8d23fb542c5e', 'https://umq.app/php/public/images/logo/logo_squre.png', 0, 1, '2022-03-28 21:52:51', '2022-03-28 21:52:51'),
(18, 45, 'https://umq.app/php/storage/uploads/2023_10_14__21_57_33__567ab09c1fed52c873f6530a315b1cf3.mp4', 'https://umq.app/php/storage/uploads/2023_10_14__22_03_42__ffdb703c632f19027ecae071c99f146f.png', 1, 0, NULL, NULL),
(19, 45, 'https://umq.app/php/storage/uploads/2023_10_14__22_48_32__567ab09c1fed52c873f6530a315b1cf3.mp4', 'https://umq.app/php/storage/uploads/2023_10_14__22_48_05__ffdb703c632f19027ecae071c99f146f.png', 1, 1, NULL, NULL),
(20, 45, 'https://umq.app/php/storage/uploads/2023_10_14__22_53_57__567ab09c1fed52c873f6530a315b1cf3.mp4', 'https://umq.app/php/storage/uploads/2023_10_14__22_53_40__ffdb703c632f19027ecae071c99f146f.png', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2022_06_08_200220_create_jobs_table', 1),
(2, '2024_01_27_160328_create_products_table', 1),
(3, '2024_01_27_160350_create_services_table', 1),
(4, '2024_01_27_230345_create_terms_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification_admin`
--

CREATE TABLE `notification_admin` (
  `id` bigint(20) NOT NULL,
  `added_by` varchar(256) NOT NULL DEFAULT 'automatic',
  `topic` varchar(256) DEFAULT NULL,
  `fcm_message_id` text DEFAULT NULL COMMENT 'response return from FCM api',
  `title` varchar(256) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `fcm_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is fcm push succussfuly',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_admin`
--

INSERT INTO `notification_admin` (`id`, `added_by`, `topic`, `fcm_message_id`, `title`, `message`, `fcm_status`, `created`, `updated`) VALUES
(71, 'automatic', 'id_78', 'api key is empty', 'Subscribe Order ID#24', 'تم الاشتراك في العضوية ', 0, '2023-10-05 12:38:23', '2023-10-05 12:38:23'),
(72, 'automatic', 'role_admin', 'api key is empty', 'New User Subscribe', 'تم الاشتراك في العضوية ', 0, '2023-10-05 12:38:23', '2023-10-05 12:38:23'),
(73, 'automatic', 'id_77', 'api key is empty', 'Order #54', 'طلبك قيد التحضير', 0, '2023-10-13 19:10:05', '2023-10-13 19:10:05'),
(74, 'automatic', 'id_77', 'api key is empty', 'Order #54', 'طلب جديد', 0, '2023-10-13 19:10:05', '2023-10-13 19:10:05'),
(75, 'automatic', 'role_admin', 'api key is empty', 'Order #54', 'طلب جديد', 0, '2023-10-13 19:10:05', '2023-10-13 19:10:05'),
(76, 'automatic', 'id_77', '{\"message_id\":1532455901280778335}', 'Subscribe Order ID#25', 'تم الاشتراك في العضوية ', 1, '2023-10-20 12:53:09', '2023-10-20 12:53:09'),
(77, 'automatic', 'role_admin', '{\"message_id\":3837205730578751031}', 'New User Subscribe', 'تم الاشتراك في العضوية ', 1, '2023-10-20 12:53:10', '2023-10-20 12:53:10'),
(78, 'automatic', 'id_77', '{\"message_id\":1931784624365664350}', 'Subscribe Order ID#26', 'تم الاشتراك في العضوية ', 1, '2023-10-20 18:15:06', '2023-10-20 18:15:06'),
(79, 'automatic', 'role_admin', '{\"message_id\":4140361815399205730}', 'New User Subscribe', 'تم الاشتراك في العضوية ', 1, '2023-10-20 18:15:06', '2023-10-20 18:15:06'),
(80, 'automatic', 'id_18', '{\"message_id\":7830472818154548128}', 'Subscribe Order ID#27', 'تم الاشتراك في العضوية ', 1, '2023-10-20 18:26:44', '2023-10-20 18:26:44'),
(81, 'automatic', 'role_admin', '{\"message_id\":6134514039080683962}', 'New User Subscribe', 'تم الاشتراك في العضوية ', 1, '2023-10-20 18:26:45', '2023-10-20 18:26:45'),
(82, 'automatic', 'id_16', '{\"message_id\":3372773404831219525}', 'Order #55', 'طلبك قيد التحضير', 1, '2023-11-03 19:53:31', '2023-11-03 19:53:31'),
(83, 'automatic', 'id_77', '{\"message_id\":2249976717656228649}', 'Order #55', 'طلب جديد', 1, '2023-11-03 19:53:33', '2023-11-03 19:53:33'),
(84, 'automatic', 'role_admin', '{\"message_id\":6245517410726669900}', 'Order #55', 'طلب جديد', 1, '2023-11-03 19:53:34', '2023-11-03 19:53:34'),
(85, 'automatic', 'id_16', '{\"message_id\":1861739869776284290}', 'Subscribe Order ID#28', 'تم الاشتراك في العضوية ', 1, '2023-11-04 14:49:57', '2023-11-04 14:49:57'),
(86, 'automatic', 'role_admin', '{\"message_id\":2572508227463596633}', 'New User Subscribe', 'تم الاشتراك في العضوية ', 1, '2023-11-04 14:49:58', '2023-11-04 14:49:58'),
(87, 'admin', 'id_77', '{\"message_id\":8598896706350524483}', 'abdallah subscibe needed', 'pay money to allow to take all feature', 1, '2023-11-05 08:56:28', '2023-11-05 08:56:28'),
(88, 'automatic', 'id_77', '{\"message_id\":9142046734327328167}', 'Subscribe Order ID#29', 'تم الاشتراك في العضوية ', 1, '2023-11-11 15:00:29', '2023-11-11 15:00:29'),
(89, 'automatic', 'role_admin', '{\"message_id\":5681234086200007399}', 'New User Subscribe', 'تم الاشتراك في العضوية ', 1, '2023-11-11 15:00:30', '2023-11-11 15:00:30'),
(90, 'automatic', 'id_79', '{\"message_id\":6948610390234044030}', 'Subscribe Order ID#30', 'تم الاشتراك في العضوية ', 1, '2023-11-11 15:06:04', '2023-11-11 15:06:04'),
(91, 'automatic', 'role_admin', '{\"message_id\":8314216658768216347}', 'New User Subscribe', 'تم الاشتراك في العضوية ', 1, '2023-11-11 15:06:05', '2023-11-11 15:06:05'),
(92, 'automatic', 'id_88', '{\"message_id\":4219066891396370208}', 'Subscribe Order ID#31', 'Subscribe Plan ', 1, '2023-11-12 17:25:13', '2023-11-12 17:25:13'),
(93, 'automatic', 'role_admin', '{\"message_id\":4207818112750620458}', 'New User Subscribe', 'Subscribe Plan ', 1, '2023-11-12 17:25:14', '2023-11-12 17:25:14'),
(94, 'automatic', 'id_101', '{\"message_id\":2929693824117865194}', 'Order #56', 'Your Order Under Preapearing', 1, '2024-01-05 11:34:24', '2024-01-05 11:34:24'),
(95, 'automatic', 'id_88', '{\"message_id\":395906349510161614}', 'Order #56', 'New Order Created', 1, '2024-01-05 11:34:26', '2024-01-05 11:34:26'),
(96, 'automatic', 'role_admin', '{\"message_id\":8285589477828036135}', 'Order #56', 'New Order Created', 1, '2024-01-05 11:34:28', '2024-01-05 11:34:28'),
(97, 'automatic', 'id_88', '{\"message_id\":3858930100891233968}', 'جمعة مباركة', 'صباح الخير', 1, '2024-01-26 08:37:29', '2024-01-26 08:37:29'),
(98, 'automatic', 'all', '{\"message_id\":1250657885068072840}', 'مساء الخير', 'جمعة مباركة', 1, '2024-01-26 08:38:14', '2024-01-26 08:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `provider_id` bigint(20) NOT NULL,
  `payment_method` varchar(256) NOT NULL DEFAULT 'cash',
  `payment_online_id` varchar(256) DEFAULT NULL COMMENT 'transaction id payment',
  `shipment_id` bigint(20) NOT NULL COMMENT 'aramex, ... etc',
  `address_detail` text NOT NULL COMMENT 'user write previous address',
  `city_id` bigint(20) NOT NULL DEFAULT 0,
  `country` varchar(256) NOT NULL COMMENT 'use picker country iso name',
  `product_price` double NOT NULL DEFAULT 0 COMMENT 'total \r\nprice product * qty',
  `vat_price` double NOT NULL DEFAULT 0 COMMENT 'vat value',
  `shipment_price` double NOT NULL DEFAULT 0,
  `net` double NOT NULL DEFAULT 0 COMMENT 'total net',
  `status_order` varchar(256) NOT NULL DEFAULT 'new' COMMENT 'new, preapering, shipping, completed, canceled, deleted',
  `product_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'json qty, productId',
  `fcm_status` tinyint(1) NOT NULL DEFAULT 0,
  `fcm_message_id` text DEFAULT NULL COMMENT 'response return from FCM api	',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_product`
--

INSERT INTO `order_product` (`id`, `user_id`, `provider_id`, `payment_method`, `payment_online_id`, `shipment_id`, `address_detail`, `city_id`, `country`, `product_price`, `vat_price`, `shipment_price`, `net`, `status_order`, `product_detail`, `fcm_status`, `fcm_message_id`, `created`, `updated`) VALUES
(27, 16, 0, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 215, 6.45, 0, 221.45, 'new', '@1=3', 1, '{\"message_id\":93803519059801861}', '2022-06-28 18:00:37', '2022-06-28 18:00:37'),
(28, 16, 0, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 275, 8.25, 0, 283.25, 'new', '@1=3', 1, '{\"message_id\":212192000361101223}', '2022-06-28 21:03:33', '2022-06-28 21:03:33'),
(29, 16, 0, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 20, 0.6, 0, 20.6, 'new', '@1=3', 1, '{\"message_id\":8949440240733740160}', '2022-06-28 21:29:27', '2022-06-28 21:29:27'),
(30, 16, 0, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 20, 0.6, 0, 20.6, 'new', '@1=3', 1, '{\"message_id\":5528258870101799936}', '2022-06-28 21:29:55', '2022-06-28 21:29:55'),
(31, 16, 0, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 20, 0.6, 0, 20.6, 'new', '@1=3', 1, '{\"message_id\":8090442036717342757}', '2022-06-28 21:30:28', '2022-06-28 21:30:28'),
(32, 52, 0, 'cash', NULL, 0, '', 0, '', 255, 7.65, 0, 262.65, 'new', NULL, 1, '{\"message_id\":7195408662843953575}', '2022-06-29 13:16:14', '2022-06-29 13:16:14'),
(33, 16, 0, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 40, 1.2, 0, 41.2, 'new', '@1=3', 1, '{\"message_id\":770393415958207823}', '2022-06-29 13:17:29', '2022-06-29 13:17:29'),
(34, 52, 0, 'cash', NULL, 0, '', 0, '', 40, 1.2, 0, 41.2, 'new', NULL, 1, '{\"message_id\":2237065535602974808}', '2022-06-29 13:26:09', '2022-06-29 13:26:09'),
(35, 16, 0, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 20, 0.6, 0, 20.6, 'new', '@1=3', 1, '{\"message_id\":2537033906064654335}', '2022-06-29 13:27:27', '2022-06-29 13:27:27'),
(36, 52, 0, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 20, 0.6, 0, 20.6, 'new', '@1=3', 1, '{\"message_id\":1333553048988811811}', '2022-06-29 13:29:56', '2022-06-29 13:29:56'),
(37, 52, 0, 'cash', NULL, 0, '', 0, '', 235, 7.05, 0, 242.05, 'new', NULL, 1, '{\"message_id\":1901788567689909729}', '2022-06-29 13:33:15', '2022-06-29 13:33:15'),
(38, 16, 5, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 75, 2.25, 0, 77.25, 'new', '@5=1', 0, NULL, '2023-04-24 12:47:55', '2023-04-24 12:47:55'),
(39, 16, 5, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 75, 2.25, 0, 77.25, 'new', '@5=1', 1, '{\"message_id\":4046116900409470453}', '2023-04-24 12:48:59', '2023-04-24 12:48:59'),
(40, 16, 5, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 75, 2.25, 0, 77.25, 'new', '@5=1', 1, '{\"message_id\":379047721711520183}', '2023-04-24 12:55:19', '2023-04-24 12:55:19'),
(41, 16, 7, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 235, 7.05, 0, 242.05, 'new', '@5=1', 1, '{\"message_id\":9142341321735016550}', '2023-05-02 20:03:42', '2023-05-02 20:03:42'),
(42, 16, 7, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 20, 0.6, 0, 20.6, 'new', '@5=1', 1, '{\"message_id\":6106449925035614041}', '2023-05-02 20:29:03', '2023-05-02 20:29:03'),
(43, 53, 7, 'cash', NULL, 0, '', 0, '', 40, 1.2, 0, 41.2, 'new', NULL, 1, '{\"message_id\":5176012899819582217}', '2023-05-02 20:59:15', '2023-05-02 20:59:15'),
(44, 16, 7, 'cash', NULL, 0, 'heliopliess', 2, 'SA', 20, 0.6, 0, 20.6, 'newOrder', '[MCart{id: 46, userId: 16, productId: 1, counter: 1, updatedAt: 1683059393, product: MProduct{id: 1, categoryId: 2, nameAr: زعانف, nameEn: fins, description_ar: زعانف للمياة من البلاستك, description_en: fins water made from plastic, price: 20, status: 1, hidden: 0, rent: rent, providerId: 7, image: https://umq.app/php/public/images/product/finis1_montage.jpg, createdAt: 1648078301, updatedAt: 1648078301, rate: 0.0, favorite: 1, cartCounter: 1, userId: 16, provider_mobile: +201012345678, provider_country: +20, provider: ProviderProduct{id: 7, whats: 010123456022}, category: MCategory{id: 2, nameEn: Fines, nameAr: زعانف, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}]', 1, '{\"message_id\":2222446785475408083}', '2023-08-01 18:25:29', '2023-08-01 18:25:29'),
(45, 53, 7, 'online', '5NY57478CB795081C', 0, 'macca', 1, 'SA', 1, 0.03, 0, 1.03, 'newOrder', '[MCart{id: 48, userId: 53, productId: 3, counter: 1, updatedAt: 1693657065, product: MProduct{id: 3, categoryId: 1, nameAr: نظارة, nameEn: glass, description_ar: نظارة مياة من البلاستك, description_en: glass water made from plastic, price: 1, status: 1, hidden: 0, rent: rent, providerId: 7, image: https://umq.app/php/public/images/product/glass1.jpg, createdAt: 1648078329, updatedAt: 1648078329, rate: 0.0, favorite: 0, cartCounter: 1, userId: 53, provider_mobile: +201012345678, provider_country: +20, provider: ProviderProduct{id: 7, whats: 010123456022}, category: MCategory{id: 1, nameEn: Glass, nameAr: نظارات, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}]', 1, '{\"message_id\":518030376882555195}', '2023-09-02 12:54:38', '2023-09-02 12:54:38'),
(46, 79, 7, 'cash', NULL, 0, 'macka street', 1, 'SA', 2, 0.06, 0, 2.06, 'newOrder', '[MCart{id: 49, userId: 79, productId: 3, counter: 2, updatedAt: 1693682557, product: MProduct{id: 3, categoryId: 1, nameAr: نظارة, nameEn: glass, description_ar: نظارة مياة من البلاستك, description_en: glass water made from plastic, price: 1, status: 1, hidden: 0, rent: rent, providerId: 7, image: https://umq.app/php/public/images/product/glass1.jpg, createdAt: 1648078329, updatedAt: 1648078329, rate: 0.0, favorite: 0, cartCounter: 2, userId: 79, provider_mobile: +201012345678, provider_country: +20, provider: ProviderProduct{id: 7, whats: 010123456022}, category: MCategory{id: 1, nameEn: Glass, nameAr: نظارات, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}]', 1, '{\"message_id\":5682328953369022599}', '2023-09-02 19:23:44', '2023-09-02 19:23:44'),
(47, 79, 7, 'online', '8L899026P2352515E', 0, 'mskka steet', 1, 'SA', 1, 0.03, 0, 1.03, 'newOrder', '[MCart{id: 50, userId: 79, productId: 3, counter: 1, updatedAt: 1694202238, product: MProduct{id: 3, categoryId: 1, nameAr: نظارة, nameEn: glass, description_ar: نظارة مياة من البلاستك, description_en: glass water made from plastic, price: 1, status: 1, hidden: 0, rent: rent, providerId: 7, image: https://umq.app/php/public/images/product/glass1.jpg, createdAt: 1648078329, updatedAt: 1648078329, rate: 0.0, favorite: 0, cartCounter: 1, userId: 79, provider_mobile: +201012345678, provider_country: +20, provider: ProviderProduct{id: 7, whats: 010123456022}, category: MCategory{id: 1, nameEn: Glass, nameAr: نظارات, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}]', 1, '{\"message_id\":1518352972972245422}', '2023-09-08 20:49:49', '2023-09-08 20:49:49'),
(48, 53, 7, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 4, 0.12, 0, 4.12, 'new', '@5=1', 1, '{\"message_id\":8212216291646309435}', '2023-09-17 19:02:11', '2023-09-17 19:02:11'),
(49, 53, 7, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 4, 0.12, 0, 4.12, 'new', '@5=1', 1, '{\"message_id\":7081780432519533081}', '2023-09-17 19:17:36', '2023-09-17 19:17:36'),
(50, 53, 7, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 4, 0.12, 0, 4.12, 'new', '@5=1', 1, '{\"message_id\":2063021294422361656}', '2023-09-17 19:20:40', '2023-09-17 19:20:40'),
(51, 53, 7, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 4, 0.12, 0, 4.12, 'new', '@5=1', 1, '{\"message_id\":6247394598298241643}', '2023-09-17 19:25:50', '2023-09-17 19:25:50'),
(52, 53, 7, 'cash', '0', 1, '10 Street, Macka', 1, 'SR', 4, 0.12, 0, 4.12, 'new', '@5=1', 1, '{\"message_id\":5042815304342024133}', '2023-09-17 19:27:17', '2023-09-17 19:27:17'),
(53, 53, 7, 'cash', NULL, 0, 'macka', 1, 'SA', 20, 0.6, 0, 20.6, 'newOrder', '[MCart{id: 59, userId: 53, productId: 1, counter: 5, updatedAt: 1694979059, product: MProduct{id: 1, categoryId: 2, nameAr: زعانف, nameEn: fins, description_ar: زعانف للمياة من البلاستك, description_en: fins water made from plastic, price: 4, status: 1, hidden: 0, rent: rent, providerId: 7, image: https://umq.app/php/public/images/product/finis1_montage.jpg, createdAt: 1648078301, updatedAt: 1648078301, rate: 0.0, favorite: 1, cartCounter: 5, userId: 53, provider_mobile: +201012345678, provider_country: +20, provider: ProviderProduct{id: 7, whats: 010123456022}, category: MCategory{id: 2, nameEn: Fines, nameAr: زعانف, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}]', 1, '{\"message_id\":6275879456083357710}', '2023-09-18 20:22:44', '2023-09-18 20:22:44'),
(54, 77, 45, 'cash', NULL, 0, 'egypt', 1, 'SA', 10, 0.3, 0, 10.3, 'newOrder', '[MCart{id: 64, userId: 77, productId: 17, counter: 1, updatedAt: 1697197331, product: MProduct{id: 17, categoryId: 1, nameAr: ar, nameEn: en, description_ar: ar, description_en: des, price: 10, status: 1, hidden: 0, rent: rent, providerId: 45, image: https://umq.app/php/storage/uploads/2023_10_13__08_54_31__5e25273c9d5dc2ea6b62735de9b4aa2b.jpg, createdAt: 1697187281, updatedAt: 1697187281, rate: 0.0, favorite: 0, cartCounter: 1, userId: 77, provider_mobile: +201063499772, provider_country: +20, provider: ProviderProduct{id: 45, whats: 010}, category: MCategory{id: 1, nameEn: Glass, nameAr: نظارات, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}]', 0, 'api key is empty', '2023-10-13 19:10:05', '2023-10-13 19:10:05'),
(55, 16, 45, 'cash', NULL, 0, 'macka street 145, floor 2', 1, 'SA', 14, 0.42, 0, 14.42, 'newOrder', '[MCart{id: 63, userId: 16, productId: 16, counter: 3, updatedAt: 1696703700, product: MProduct{id: 16, categoryId: 1, nameAr: postman ar, nameEn: postman en, description_ar: postman description_ar, description_en: postman description_en, price: 3, status: 0, hidden: 1, rent: sell, providerId: 45, image: https://umq.app/php/storage/uploads/2023_10_07__15_40_32__7e1c7c435797e856610d4016d871ec46.jpg, createdAt: 1696702644, updatedAt: 1696702644, rate: 0.0, favorite: 1, cartCounter: 3, userId: 16, provider_mobile: +201063499772, provider_country: +20, provider: ProviderProduct{id: 45, whats: 010}, category: MCategory{id: 1, nameEn: Glass, nameAr: نظارات, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}, MCart{id: 65, userId: 16, productId: 17, counter: 1, updatedAt: 1697805876, product: MProduct{id: 17, categoryId: 1, nameAr: fines abdallah, nameEn: abdallah fines, description_ar: fine good, description_en: fines good, price: 5, status: 1, hidden: 1, rent: rent, providerId: 45, image: https://umq.app/php/storage/uploads/2023_10_20__16_41_24__ffdb703c632f19027ecae071c99f146f.png, createdAt: 1697187281, updatedAt: 1697187281, rate: 0.0, favorite: 0, cartCounter: 1, userId: 16, provider_mobile: +201063499772, provider_country: +20, provider: ProviderProduct{id: 45, whats: 010}, category: MCategory{id: 1, nameEn: Glass, nameAr: نظارات, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}]', 1, '{\"message_id\":3372773404831219525}', '2023-11-03 19:53:28', '2023-11-03 19:53:28'),
(56, 101, 53, 'cash', NULL, 0, 'مكة', 1, 'SA', 1, 0.03, 0, 1.03, 'newOrder', '[MCart{id: 74, userId: 101, productId: 27, counter: 1, updatedAt: 1704454305, product: MProduct{id: 27, categoryId: 9, nameAr: نظارة غوص, nameEn: musk, description_ar: أ         ا, description_en: a     a, price: 1, status: 1, hidden: 0, rent: sell, providerId: 53, image: https://umq.app/php/storage/uploads/2024_01_05__11_29_27__1a641217d0f0ffcbfe520299c5f99ba3.jpg, createdAt: 1704454257, updatedAt: 1704454257, rate: 0.0, favorite: 0, cartCounter: 1, userId: 101, provider_mobile: +966541661331, provider_country: +966, provider: ProviderProduct{id: 53, whats: 010}, category: MCategory{id: 9, nameEn: glass, nameAr: نضارة, descriptionEn: null, descriptionAr: null, hidden: null, status: null, image: null, createdAt: null, updatedAt: null}}}]', 1, '{\"message_id\":2929693824117865194}', '2024-01-05 11:34:22', '2024-01-05 11:34:22');

-- --------------------------------------------------------

--
-- Table structure for table `order_vendor`
--

CREATE TABLE `order_vendor` (
  `id` bigint(20) NOT NULL,
  `order_product_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'from table "order product"',
  `provider_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'vendor id',
  `user_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'customer id',
  `product_id` bigint(20) NOT NULL DEFAULT 0,
  `product_price` float NOT NULL DEFAULT 0 COMMENT 'history of price',
  `product_qty` int(11) NOT NULL,
  `provider_status` varchar(256) NOT NULL COMMENT 'new, prepared, shipment, completed, cancel_provider, cancel_admin',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_vendor`
--

INSERT INTO `order_vendor` (`id`, `order_product_id`, `provider_id`, `user_id`, `product_id`, `product_price`, `product_qty`, `provider_status`, `created`, `updated`) VALUES
(1, 22, 7, 16, 1, 20, 1, 'new', '2022-06-22 18:20:22', '2022-06-22 18:20:22'),
(2, 23, 7, 16, 1, 20, 3, 'new', '2022-06-22 18:25:07', '2022-06-22 18:25:07'),
(3, 23, 7, 16, 2, 215, 1, 'new', '2022-06-22 18:25:07', '2022-06-22 18:25:07'),
(4, 24, 7, 16, 1, 20, 2, 'new', '2022-06-26 23:30:40', '2022-06-26 23:30:40'),
(5, 25, 7, 16, 1, 20, 2, 'new', '2022-06-27 00:35:50', '2022-06-27 00:35:50'),
(6, 26, 7, 16, 1, 20, 1, 'new', '2022-06-28 08:52:19', '2022-06-28 08:52:19'),
(7, 27, 7, 16, 2, 215, 1, 'new', '2022-06-28 18:00:38', '2022-06-28 18:00:38'),
(8, 28, 7, 16, 2, 215, 1, 'new', '2022-06-28 21:03:33', '2022-06-28 21:03:33'),
(9, 28, 7, 16, 1, 20, 3, 'new', '2022-06-28 21:03:33', '2022-06-28 21:03:33'),
(10, 29, 7, 16, 1, 20, 1, 'new', '2022-06-28 21:29:27', '2022-06-28 21:29:27'),
(11, 30, 7, 16, 1, 20, 1, 'new', '2022-06-28 21:29:55', '2022-06-28 21:29:55'),
(12, 31, 7, 16, 1, 20, 1, 'new', '2022-06-28 21:30:28', '2022-06-28 21:30:28'),
(13, 32, 7, 52, 1, 20, 2, 'new', '2022-06-29 13:16:14', '2022-06-29 13:16:14'),
(14, 32, 7, 52, 2, 215, 1, 'new', '2022-06-29 13:16:14', '2022-06-29 13:16:14'),
(15, 33, 7, 16, 1, 20, 2, 'new', '2022-06-29 13:17:29', '2022-06-29 13:17:29'),
(16, 34, 7, 52, 1, 20, 2, 'new', '2022-06-29 13:26:09', '2022-06-29 13:26:09'),
(17, 35, 7, 16, 1, 20, 1, 'new', '2022-06-29 13:27:27', '2022-06-29 13:27:27'),
(18, 36, 7, 52, 1, 20, 1, 'new', '2022-06-29 13:29:56', '2022-06-29 13:29:56'),
(19, 37, 7, 52, 1, 20, 1, 'new', '2022-06-29 13:33:15', '2022-06-29 13:33:15'),
(20, 37, 7, 52, 2, 215, 1, 'new', '2022-06-29 13:33:15', '2022-06-29 13:33:15'),
(21, 39, 5, 16, 4, 75, 1, 'new', '2023-04-24 12:48:59', '2023-04-24 12:48:59'),
(22, 40, 5, 16, 4, 75, 1, 'new', '2023-04-24 12:55:19', '2023-04-24 12:55:19'),
(23, 41, 7, 16, 2, 215, 1, 'new', '2023-05-02 20:03:42', '2023-05-02 20:03:42'),
(24, 41, 7, 16, 1, 20, 1, 'new', '2023-05-02 20:03:42', '2023-05-02 20:03:42'),
(25, 42, 7, 16, 1, 20, 1, 'new', '2023-05-02 20:29:03', '2023-05-02 20:29:03'),
(26, 43, 7, 53, 1, 20, 2, 'new', '2023-05-02 20:59:15', '2023-05-02 20:59:15'),
(27, 44, 7, 16, 1, 20, 1, 'new', '2023-08-01 18:25:29', '2023-08-01 18:25:29'),
(28, 45, 7, 53, 3, 1, 1, 'new', '2023-09-02 12:54:38', '2023-09-02 12:54:38'),
(29, 46, 7, 79, 3, 1, 2, 'new', '2023-09-02 19:23:44', '2023-09-02 19:23:44'),
(30, 47, 7, 79, 3, 1, 1, 'new', '2023-09-08 20:49:50', '2023-09-08 20:49:50'),
(31, 48, 7, 53, 1, 4, 1, 'new', '2023-09-17 19:02:11', '2023-09-17 19:02:11'),
(32, 49, 7, 53, 1, 4, 1, 'new', '2023-09-17 19:17:36', '2023-09-17 19:17:36'),
(33, 50, 7, 53, 1, 4, 1, 'new', '2023-09-17 19:20:40', '2023-09-17 19:20:40'),
(34, 51, 7, 53, 1, 4, 1, 'new', '2023-09-17 19:25:50', '2023-09-17 19:25:50'),
(35, 52, 7, 53, 1, 4, 1, 'new', '2023-09-17 19:27:17', '2023-09-17 19:27:17'),
(36, 53, 7, 53, 1, 4, 5, 'new', '2023-09-18 20:22:44', '2023-09-18 20:22:44'),
(37, 54, 45, 77, 17, 10, 1, 'new', '2023-10-13 19:10:05', '2023-10-13 19:10:05'),
(38, 55, 45, 16, 16, 3, 3, 'new', '2023-11-03 19:53:28', '2023-11-03 19:53:28'),
(39, 55, 45, 16, 17, 5, 1, 'new', '2023-11-03 19:53:28', '2023-11-03 19:53:28'),
(40, 56, 53, 101, 27, 1, 1, 'new', '2024-01-05 11:34:22', '2024-01-05 11:34:22');

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'club', 1, '2022-04-04 13:22:22', '2022-04-04 13:22:25'),
(2, 'Gold Metal', 1, '2022-04-04 13:22:07', '2022-04-04 13:22:07'),
(3, 'Honda 2', 0, '2022-04-20 19:38:43', '2022-04-20 19:38:43');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_ar` text NOT NULL,
  `description_en` text NOT NULL,
  `price` float NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `rent` varchar(30) NOT NULL DEFAULT 'sell',
  `provider_id` bigint(20) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category_id`, `name_ar`, `name_en`, `description_ar`, `description_en`, `price`, `status`, `hidden`, `rent`, `provider_id`, `image`, `created_at`, `updated_at`) VALUES
(1, 2, 'زعانف', 'fins', 'زعانف للمياة من البلاستك', 'fins water made from plastic', 4, 1, 1, 'rent', 7, 'https://umq.app/php/public/images/product/finis1_montage.jpg', '2022-03-23 23:31:41', '2022-03-23 23:31:41'),
(2, 2, 'زعانف', 'fins', 'زعانف للمياة من البلاستك', 'fins water made from plastic', 5, 1, 0, 'sell', 7, 'https://umq.app/php/public/images/product/finis2_montage.jpg', '2022-03-23 23:31:43', '2022-03-23 23:31:43'),
(3, 1, 'نظارة', 'glass', 'نظارة مياة من البلاستك', 'glass water made from plastic', 1, 1, 0, 'rent', 7, 'https://umq.app/php/public/images/product/glass1.jpg', '2022-03-23 23:32:09', '2022-03-23 23:32:09'),
(4, 1, 'نظارة', 'glass', 'نظارة مياة من البلاستك', 'glass water made from plastic', 2, 1, 0, 'sell', 5, 'https://umq.app/php/public/images/product/glass2.jpg', '2022-03-23 23:32:10', '2022-03-23 23:32:10'),
(27, 9, 'نظارة غوص', 'musk', 'أ         ا', 'a     a', 1, 1, 0, 'sell', 53, 'https://umq.app/php/storage/uploads/2024_01_05__11_29_27__1a641217d0f0ffcbfe520299c5f99ba3.jpg', '2024-01-05 11:30:57', '2024-01-05 11:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `price` bigint(20) DEFAULT NULL,
  `provider_id` bigint(20) DEFAULT NULL,
  `service_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider`
--

CREATE TABLE `provider` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `whats` varchar(30) NOT NULL,
  `city_id` bigint(20) NOT NULL,
  `org_id` int(11) NOT NULL,
  `block` tinyint(1) NOT NULL DEFAULT 0,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `location_name` varchar(255) DEFAULT NULL,
  `lat` varchar(50) DEFAULT NULL,
  `lng` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `provider`
--

INSERT INTO `provider` (`id`, `user_id`, `whats`, `city_id`, `org_id`, `block`, `hidden`, `location_name`, `lat`, `lng`, `created_at`, `updated_at`) VALUES
(5, 16, '01012345602', 11, 2, 0, 1, 'Maka', '', NULL, '2022-03-23 21:54:21', '2022-03-23 21:54:21'),
(7, 18, '010123456022', 11, 2, 0, 1, 'Maka', '21.423071137210776', '39.8257025104764', '2022-03-23 21:55:27', '2022-03-23 21:55:27'),
(45, 77, '010', 2, 2, 0, 0, NULL, NULL, NULL, '2022-06-29 16:08:43', '2022-06-29 16:08:43'),
(46, 80, '010', 1, 2, 0, 0, NULL, NULL, NULL, '2023-09-19 17:39:11', '2023-09-19 17:39:11'),
(49, 86, '010', 2, 2, 0, 1, NULL, NULL, NULL, '2023-09-28 17:09:19', '2023-09-28 17:09:19'),
(50, 78, '010', 1, 1, 0, 0, NULL, NULL, NULL, '2023-10-05 12:23:18', '2023-10-05 12:23:18'),
(52, 79, '010', 1, 1, 0, 1, NULL, NULL, NULL, '2023-10-20 04:10:26', '2023-10-20 04:10:26'),
(53, 88, '010', 11, 1, 0, 0, NULL, NULL, NULL, '2023-11-10 10:46:49', '2023-11-10 10:46:49'),
(54, 94, '010', 1, 1, 0, 0, NULL, NULL, NULL, '2023-11-12 15:17:51', '2023-11-12 15:17:51'),
(56, 100, '010', 2, 0, 0, 0, NULL, NULL, NULL, '2023-12-29 20:32:40', '2023-12-29 20:32:40'),
(57, 97, '010', 11, 2, 0, 0, NULL, NULL, NULL, '2024-01-17 12:49:13', '2024-01-17 12:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `rate_product`
--

CREATE TABLE `rate_product` (
  `id` bigint(20) NOT NULL,
  `value` float NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rate_provider`
--

CREATE TABLE `rate_provider` (
  `id` bigint(20) NOT NULL,
  `value` float NOT NULL,
  `provider_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rate_provider`
--

INSERT INTO `rate_provider` (`id`, `value`, `provider_id`, `user_id`, `created_at`) VALUES
(11, 5, 7, 58, '2022-04-27 03:20:16'),
(12, 5, 2, 58, '2022-04-27 03:26:58'),
(13, 5, 44, 70, '2022-05-10 06:03:48'),
(14, 5, 5, 71, '2022-05-10 06:04:15'),
(15, 2, 5, 70, '2022-05-10 06:04:57'),
(16, 5, 2, 15, '2022-06-29 18:48:49'),
(17, 5, 2, 78, '2023-10-05 12:23:48'),
(18, 5, 46, 78, '2023-10-06 14:06:38'),
(19, 5, 46, 80, '2023-10-24 09:13:35'),
(20, 5, 45, 88, '2023-11-05 15:43:57'),
(21, 5, 45, 16, '2023-11-09 07:43:20'),
(22, 5, 5, 16, '2023-11-09 08:06:59'),
(23, 5, 7, 16, '2023-11-09 08:11:04'),
(24, 5, 50, 97, '2024-01-03 10:53:12'),
(25, 5, 53, 97, '2024-01-03 10:56:05'),
(26, 5, 56, 97, '2024-01-03 16:23:49'),
(27, 5, 56, 101, '2024-01-03 16:30:38'),
(28, 5, 57, 97, '2024-01-17 12:49:30');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_admin`
--

CREATE TABLE `setting_admin` (
  `id` int(11) NOT NULL,
  `key` varchar(256) DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setting_admin`
--

INSERT INTO `setting_admin` (`id`, `key`, `value`) VALUES
(3, 'about_us_html', 'The fastest-growing dive trainers in the world! We have the privilege of being assigned the status of “Searching Instructor Center”.\n\nThis means we are recognized in our training standards & quality, to allow us to educate the next generation of trainers.'),
(4, 'terms_html', 'App Terms & Conditions\n\nBy downloading or using the app, these terms will automatically apply to your account, you should make sure therefore that you read them carefully before using the app. \n\nYou are not allowed to copy or modify the app, any part of the app, or our trademarks in any way. \n\nYou are not allowed to attempt to extract the source code of the app, and you also shouldnot try to translate the app into other languages or make derivative versions. \n\nResponsibility for your use of the app, when you are using the app, its important to bear in mind that although we endeavor to ensure that it is updated and correct at all times, we do rely on third parties to provide information to us so that we can make it available to you. Diving Solutions\n\n\n\n\nPrivacy And Policy. \n\n\nWe Asking For some permission in Mobile App these is reason in points\n\nPermission Photo attached and camera: for allow both students and trainers to chat and attach favorite images, videos and Also allow to set the avatar profile photo.\nPermission Record voice: this app used for connect students and trainers in one app for learning. Record voice is very useful in chat between trainer and student.\nPermission notification have been used in chat.\nPermission camera to attach avatar and photos to be set in your account.\nPermission phone call to allow call directly between students and trainers.'),
(7, 'about_us_html_arabic', 'مدربي الغوص الأسرع نمواً في العالم! لدينا امتياز تعيين حالة \"اكبر مركز بحث في العالم\" عن المدربين.\n\nوهذا يعني أننا معترف بهم في معايير التدريب والجودة لدينا، للسماح لنا بتعليم الجيل القادم من المدربين.'),
(8, 'terms_html_arabic', 'شروط وأحكام التطبيق\n\nمن خلال تنزيل التطبيق أو استخدامه، سيتم تطبيق هذه الشروط تلقائيًا على حسابك، لذا يجب عليك التأكد من قراءتها بعناية قبل استخدام التطبيق.\n\nلا يُسمح لك بنسخ أو تعديل التطبيق أو أي جزء من التطبيق أو علاماتنا التجارية بأي شكل من الأشكال.\n\nلا يُسمح لك بمحاولة استخراج الكود المصدري للتطبيق، ولا يجوز لك أيضًا محاولة ترجمة التطبيق إلى لغات أخرى أو إنشاء إصدارات مشتقة.\n\nالمسؤولية عن استخدامك للتطبيق، عندما تستخدم التطبيق، من المهم أن تضع في اعتبارك أنه على الرغم من أننا نسعى لضمان تحديثه وتصحيحه في جميع الأوقات، إلا أننا نعتمد على أطراف ثالثة لتزويدنا بالمعلومات حتى نتمكن من يمكننا أن نجعلها متاحة لك. حلول الغوص\n\n\n\n\nالخصوصية والسياسة.\n\n\nنحن نطلب بعض الإذن في تطبيق الهاتف المحمول، وهذا هو السبب في بعض النقاط\n\nإذن إرفاق الصورة والكاميرا: للسماح لكل من الطلاب والمدربين بالدردشة وإرفاق الصور ومقاطع الفيديو المفضلة والسماح أيضًا بتعيين صورة الملف الشخصي الرمزية.\nإذن تسجيل الصوت: يستخدم هذا التطبيق لربط الطلاب والمدربين في تطبيق واحد للتعلم. يعد تسجيل الصوت مفيدًا جدًا في الدردشة بين المدرب والطالب.\nتم استخدام إشعار الإذن في الدردشة.\nإذن للكاميرا بإرفاق الصورة الرمزية والصور التي سيتم تعيينها في حسابك.\nإذن مكالمة هاتفية للسماح بالاتصال مباشرة بين الطلاب والمدربين.'),
(9, 'payment_subscribe_status', 'prevent'),
(10, 'paypal_domain', 'https://scuba-world.net/paypal');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `object_type` varchar(30) NOT NULL DEFAULT 'product',
  `provider_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `image`, `object_type`, `provider_id`, `product_id`, `status`, `created_at`, `updated_at`) VALUES
(7, 'https://umq.app/php/public/images/slider/ad1_montage.jpg', 'provider', 2, NULL, 1, '2022-03-23 21:54:21', '2022-03-23 21:54:21'),
(14, 'https://umq.app/php/public/images/slider/ad2_montage.jpg', 'provider', 7, NULL, 1, '2022-03-23 22:41:53', '2022-03-23 22:41:53'),
(15, 'https://scuba-ksa.com/storage/uploads/2022_06_28__15_53_46__7e9c02c22192673789da71b9b3747472.jpg', 'product', NULL, NULL, 0, '2022-06-28 15:53:15', '2022-06-28 15:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `subscribe_package`
--

CREATE TABLE `subscribe_package` (
  `id` int(11) NOT NULL,
  `name_en` varchar(256) NOT NULL,
  `name_ar` varchar(256) NOT NULL,
  `description_en` text NOT NULL,
  `description_ar` text NOT NULL,
  `price` float NOT NULL,
  `period` int(11) NOT NULL COMMENT 'days period give to you',
  `allowed_product_numers` int(11) NOT NULL,
  `allowed_chat` tinyint(1) NOT NULL,
  `color` varchar(16) DEFAULT NULL COMMENT 'hexcode of package color. like package Gold make color yellow',
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `created` timestamp NULL DEFAULT current_timestamp(),
  `updated` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribe_package`
--

INSERT INTO `subscribe_package` (`id`, `name_en`, `name_ar`, `description_en`, `description_ar`, `price`, `period`, `allowed_product_numers`, `allowed_chat`, `color`, `hidden`, `created`, `updated`) VALUES
(1, 'GOLD', 'الذهبية', '* One Year For Low Price\n* Allowed To Add 1000 Product\n* Contact With Student Directly In Chat', '* سنة كاملة بسعر رخيص\n* صلاحية اضافة 1000 منتج\n* الحصول علي ميزة التواصل المباشرة مع الطلاب بالشات', 100, 360, 1000, 1, '#fff3c421', 0, '2023-09-22 08:07:45', '2023-09-22 08:07:45'),
(2, 'SILVER', 'الفضية', '* SIX MONTH For Low Price', '* سنة نصف بسعر رخيص', 60, 180, 10, 1, '#ff999a93', 0, '2023-09-22 08:22:58', '2023-09-22 08:22:58'),
(7, 'Free', 'مجانا', 'Trying All Feature For One Month For Free', 'جرب كل المميزات لمدة شهر مجانا', 0, 30, 30, 0, '#ff2196f3', 0, '2023-09-22 16:28:27', '2023-09-22 16:28:27'),
(8, 'fff', 'test ar', 'des e', 'des ar', 1, 10, 1, 1, '#ff4af321', 1, '2023-10-04 05:04:32', '2023-10-04 05:04:32');

-- --------------------------------------------------------

--
-- Table structure for table `subscribe_user`
--

CREATE TABLE `subscribe_user` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(256) NOT NULL COMMENT 'history information about user, to avoid user edit his information in the future',
  `user_phone` varchar(256) NOT NULL COMMENT 'history information about user, to avoid user edit his information in the future',
  `user_image` varchar(256) DEFAULT NULL COMMENT 'history information about user, to avoid user edit his information in the future',
  `package_id` int(11) NOT NULL,
  `package_name` varchar(256) DEFAULT NULL COMMENT 'save history of package when user buying, due to this information may change in the future',
  `package_period` int(11) DEFAULT 0 COMMENT 'how many days package offers.  save history of package when user buying, due to this information may change in the future',
  `package_allowed_product_numers` int(11) NOT NULL DEFAULT 0 COMMENT 'save history of package , to avoid edit change this data in the future',
  `package_allowed_chat` tinyint(4) DEFAULT 0 COMMENT 'save history of package , to avoid edit change this data in the future',
  `price` float NOT NULL,
  `by_admin` tinyint(4) NOT NULL COMMENT 'is by admin subscribe',
  `payment_method` varchar(256) DEFAULT NULL,
  `payment_transaction_id` varchar(256) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(30) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `fid` varchar(255) NOT NULL,
  `city_id` bigint(20) NOT NULL,
  `country` varchar(10) NOT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'customer',
  `block` tinyint(1) NOT NULL DEFAULT 0,
  `hidden` tinyint(1) DEFAULT 0,
  `photo` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `email_verified_at`, `password`, `remember_token`, `fid`, `city_id`, `country`, `role`, `block`, `hidden`, `photo`, `created_at`, `updated_at`) VALUES
(16, 'Admin Abdallah', 'admin.abdallah@gmail.com', '+201063499771', NULL, '$2y$10$ANnYY3JFHOjIkHDH2dlFfuyXolASQ8nnNm..uBcmE0yVtwIQfgeV6', NULL, 'adminFid', 1, '+20', 'admin', 0, 0, 'https://umq.app/php/public/images/user/admin2.jpg', '2022-03-23 21:54:21', '2022-03-23 21:54:21'),
(18, 'Jon Scuba Man', 'jon.scuba.man1@gmail.com', '+201012345678', NULL, '$2y$10$US4nviKyklp0RTE57rLd3e88HLpspR0qk34AnM1Ee1RF5UpBbZYQm', NULL, 'firebaseId_after_login_by_phone', 1, '+20', 'provider', 0, 1, 'https://umq.app/php/public/images/user/provider1.jpg', '2022-03-23 21:54:21', '2022-03-23 21:54:21'),
(78, 'murelele@yahoo.com', 'Maher123', '+966569679789', '2022-06-30 00:00:00', '$2y$10$jgf9WTjOQuvq1MYWxNhr6OOVU8H9kQTgv1YBJalbl2c8CMEFy.q.O', NULL, 'djuaus-rHUxmjzmfahuJOQ:APA91bEFl42e3YLlnIff1oHv6J818-xS5Z6kfJOMlu_Ixu9hnWq-SstiMmSQcrTnQ3sUaI01wxqUa8klQ4ExiBVLX-oLviiIiiRHbks0Zkl0LgPhEodEtayke6f36n60CL5X4YDMLzC-', 1, '+966', 'admin', 0, 0, 'https://umq.app/php/storage/uploads/2023_10_21__08_06_49__ef01773bcfba7adbc8e19f714acf59be.jpg', '2022-06-30 14:20:07', '2022-06-30 14:20:07'),
(80, 'Faiez', 'adilh.sa@gmail.com', '+201557223787', '2023-09-19 00:00:00', '$2y$10$DenH7m0TQQxne.I7fJ3nQucoJFS/5qx8rPrNmhOextVBNmaCw1pvK', NULL, 'dhraUQrLQvy7dEc2xJP_wW:APA91bFdcSoGvCuIUV8JvhwQoiWQtV3xSAv7IgzQA6WAstBX4wmEeLf_uAqYE_4bBddsnM8wC05Ua9rgknBNIXpREyqQ6iZ2FH-MvJGCHIe7idmZQ8TM7UdXxPtoxtoIkZyd0WzXNrp7', 1, '+20', 'provider', 0, 0, NULL, '2023-09-19 16:51:37', '2023-09-19 16:51:37'),
(86, 'abdallah 75', 'abdallah75@gmail.com', '+2000000000', '2023-09-28 00:00:00', '$2y$10$vDYuiS3EIE3EkWI6WF8dyOrwfV8svdi9gF8NuhWfFakMJDlKgk1U6', NULL, 'e_wIvFa3RySeP_sNvMBaKc:APA91bH0nJ5lkDS_DzT5-WPL38b25ZiLzYCju2AMACuwTprxtXt1m0VOapSJafgG1AnjOl78VdtUt98OLun018bbb1rFZqVnEJ-ZzUfoEzNiCW9Kf8V-QvOIZVKaL5ZhzLkkjZNfr_lo', 2, '+20', 'provider', 0, 1, 'https://umq.app/php/storage/uploads/2023_09_28__17_09_05__c27a0c9fc6b04d80aea77cc2f64d6fc9.jpg', '2023-09-28 17:08:30', '2023-09-28 17:08:30'),
(88, 'ماهر سعيد', 'murelele@yahoo.com', '+966541661331', '2023-11-05 00:00:00', '$2y$10$36KfTJh4M2qBKguKY8m0Hulpg5V.Njd/2jtXqJ1JAigWWja9ILdbq', NULL, 'cP8DMPA5kU_1nQowXu_6br:APA91bFgmTkYs_rtthsKqSUn0g1ma5PyPGU4EH8w6I3vHhvICO28QJ8zBQjJN05AxyhYftM43GOpj1_dKQsWJL2e4MahV69p7jwnS-N56K-EtCoUCreUW4BRoo10r1P9PbJcqAMQDl9r', 11, '+966', 'admin', 0, 0, 'https://umq.app/php/storage/uploads/2023_12_31__21_31_52__759ebbc9cb021203797b50e1dbb47200.jpg', '2023-11-05 15:37:22', '2023-11-05 15:37:22'),
(89, 'Mimi', 'santasouth2013@gmail.com', '+16693334215', '2023-11-06 00:00:00', '$2y$10$MwwSg9rL5GW7w4XjYOKu0uCWX79iMnHEPk0WuVxUGOA1DyBBkf13q', NULL, 'dpZJY0J7pU-sl45TO2E16s:APA91bHRljZ0hBMsymLK6ZSIZIGNRN0KBhXURtF7VoR7U4z7LKq6AZFXsxybT0wJom0rrX8kYpQ6e-ntnkAh0iH5LokjGhIgFqMXwIW_KIBO8phqYVwXEF-6hdSEU1VsLGAHoK-MT5rV', 0, '+1', 'student', 0, 0, NULL, '2023-11-06 10:39:28', '2023-11-06 10:39:28'),
(96, NULL, NULL, '+966569679780', '2023-12-16 00:00:00', '$2y$10$ekf.A8pVveaqoethLS2hh.kDMANZEsiGiinXwbGpAZWaWzN2WM8kK', NULL, 'use8sZUnikUeEDhkDvB09ezWxwS2', 0, '+966', 'student', 0, 1, NULL, '2023-12-16 17:55:20', '2023-12-16 17:55:20'),
(97, 'فايز', 'adilh.sa@gmail.com', '+966590791510', '2023-12-21 00:00:00', '$2y$10$yvWJwYx8vz.X7aVHMyEnVuYwrfEVFkYDyHZaeEKvMG0bgMNuLSbcW', NULL, 'cea2SqQSQfCkpUIA6uCLVU:APA91bGjwYmc3VfvGPitTGFby724YzM0v8Kl6WO_rwbg_LXgD7f-YEZ7UXyqUb-4PjokH9pZ03A-Ev-LzVRC1YqF7VU_FAGSYJdsHY2m8XY96_xn0TF6XdDNvPqcXTzra0_4vPz706JT', 11, '+966', 'provider', 0, 0, NULL, '2023-12-21 05:05:33', '2023-12-21 05:05:33'),
(98, 'jana ghina', 'murelele@yahoo.com', '+966569060269', '2023-12-22 00:00:00', '$2y$10$TVbom2LLwvQsxjFNTUQoTOAScvJWywVstCgD2uygiofGuoOZAuXW6', NULL, 'eMSJKzKvQsONXLrZrbO9hI:APA91bFMdp6q_s1EHrSCWH6CHqefBFpPW69_vIAKKqBJUdCoXHMmq5k63goVeRww4o3q0sd0mb0AlD_z3EeOW2Me6CtMMz44rkBcYWkbjh95P5uHiNJ6J_6IaHyv_2XJUIC5uofCUlhL', 0, '+966', 'student', 0, 0, NULL, '2023-12-22 11:14:50', '2023-12-22 11:14:50'),
(99, NULL, NULL, '+201144758348', '2023-12-29 00:00:00', '$2y$10$UxcWuVyzsH7gwfIniS2S8u8RXy96kXHWfiuaBxDeh5coKl4OkiLlq', NULL, 'd7xGQUGIQna7sGbZI9sf4y:APA91bGOolGROtMzz51VZARZshwi6haRNhbqDEH4VBMa0bWXXiuvXl4AlVLRilbCQbqNfuotDlV8uKQXogz3YQ9eNHw1VJ5uGdXhRAmx1mX51cB_EU4BYnFTEWJSHaK-Lgii_6mmDg-E', 0, '+20', 'student', 0, 0, NULL, '2023-12-29 20:17:33', '2023-12-29 20:17:33'),
(100, 'Medhat kamel', 'medkam1216@gmail.com', '+966542902135', '2023-12-29 00:00:00', '$2y$10$O3DnPWlcZzrBNU38huEzk.D97x3kXuLLBk07glr3YKe2OrXqd3buW', NULL, 'd7xGQUGIQna7sGbZI9sf4y:APA91bGOolGROtMzz51VZARZshwi6haRNhbqDEH4VBMa0bWXXiuvXl4AlVLRilbCQbqNfuotDlV8uKQXogz3YQ9eNHw1VJ5uGdXhRAmx1mX51cB_EU4BYnFTEWJSHaK-Lgii_6mmDg-E', 2, '+966', 'provider', 0, 0, 'https://umq.app/php/storage/uploads/2024_01_01__19_35_49__3ee96a80a1176ad5ad18e84072308fcf.jpg', '2023-12-29 20:24:45', '2023-12-29 20:24:45'),
(101, 'ام منصور', 'murelele@yahoo.com', '+966561121156', '2024-01-03 00:00:00', '$2y$10$Y53VFvG.UTmujuTl2fV3deDB8IgH9GMAOS7IR0MY79/scsVpgUTNW', NULL, 'cP8DMPA5kU_1nQowXu_6br:APA91bFgmTkYs_rtthsKqSUn0g1ma5PyPGU4EH8w6I3vHhvICO28QJ8zBQjJN05AxyhYftM43GOpj1_dKQsWJL2e4MahV69p7jwnS-N56K-EtCoUCreUW4BRoo10r1P9PbJcqAMQDl9r', 0, '+966', 'student', 0, 0, NULL, '2024-01-03 16:26:14', '2024-01-03 16:26:14'),
(102, 'hamid', 'hm8335651@gmail.com', '+966598864822', '2024-01-07 00:00:00', '$2y$10$C5YFPRpUjAe79dxZb7Y/yOZMUQulwXftvH00Fq.7L8vAt7djxHKx.', NULL, 'dMNoxLgTjU2mr75iRmeUO9:APA91bGObXdmQnVnCwd0obuEcgI7tG0ESyYQ5jXEi3QeEY-5hXUs_Lt8GecIrmoKwQK_QJTWjg2chMj9sFwe92TKv--T0U_7ZJkfSv2kILCmX5kXh9J8bFRdwsv8puT_MwP62-WAU8ry', 0, '+966', 'student', 0, 0, NULL, '2024-01-07 19:20:17', '2024-01-07 19:20:17'),
(106, 'Abdo AlGhouls', 'abdo.alghouul@gmail.com', '123123', NULL, '$2y$10$SNvqPp6EE6.XS1i2wMYWreKZqGV5rtzBSQcEOjGraolXQoPZv6Ca.', NULL, '', 0, '', 'admin', 0, 0, NULL, '2024-01-28 01:15:09', '2024-01-28 01:15:09'),
(107, 'Abdo AlGhouls', 'admin@admin.com', '1231232', NULL, '$2y$10$wE4hFpdp3AHESMZM2UkqbOE.vv1GZsAsxakkBKcqkg6kmszVdDU9G', NULL, '', 0, '', 'provider', 0, 0, NULL, '2024-01-28 01:25:23', '2024-01-28 01:25:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_product`
--
ALTER TABLE `cart_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userCart` (`user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_key` (`group_key`);

--
-- Indexes for table `chat_user`
--
ALTER TABLE `chat_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fav_gallery_image`
--
ALTER TABLE `fav_gallery_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favImage` (`user_id`);

--
-- Indexes for table `fav_gallery_video`
--
ALTER TABLE `fav_gallery_video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favVideo` (`user_id`);

--
-- Indexes for table `fav_product`
--
ALTER TABLE `fav_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favProduct` (`user_id`);

--
-- Indexes for table `fav_provider`
--
ALTER TABLE `fav_provider`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favProvider` (`user_id`);

--
-- Indexes for table `gallery_image`
--
ALTER TABLE `gallery_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleryImageProvider` (`provider_id`);

--
-- Indexes for table `gallery_video`
--
ALTER TABLE `gallery_video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleryVideoProvider` (`provider_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_admin`
--
ALTER TABLE `notification_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_vendor`
--
ALTER TABLE `order_vendor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productForProviderId` (`provider_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`),
  ADD KEY `constraint_org_id` (`org_id`),
  ADD KEY `constraint_user_id` (`user_id`),
  ADD KEY `block` (`block`),
  ADD KEY `providerSeeUserBlocked` (`block`,`user_id`),
  ADD KEY `hidden` (`hidden`),
  ADD KEY `casecadeProviderUserHidden` (`hidden`,`user_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `rate_product`
--
ALTER TABLE `rate_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rateProductUserId` (`user_id`),
  ADD KEY `rateProductId` (`product_id`);

--
-- Indexes for table `rate_provider`
--
ALTER TABLE `rate_provider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_admin`
--
ALTER TABLE `setting_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slideForProductId` (`product_id`),
  ADD KEY `slideForProviderId` (`provider_id`);

--
-- Indexes for table `subscribe_package`
--
ALTER TABLE `subscribe_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribe_user`
--
ALTER TABLE `subscribe_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD KEY `hidden` (`hidden`),
  ADD KEY `block` (`block`),
  ADD KEY `city_id` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_product`
--
ALTER TABLE `cart_product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chat_message`
--
ALTER TABLE `chat_message`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `chat_user`
--
ALTER TABLE `chat_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `fav_gallery_image`
--
ALTER TABLE `fav_gallery_image`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `fav_gallery_video`
--
ALTER TABLE `fav_gallery_video`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `fav_product`
--
ALTER TABLE `fav_product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `fav_provider`
--
ALTER TABLE `fav_provider`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `gallery_image`
--
ALTER TABLE `gallery_image`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `gallery_video`
--
ALTER TABLE `gallery_video`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notification_admin`
--
ALTER TABLE `notification_admin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `order_vendor`
--
ALTER TABLE `order_vendor`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `provider`
--
ALTER TABLE `provider`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `rate_product`
--
ALTER TABLE `rate_product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rate_provider`
--
ALTER TABLE `rate_provider`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `setting_admin`
--
ALTER TABLE `setting_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subscribe_package`
--
ALTER TABLE `subscribe_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subscribe_user`
--
ALTER TABLE `subscribe_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
