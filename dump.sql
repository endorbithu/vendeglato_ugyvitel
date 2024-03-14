-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Nov 15, 2016 at 02:01 PM
-- Server version: 5.6.33
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `postamos_vendeglato`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_event_log`
--

CREATE TABLE IF NOT EXISTS `app_event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `event_type_id` int(11) DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_time` datetime NOT NULL,
  `resource_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_369FF223A76ED395` (`user_id`),
  KEY `IDX_369FF223401B253C` (`event_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=369 ;

--
-- Dumping data for table `app_event_log`
--

INSERT INTO `app_event_log` (`id`, `user_id`, `event_type_id`, `message`, `date_time`, `resource_id`) VALUES
(1, 1, 2, 'Ezeket az elemeket nem sikerült törölni, mert még függnek tőlük más adatok: Adasdasds (id:) ', '2016-10-13 06:50:10', NULL),
(2, 1, 2, 'Ezeket az elemeket nem sikerült törölni, mert még függnek tőlük más adatok: élsdnjfsédkf sd (id:42) ', '2016-10-13 08:07:37', NULL),
(3, 1, 2, 'Ezeket az elemeket nem sikerült törölni, mert még függnek tőlük más adatok: Alföldi Asztali Só (id:9) Arany Ászok (id:2) Borsodi sör (id:1) Coca Cola (id:10) Dreher 0,5 üveges (id:14) Hubertus (id:17) Jagermaister (id:18) Karaván Kávé (id:19) Koccintós (i', '2016-10-13 08:21:34', NULL),
(4, 1, 2, 'Ezeket az elemeket nem sikerült törölni, mert még függnek tőlük más adatok: Alföldi Asztali Só (id:9),  Arany Ászok (id:2),  Borsodi sör (id:1),  Coca Cola (id:10),  Dreher 0,5 üveges (id:14),  Hubertus (id:17),  Jagermaister (id:18),  Karaván Kávé (id:19', '2016-10-13 08:24:42', NULL),
(11, 1, 101, '', '2016-10-17 11:33:55', 56),
(12, 1, 103, '', '2016-10-17 12:31:03', 57),
(13, 1, 103, '', '2016-10-17 12:32:05', 58),
(14, 1, 103, '', '2016-10-17 12:32:49', 59),
(15, 1, 106, '', '2016-10-17 12:34:58', 60),
(16, 1, 106, '', '2016-10-17 12:34:59', 61),
(17, 1, 106, '', '2016-10-17 12:35:00', 62),
(18, 1, 103, '', '2016-10-17 20:49:18', 63),
(19, 1, 103, '', '2016-10-18 09:05:53', 64),
(20, 1, 103, '', '2016-10-18 09:06:45', 65),
(21, 1, 103, '', '2016-10-18 11:20:54', 66),
(22, 1, 103, '', '2016-10-18 13:23:36', 67),
(23, 1, 106, '', '2016-10-18 13:25:29', 68),
(24, 1, 106, '', '2016-10-18 13:25:30', 69),
(25, 1, 106, '', '2016-10-18 13:33:10', 70),
(26, 1, 106, '', '2016-10-18 13:33:10', 71),
(27, 1, 106, '', '2016-10-19 02:15:01', 72),
(28, 1, 106, '', '2016-10-19 02:15:01', 73),
(29, 1, 106, '', '2016-10-19 02:16:39', 74),
(30, 1, 106, '', '2016-10-19 02:16:40', 75),
(31, 1, 103, '', '2016-10-19 07:48:03', 76),
(32, 1, 101, '', '2016-10-19 07:49:24', 77),
(33, 1, 102, '', '2016-10-19 07:50:21', 78),
(34, 1, 106, '', '2016-10-19 07:51:36', 79),
(35, 1, 106, '', '2016-10-19 07:51:37', 80),
(36, 1, 104, '', '2016-10-19 07:52:45', 81),
(37, 1, NULL, '', '2016-10-19 07:54:01', 82),
(38, 1, NULL, '', '2016-10-19 07:59:12', 83),
(39, 1, 103, '', '2016-10-19 20:18:19', 84),
(40, 1, NULL, '', '2016-10-20 05:41:27', 85),
(41, 1, 101, '', '2016-10-21 01:58:40', 86),
(42, 1, NULL, '', '2016-10-22 08:41:45', 87),
(43, 1, 106, '', '2016-10-22 08:51:01', 88),
(44, 1, 106, '', '2016-10-22 08:51:02', 89),
(45, 1, NULL, '', '2016-10-22 08:55:50', 90),
(46, 1, 106, '', '2016-10-22 09:04:43', 91),
(47, 1, 106, '', '2016-10-22 09:04:45', 92),
(48, 1, NULL, '', '2016-10-22 23:39:02', NULL),
(49, 1, NULL, '', '2016-10-22 23:39:03', NULL),
(50, 1, NULL, '', '2016-10-23 08:13:24', 93),
(51, 1, NULL, '', '2016-10-23 08:13:24', NULL),
(52, 1, NULL, '', '2016-10-23 08:13:25', 94),
(53, 1, NULL, '', '2016-10-23 08:13:25', NULL),
(54, 1, NULL, '', '2016-10-23 10:02:40', NULL),
(55, 1, NULL, '', '2016-10-23 10:02:41', 95),
(56, 1, NULL, '', '2016-10-23 10:02:41', NULL),
(57, 1, NULL, '', '2016-10-23 10:28:49', 96),
(58, 1, NULL, '', '2016-10-23 10:28:49', NULL),
(59, 1, NULL, '', '2016-10-23 10:28:50', 97),
(60, 1, NULL, '', '2016-10-23 10:28:50', NULL),
(61, 1, NULL, '', '2016-10-23 11:11:56', 98),
(62, 1, NULL, '', '2016-10-23 11:11:57', NULL),
(63, 1, NULL, '', '2016-10-23 11:11:57', 99),
(64, 1, NULL, '', '2016-10-23 11:11:58', NULL),
(65, 1, NULL, '', '2016-10-23 11:13:32', 100),
(66, 1, NULL, '', '2016-10-23 11:13:32', NULL),
(67, 1, NULL, '', '2016-10-23 11:13:32', 101),
(68, 1, NULL, '', '2016-10-23 11:13:32', NULL),
(69, 1, NULL, '', '2016-10-23 11:45:17', NULL),
(70, 1, NULL, '', '2016-10-23 11:47:06', NULL),
(71, 1, NULL, '', '2016-10-23 11:47:38', NULL),
(72, 1, NULL, '', '2016-10-23 11:48:23', NULL),
(73, 1, NULL, '', '2016-10-23 11:56:07', NULL),
(74, 1, NULL, '', '2016-10-23 15:37:24', 102),
(75, 1, NULL, '', '2016-10-23 15:46:50', 103),
(76, 1, NULL, '', '2016-10-23 15:46:51', NULL),
(77, 1, NULL, '', '2016-10-23 15:51:53', 104),
(78, 1, NULL, '', '2016-10-23 15:51:54', NULL),
(79, 1, NULL, '', '2016-10-23 15:52:32', 105),
(80, 1, NULL, '', '2016-10-23 15:52:32', NULL),
(81, 1, NULL, '', '2016-10-23 15:53:03', 106),
(82, 1, NULL, '', '2016-10-23 15:53:03', NULL),
(83, 1, NULL, '', '2016-10-23 15:54:17', 107),
(84, 1, NULL, '', '2016-10-23 15:54:17', NULL),
(85, 1, NULL, '', '2016-10-23 15:55:34', 108),
(86, 1, NULL, '', '2016-10-23 15:55:34', NULL),
(87, 1, NULL, '', '2016-10-23 16:16:38', 109),
(88, 1, NULL, '', '2016-10-23 16:16:38', NULL),
(89, 1, NULL, '', '2016-10-23 16:17:42', 110),
(90, 1, NULL, '', '2016-10-23 16:17:43', NULL),
(91, 1, NULL, '', '2016-10-23 16:18:38', 111),
(92, 1, NULL, '', '2016-10-23 16:18:38', NULL),
(93, 1, NULL, '', '2016-10-23 16:21:01', 112),
(94, 1, NULL, '', '2016-10-23 16:21:01', NULL),
(95, 1, NULL, '', '2016-10-23 16:21:37', 113),
(96, 1, NULL, '', '2016-10-23 16:21:37', NULL),
(97, 1, NULL, '', '2016-10-23 16:23:18', 114),
(98, 1, NULL, '', '2016-10-23 16:23:18', NULL),
(99, 1, NULL, '', '2016-10-23 16:27:52', 115),
(100, 1, NULL, '', '2016-10-23 16:27:52', NULL),
(101, 1, NULL, '', '2016-10-23 16:28:14', 116),
(102, 1, NULL, '', '2016-10-23 16:28:15', NULL),
(103, 1, NULL, '', '2016-10-23 16:30:41', 117),
(104, 1, NULL, '', '2016-10-23 16:30:41', NULL),
(105, 1, NULL, '', '2016-10-23 17:04:59', 118),
(106, 1, NULL, '', '2016-10-23 17:05:00', NULL),
(107, 1, NULL, '', '2016-10-23 17:07:03', 119),
(108, 1, NULL, '', '2016-10-23 17:07:03', NULL),
(109, 1, NULL, '', '2016-10-23 17:08:05', 120),
(110, 1, NULL, '', '2016-10-23 17:08:05', NULL),
(111, 1, NULL, '', '2016-10-23 17:41:58', 121),
(112, 1, NULL, '', '2016-10-23 17:41:59', NULL),
(113, 1, NULL, '', '2016-10-23 17:45:09', 122),
(114, 1, NULL, '', '2016-10-23 17:45:09', NULL),
(115, 1, NULL, '', '2016-10-23 17:49:22', 123),
(116, 1, NULL, '', '2016-10-23 17:49:22', NULL),
(117, 1, NULL, '', '2016-10-24 08:12:01', 124),
(118, 1, 106, '', '2016-10-24 08:16:15', 125),
(119, 1, NULL, '', '2016-10-24 08:17:07', 126),
(120, 1, NULL, '', '2016-10-24 08:18:46', 127),
(121, 1, NULL, '', '2016-10-24 08:19:53', 128),
(122, 1, NULL, '', '2016-10-24 08:21:15', 129),
(123, 1, NULL, '', '2016-10-24 08:22:35', 130),
(124, 1, NULL, '', '2016-10-24 08:22:35', NULL),
(125, 1, NULL, '', '2016-10-24 08:24:08', 131),
(126, 1, NULL, '', '2016-10-24 08:24:09', NULL),
(127, 1, NULL, '', '2016-10-25 22:19:16', 132),
(128, 1, NULL, '', '2016-10-25 22:19:17', NULL),
(129, 1, NULL, '', '2016-10-25 22:19:18', 133),
(130, 1, NULL, '', '2016-10-25 22:19:19', NULL),
(131, 1, NULL, '', '2016-10-26 01:28:46', 134),
(132, 1, NULL, '', '2016-10-26 01:28:46', NULL),
(133, 1, NULL, '', '2016-10-26 01:34:57', 135),
(134, 1, NULL, '', '2016-10-26 01:34:58', NULL),
(135, 1, 103, '', '2016-10-26 01:42:05', 136),
(136, 1, 101, '', '2016-10-26 01:43:52', 137),
(137, 1, NULL, '', '2016-10-26 03:11:18', 138),
(138, 1, NULL, '', '2016-10-26 03:11:22', NULL),
(139, 1, NULL, '', '2016-10-26 03:12:21', NULL),
(140, 1, NULL, '', '2016-10-26 03:12:53', NULL),
(141, 1, NULL, '', '2016-10-26 03:13:43', NULL),
(142, 1, NULL, '', '2016-10-26 03:15:22', NULL),
(143, 1, NULL, '', '2016-10-26 03:16:22', NULL),
(144, 1, NULL, '', '2016-10-26 03:18:41', NULL),
(145, 1, NULL, '', '2016-10-26 03:21:24', NULL),
(146, 1, NULL, '', '2016-10-26 03:24:34', NULL),
(147, 1, NULL, '', '2016-10-26 03:25:11', NULL),
(148, 1, NULL, '', '2016-10-26 03:25:59', NULL),
(149, 1, NULL, '', '2016-10-26 03:26:35', NULL),
(150, 1, NULL, '', '2016-10-26 03:28:07', NULL),
(151, 1, NULL, '', '2016-10-26 03:29:20', NULL),
(152, 1, NULL, '', '2016-10-26 03:30:33', NULL),
(153, 1, NULL, '', '2016-10-26 03:31:02', NULL),
(154, 1, NULL, '', '2016-10-26 03:31:28', NULL),
(155, 1, NULL, '', '2016-10-26 03:31:49', NULL),
(156, 1, NULL, '', '2016-10-26 03:32:30', NULL),
(157, 1, NULL, '', '2016-10-26 03:34:09', NULL),
(158, 1, NULL, '', '2016-10-26 03:34:54', NULL),
(159, 1, NULL, '', '2016-10-26 03:35:34', NULL),
(160, 1, NULL, '', '2016-10-26 03:36:16', NULL),
(161, 1, NULL, '', '2016-10-26 03:37:00', NULL),
(162, 1, NULL, '', '2016-10-26 03:37:22', NULL),
(163, 1, NULL, '', '2016-10-26 03:38:10', NULL),
(164, 1, NULL, '', '2016-10-26 03:39:00', NULL),
(165, 1, NULL, '', '2016-10-26 03:39:21', NULL),
(166, 1, NULL, '', '2016-10-26 03:40:57', NULL),
(167, 1, 101, '', '2016-10-26 03:56:46', 139),
(168, 1, 101, '', '2016-10-26 03:58:03', 140),
(169, 1, NULL, '', '2016-10-26 04:02:40', 141),
(170, 1, NULL, '', '2016-10-26 04:02:42', NULL),
(171, 1, NULL, '', '2016-10-26 04:02:44', 142),
(172, 1, NULL, '', '2016-10-26 04:02:45', NULL),
(173, 1, NULL, '', '2016-10-26 04:04:29', 143),
(174, 1, NULL, '', '2016-10-26 04:04:32', NULL),
(175, 1, NULL, '', '2016-10-26 04:04:34', 144),
(176, 1, NULL, '', '2016-10-26 04:04:35', NULL),
(177, 1, NULL, '', '2016-10-26 04:11:27', 145),
(178, 1, NULL, '', '2016-10-26 04:11:30', NULL),
(179, 1, NULL, '', '2016-10-26 04:11:32', 146),
(180, 1, NULL, '', '2016-10-26 04:11:33', NULL),
(181, 1, NULL, '', '2016-10-26 04:12:40', 147),
(182, 1, NULL, '', '2016-10-26 04:12:42', NULL),
(183, 1, NULL, '', '2016-10-26 04:12:44', 148),
(184, 1, NULL, '', '2016-10-26 04:12:46', NULL),
(185, 1, NULL, '', '2016-10-26 04:24:03', 149),
(186, 1, NULL, '', '2016-10-26 04:24:05', NULL),
(187, 1, NULL, '', '2016-10-26 04:24:07', 150),
(188, 1, NULL, '', '2016-10-26 04:24:08', NULL),
(189, 1, NULL, '', '2016-10-26 04:25:31', 151),
(190, 1, NULL, '', '2016-10-26 04:25:34', NULL),
(191, 1, NULL, '', '2016-10-26 04:25:37', 152),
(192, 1, NULL, '', '2016-10-26 04:25:38', NULL),
(193, 1, NULL, '', '2016-10-26 04:26:33', 153),
(194, 1, NULL, '', '2016-10-26 04:26:35', NULL),
(195, 1, NULL, '', '2016-10-26 04:26:37', 154),
(196, 1, NULL, '', '2016-10-26 04:26:38', NULL),
(197, 1, NULL, '', '2016-10-26 04:27:25', 155),
(198, 1, NULL, '', '2016-10-26 04:27:26', NULL),
(199, 1, 101, '', '2016-10-26 05:40:27', 156),
(200, 1, NULL, '', '2016-10-26 05:41:41', 157),
(201, 1, NULL, '', '2016-10-26 05:41:42', NULL),
(202, 1, NULL, '', '2016-10-26 05:41:42', 158),
(203, 1, NULL, '', '2016-10-26 05:41:43', NULL),
(204, 1, 101, '', '2016-10-26 06:02:57', 159),
(205, 1, 101, '', '2016-10-26 06:16:05', 160),
(206, 1, 106, '', '2016-10-26 06:17:27', 161),
(207, 1, 106, '', '2016-10-26 06:17:28', 162),
(208, 1, 106, '', '2016-10-26 06:17:28', 163),
(209, 1, NULL, '', '2016-10-26 07:06:04', 164),
(210, 1, NULL, '', '2016-10-26 07:06:05', NULL),
(211, 1, NULL, '', '2016-10-26 07:06:05', NULL),
(212, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-10-28 00:08:18', 0),
(213, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-10-28 03:21:45', 0),
(214, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-10-28 03:41:38', 0),
(215, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-10-28 03:42:14', 0),
(216, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-28 03:45:08', 0),
(217, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-28 03:45:59', 0),
(218, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-28 03:47:08', 0),
(219, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-10-28 03:48:02', 0),
(220, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-10-28 03:49:47', 0),
(221, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-10-28 03:50:28', 0),
(222, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-10-28 03:52:22', 0),
(223, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-28 03:53:14', 0),
(224, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-28 03:54:21', 0),
(225, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-28 03:54:37', 0),
(226, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-28 03:54:54', 0),
(227, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-10-28 03:55:09', 0),
(228, 1, 100, 'Sikeres készletművelet: receive', '2016-10-28 04:07:49', 0),
(229, 1, 100, 'Sikeres készletművelet: receive', '2016-10-28 04:09:35', 0),
(230, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-28 04:11:27', 0),
(231, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-28 04:11:58', 0),
(232, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-28 05:30:12', 0),
(233, 1, 100, 'Sikeres készletművelet: receive', '2016-10-28 06:44:45', 0),
(234, 1, 100, 'Sikeres készletművelet: receive', '2016-10-28 06:45:52', 0),
(235, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-10-28 06:56:21', 0),
(236, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-10-28 06:57:02', 0),
(237, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-29 18:10:26', 0),
(238, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-29 18:11:02', 0),
(239, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-10-29 18:11:20', 0),
(240, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-29 18:11:38', 0),
(241, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 14:06:53', 0),
(242, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-30 14:07:10', 0),
(243, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 14:08:36', 0),
(244, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 15:00:32', 0),
(245, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 15:04:05', 0),
(246, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 15:04:54', 0),
(247, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 15:07:12', 0),
(248, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 16:24:04', 0),
(249, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-30 17:21:33', 0),
(250, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-30 17:23:59', 0),
(251, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 17:28:59', 0),
(252, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-30 17:29:27', 0),
(253, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 17:31:49', 0),
(254, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 18:39:31', 0),
(255, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 18:40:58', 0),
(256, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 18:41:34', 0),
(257, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 18:48:36', 0),
(258, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 18:51:06', 0),
(259, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 18:52:12', 0),
(260, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 18:55:40', 0),
(261, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 18:56:20', 0),
(262, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 18:56:56', 0),
(263, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 18:57:31', 0),
(264, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 18:59:13', 0),
(265, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 19:01:11', 0),
(266, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 19:06:57', 0),
(267, 1, 100, 'Sikeres készletművelet: receive', '2016-10-30 19:14:43', 0),
(268, 1, 100, 'Sikeres készletművelet: receive', '2016-10-30 19:16:21', 0),
(269, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 19:17:04', 0),
(270, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 19:17:50', 0),
(271, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 19:19:00', 0),
(272, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 19:19:41', 0),
(273, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-30 19:20:37', 0),
(274, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 19:21:01', 0),
(275, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 19:22:55', 0),
(276, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 19:24:54', 0),
(277, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-30 19:28:06', 0),
(278, 1, 200, 'Sikeres rendelési tétel mozgatás: move', '2016-10-31 05:00:41', 0),
(279, 1, 200, 'Sikeres rendelési tétel mozgatás: moveorderitem', '2016-10-31 05:16:16', 0),
(280, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 05:19:08', 0),
(281, 1, 200, 'Sikeres rendelési tétel mozgatás: moveorderitem', '2016-10-31 05:19:31', 0),
(282, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-31 05:19:49', 0),
(283, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-31 05:22:08', 0),
(284, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 05:32:18', 0),
(285, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 05:33:33', 0),
(286, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 05:35:02', 0),
(287, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 05:36:15', 0),
(288, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 05:36:41', 0),
(289, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 05:37:10', 0),
(290, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-31 05:37:35', 0),
(291, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 05:53:12', 0),
(292, 1, 200, 'Sikeres rendelési tétel mozgatás: moveorderitem', '2016-10-31 07:00:20', 0),
(293, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 07:02:00', 0),
(294, 1, 100, 'Sikeres készletművelet: receive', '2016-10-31 21:41:47', 0),
(295, 1, 100, 'Sikeres készletművelet: receive', '2016-10-31 21:43:05', 0),
(296, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 21:45:31', 0),
(297, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-31 21:45:58', 0),
(298, 1, 200, 'Sikeres rendelési tétel mozgatás: moveorderitem', '2016-10-31 21:46:23', 0),
(299, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-31 21:46:45', 0),
(300, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-10-31 21:47:15', 0),
(301, 14, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-10-31 22:15:31', 0),
(302, 14, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-10-31 22:15:52', 0),
(303, 13, 100, 'Sikeres készletművelet: receive', '2016-10-31 22:58:53', 0),
(304, 13, 100, 'Sikeres készletművelet: receive', '2016-10-31 23:06:29', 0),
(305, 13, 100, 'Sikeres készletművelet: move', '2016-10-31 23:07:35', 0),
(306, 13, 100, 'Sikeres készletművelet: return', '2016-10-31 23:08:33', 0),
(307, 13, 100, 'Sikeres készletművelet: return', '2016-10-31 23:09:26', 0),
(308, 13, 100, 'Sikeres készletművelet: stockcorrection', '2016-10-31 23:11:41', 0),
(309, 13, 100, 'Sikeres készletművelet: discardingredient', '2016-10-31 23:12:51', 0),
(310, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-11-01 00:36:38', 0),
(311, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-11-01 00:37:03', 0),
(312, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-11-01 00:38:39', 0),
(313, 1, 200, 'Sikeres rendelési tétel mozgatás: destination', '2016-11-01 10:35:43', 0),
(314, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 10:41:36', 0),
(315, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 10:43:26', 0),
(316, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 10:44:10', 0),
(317, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 10:44:48', 0),
(318, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 11:21:51', 0),
(319, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-11-01 12:14:28', 0),
(320, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 14:17:55', 0),
(321, 1, 200, 'Sikeres rendelési tétel mozgatás: moveorderitem', '2016-11-01 14:55:25', 0),
(322, 1, 200, 'Sikeres rendelési tétel mozgatás: moveorderitem', '2016-11-01 14:57:01', 0),
(323, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 15:02:32', 0),
(324, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 15:13:31', 0),
(325, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 15:18:39', 0),
(326, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 15:27:51', 0),
(327, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-11-01 15:28:32', 0),
(328, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-01 15:45:49', 0),
(329, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-11-01 15:46:11', 0),
(330, 1, 100, 'Sikeres készletművelet: receive', '2016-11-01 21:24:22', 0),
(331, 1, 100, 'Sikeres készletművelet: receive', '2016-11-02 18:48:30', 0),
(332, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-02 18:50:37', 0),
(333, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-11-02 18:54:55', 0),
(334, 1, 100, 'Sikeres készletművelet: receive', '2016-11-02 19:18:21', 0),
(335, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-11-02 19:38:51', 0),
(336, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-02 19:46:47', 0),
(337, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-11-02 19:48:31', 0),
(338, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-11-02 19:52:04', 0),
(339, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-11-02 20:35:37', 0),
(340, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-02 20:38:06', 0),
(341, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-11-02 20:38:15', 0),
(342, 1, 100, 'Sikeres készletművelet: universal', '2016-11-02 20:50:25', 0),
(343, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-11-02 20:50:32', 0),
(344, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-02 20:50:50', 0),
(345, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-11-02 20:50:56', 0),
(346, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-03 05:35:12', 0),
(347, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-03 06:40:22', 0),
(348, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-11-03 08:34:49', 0),
(349, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-11-03 08:40:20', 0),
(350, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-03 08:40:58', 0),
(351, 1, 200, 'Sikeres rendelési tétel mozgatás: moveorderitem', '2016-11-03 08:41:31', 0),
(352, 1, 200, 'Sikeres rendelési tétel mozgatás: moveorderitem', '2016-11-03 08:42:04', 0),
(353, 1, 200, 'Sikeres rendelési tétel mozgatás: productbacktotrash', '2016-11-03 08:49:29', 0),
(354, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-04 07:17:47', 0),
(355, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-11-04 08:17:23', 0),
(356, 1, 100, 'Sikeres készletművelet: stockcorrection', '2016-11-04 08:19:03', 0),
(357, 1, 100, 'Sikeres készletművelet: receive', '2016-11-04 08:20:28', 0),
(358, 1, 100, 'Sikeres készletművelet: return', '2016-11-04 08:21:21', 0),
(359, 1, 100, 'Sikeres készletművelet: move', '2016-11-04 08:22:23', 0),
(360, 1, 100, 'Sikeres készletművelet: discardingredient', '2016-11-04 08:24:28', 0),
(361, 1, 100, 'Sikeres készletművelet: universal', '2016-11-04 08:25:41', 0),
(362, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-04 08:26:32', 0),
(363, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-11-04 08:28:19', 0),
(364, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-05 05:50:34', 0),
(365, 1, 200, 'Sikeres rendelési tétel mozgatás: productback', '2016-11-07 05:52:09', 0),
(366, 1, 200, 'Sikeres rendelési tétel mozgatás: order', '2016-11-10 00:58:48', 0),
(367, 1, 100, 'Sikeres készletművelet: receive', '2016-11-13 19:09:16', 0),
(368, 1, 200, 'Sikeres rendelési tétel mozgatás: paying', '2016-11-13 19:10:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `app_event_type`
--

CREATE TABLE IF NOT EXISTS `app_event_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=201 ;

--
-- Dumping data for table `app_event_type`
--

INSERT INTO `app_event_type` (`id`, `name`) VALUES
(1, 'Törzsadat módosítása'),
(2, 'Törzsadat törlése'),
(3, 'Törzsadat sikertelen törlése'),
(100, 'Készletművelet'),
(101, 'Bevételezés'),
(102, 'Visszárú'),
(103, 'Átvételezés'),
(104, 'Selejtezés (alapanyag)'),
(105, 'Selejtezés (termék)'),
(106, 'Készletkorrekció (leltár)'),
(200, 'Rendelési tétel mozgatás');

-- --------------------------------------------------------

--
-- Table structure for table `app_role`
--

CREATE TABLE IF NOT EXISTS `app_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `app_role`
--

INSERT INTO `app_role` (`id`, `name`, `display_name`) VALUES
(1, 'owner', 'Tulajdonos'),
(2, 'manager', 'Vezető'),
(3, 'staff', 'Munkatárs'),
(4, 'guest', 'Vendég'),
(5, 'admin', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `app_user`
--

CREATE TABLE IF NOT EXISTS `app_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `more_info` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_88BDF3E9D60322AC` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `app_user`
--

INSERT INTO `app_user` (`id`, `username`, `password`, `role_id`, `name`, `email`, `telephone`, `more_info`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 5, 'Magyar Bertalan', 'stellar124@gmail.com', '+36304031556', 'Fejlesztő'),
(13, 'egyvezeto', '49c22936de6b1a03407a815da8764b50', 2, 'Vezető János', 'vezeto@janos.com', '+36304031550', 'Egyik vezető'),
(14, 'egymunkatars', '8cd9f17e48cf4d57abfd5a6969ce23f9', 3, 'Munkatárs Béla', 'munkatars@bela.com', '+36304033556', 'Egyik munkatárs'),
(15, 'egytulajdonos', 'd41d8cd98f00b204e9800998ecf8427e', 1, 'Tulajdonos Dezső', 'egytulajdonos@gssmail.com', '+36304087556', 'Ez egy tulajdonos'),
(16, 'mate', '7c13aea47d6e6ddefd62d2c00653b2a4', 5, 'Magyar Máté', 'mmate999@gmail.com', '+3630408565656', 'Egyik ötletgazda'),
(17, 'kati', '05155838d6a9b3ff384a8b247be221b5', 5, 'Magyar Katalin', 'katticca@gmail.hu', '+363450875565', 'Szakértő :)'),
(18, 'kusper', '92b12406c303b701df851d1d31ded6ae', 5, 'Kusper G.', 'ggggggg@ggg.hu', '1221022', ''),
(19, 'szakdolgozat', '1232c6fc927470ed51e9ed86f0282064', 5, 'Szak Dolgozat', 'szakd@szakd.com', '324234', ''),
(20, 'szakdolgozat', '1232c6fc927470ed51e9ed86f0282064', 5, 'Szak Dolgozat', 'stellarsdsdsd124@gmail.com', '+36304031556', '');

-- --------------------------------------------------------

--
-- Table structure for table `cat_ingredient`
--

CREATE TABLE IF NOT EXISTS `cat_ingredient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ingredient_group_id` int(11) DEFAULT NULL,
  `ingredient_unit_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `minimum_amount` decimal(8,2) NOT NULL,
  `more_info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B64D53818C5289C9` (`ingredient_group_id`),
  KEY `IDX_B64D5381F98A7BCB` (`ingredient_unit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `cat_ingredient`
--

INSERT INTO `cat_ingredient` (`id`, `ingredient_group_id`, `ingredient_unit_id`, `name`, `minimum_amount`, `more_info`) VALUES
(1, 7, 1, 'Borsodi sör', '10.55', 'Erre figyelni kell, mert hamar fogy'),
(2, 7, 1, 'Arany Ászok', '17.33', 'dsdf'),
(4, 1, 1, 'Royal vodka', '5.40', 'Jó kis vodka'),
(5, 3, 1, 'Sió őszibaracklé', '20.50', ''),
(9, 10, 3, 'Asztali Só', '3.50', 'fghfhf'),
(10, 4, 1, 'Coca Cola', '10.50', NULL),
(12, 2, 1, 'Koccintós', '15.40', NULL),
(13, 5, 2, 'Mogyi ropi', '6.00', NULL),
(14, 7, 2, 'Dreher 0,5 üveges', '15.00', NULL),
(15, 7, 2, 'Szalon 0,5 üveges', '15.00', NULL),
(17, 1, 1, 'Hubertus', '20.00', NULL),
(18, 1, 1, 'Jagermaister', '30.00', NULL),
(19, 8, 3, 'Karaván Kávé', '10.00', NULL),
(20, 7, 1, 'Szalon sör', '0.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cat_ingredient_group`
--

CREATE TABLE IF NOT EXISTS `cat_ingredient_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `cat_ingredient_group`
--

INSERT INTO `cat_ingredient_group` (`id`, `name`) VALUES
(1, 'Tömények'),
(2, 'Borok'),
(3, 'Rostosak'),
(4, 'Szénsavasak'),
(5, 'Sósak'),
(6, 'Édesek'),
(7, 'Sörök'),
(8, 'Kávék'),
(9, 'Teák'),
(10, 'Díszítők'),
(11, 'GÖNGYÖLEG'),
(12, 'Ételek');

-- --------------------------------------------------------

--
-- Table structure for table `cat_ingredient_in_product`
--

CREATE TABLE IF NOT EXISTS `cat_ingredient_in_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`id`),
  KEY `IDX_20BA3B5F4584665A` (`product_id`),
  KEY `IDX_20BA3B5F933FE08C` (`ingredient_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=75 ;

--
-- Dumping data for table `cat_ingredient_in_product`
--

INSERT INTO `cat_ingredient_in_product` (`id`, `product_id`, `ingredient_id`, `amount`) VALUES
(2, 4, 1, '0.50'),
(3, 5, 14, '0.50'),
(4, 6, 15, '0.50'),
(5, 7, 10, '0.50'),
(7, 10, 4, '0.50'),
(8, 11, 17, '0.50'),
(9, 12, 18, '0.50'),
(10, 13, 19, '0.10'),
(11, 14, 19, '0.50'),
(27, 8, 15, '0.50'),
(29, 8, 9, '0.50'),
(30, 8, 13, '0.50'),
(31, 8, 13, '0.50'),
(32, 8, 14, '0.50'),
(54, 35, 15, '0.50'),
(55, 35, 13, '0.50'),
(65, 4, 5, '0.50'),
(66, 3, 2, '0.55'),
(73, 18, 20, '0.20'),
(74, 40, 1, '0.50');

-- --------------------------------------------------------

--
-- Table structure for table `cat_ingredient_moving`
--

CREATE TABLE IF NOT EXISTS `cat_ingredient_moving` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_transaction_id` int(11) DEFAULT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FA0211A488237244` (`stock_transaction_id`),
  KEY `IDX_FA0211A4933FE08C` (`ingredient_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=424 ;

--
-- Dumping data for table `cat_ingredient_moving`
--

INSERT INTO `cat_ingredient_moving` (`id`, `stock_transaction_id`, `ingredient_id`, `amount`) VALUES
(222, 109, 1, '1.00'),
(223, 109, 2, '2.00'),
(224, 109, 4, '2.00'),
(225, 109, 5, '1.00'),
(226, 109, 10, '2.00'),
(227, 109, 12, '1.00'),
(228, 109, 17, '3.00'),
(229, 110, 18, '2.00'),
(230, 110, 20, '2.00'),
(231, 110, 13, '1.00'),
(232, 110, 14, '2.00'),
(233, 110, 15, '1.00'),
(234, 111, 1, '1.00'),
(235, 111, 4, '0.50'),
(236, 111, 5, '1.00'),
(237, 111, 10, '1.00'),
(238, 111, 17, '1.00'),
(239, 112, 13, '2.00'),
(240, 112, 14, '1.00'),
(241, 112, 15, '2.00'),
(242, 112, 9, '1.00'),
(243, 113, 1, '1.00'),
(244, 113, 5, '1.00'),
(245, 113, 13, '1.00'),
(246, 113, 14, '1.00'),
(247, 113, 15, '1.00'),
(248, 113, 9, '1.00'),
(249, 114, 17, '1.00'),
(250, 114, 4, '0.50'),
(251, 115, 4, '0.50'),
(252, 117, 17, '1.00'),
(253, 119, 4, '1.00'),
(254, 121, 10, '1.00'),
(255, 122, 5, '100.00'),
(256, 122, 12, '200.00'),
(257, 123, 4, '210.00'),
(258, 124, 13, '100.00'),
(259, 125, 20, '100.00'),
(260, 126, 13, '100.00'),
(261, 127, 1, '34.00'),
(262, 127, 4, '10.00'),
(263, 127, 12, '98.00'),
(264, 128, 2, '43.50'),
(265, 128, 5, '62.50'),
(266, 128, 10, '101.50'),
(267, 129, 17, '0.00'),
(268, 130, 2, '20.00'),
(269, 132, 1, '40.00'),
(270, 135, 17, '3.00'),
(271, 137, 1, '100.00'),
(272, 137, 2, '124.00'),
(273, 141, 1, '0.50'),
(274, 141, 5, '0.50'),
(275, 143, 1, '1.00'),
(276, 143, 5, '1.00'),
(277, 145, 1, '1.00'),
(278, 145, 5, '1.00'),
(279, 147, 4, '1.00'),
(280, 149, 1, '0.50'),
(281, 149, 5, '0.50'),
(282, 149, 10, '0.60'),
(283, 151, 13, '1.00'),
(284, 151, 15, '1.00'),
(285, 152, 1, '1.00'),
(286, 152, 5, '1.00'),
(287, 152, 10, '0.30'),
(288, 152, 17, '0.50'),
(289, 153, 18, '0.50'),
(290, 154, 17, '0.50'),
(291, 154, 1, '1.00'),
(292, 154, 4, '1.00'),
(293, 154, 5, '1.00'),
(294, 155, 1, '0.50'),
(295, 155, 4, '1.00'),
(296, 155, 5, '0.50'),
(297, 156, 1, '1.00'),
(298, 156, 5, '1.00'),
(299, 156, 10, '0.50'),
(300, 158, 1, '0.50'),
(301, 158, 2, '0.55'),
(302, 158, 4, '1.50'),
(303, 158, 5, '0.50'),
(304, 158, 10, '0.80'),
(305, 158, 17, '0.50'),
(306, 159, 18, '0.50'),
(307, 159, 20, '0.20'),
(308, 159, 13, '0.50'),
(309, 159, 14, '1.00'),
(310, 159, 15, '1.00'),
(311, 159, 9, '0.50'),
(312, 159, 19, '1.00'),
(313, 160, 1, '0.50'),
(314, 160, 4, '2.00'),
(315, 160, 5, '0.50'),
(316, 161, 14, '2.50'),
(317, 162, 4, '0.50'),
(318, 162, 10, '0.50'),
(319, 164, 18, '1.00'),
(320, 164, 17, '0.50'),
(321, 164, 1, '2.50'),
(322, 164, 4, '5.00'),
(323, 164, 5, '2.50'),
(324, 164, 10, '2.10'),
(325, 164, 13, '0.50'),
(326, 164, 14, '3.50'),
(327, 164, 15, '1.00'),
(328, 164, 9, '0.50'),
(329, 164, 2, '0.55'),
(330, 164, 20, '0.20'),
(331, 164, 19, '1.00'),
(332, 165, 10, '0.50'),
(333, 165, 17, '0.50'),
(334, 167, 10, '0.50'),
(335, 168, 1, '400.00'),
(336, 168, 2, '400.00'),
(337, 168, 4, '500.00'),
(338, 168, 5, '333.00'),
(339, 168, 10, '400.00'),
(340, 168, 12, '400.00'),
(341, 168, 15, '300.00'),
(342, 168, 9, '500.00'),
(343, 169, 2, '500.00'),
(344, 169, 5, '600.00'),
(345, 170, 17, '0.50'),
(346, 171, 13, '0.50'),
(347, 171, 14, '0.50'),
(348, 171, 15, '1.00'),
(349, 171, 9, '0.50'),
(350, 173, 14, '104.00'),
(351, 173, 9, '202.50'),
(352, 175, 10, '123.60'),
(353, 175, 12, '500.55'),
(354, 176, 17, '1.00'),
(355, 179, 19, '0.10'),
(356, 180, 19, '0.10'),
(357, 181, 13, '0.50'),
(358, 181, 14, '0.50'),
(359, 181, 15, '1.00'),
(360, 181, 9, '0.50'),
(361, 182, 1, '2.50'),
(362, 182, 5, '2.50'),
(363, 182, 4, '1.00'),
(364, 185, 17, '0.50'),
(365, 185, 1, '1.00'),
(366, 185, 5, '1.00'),
(367, 188, 1, '0.80'),
(368, 188, 2, '0.40'),
(369, 189, 1, '0.80'),
(370, 189, 2, '0.40'),
(371, 190, 4, '0.50'),
(372, 190, 17, '0.50'),
(373, 192, 4, '0.50'),
(374, 194, 4, '0.50'),
(375, 195, 13, '0.50'),
(376, 195, 14, '0.50'),
(377, 195, 15, '0.50'),
(378, 195, 9, '0.50'),
(379, 196, 4, '0.50'),
(380, 198, 4, '0.50'),
(381, 200, 4, '0.50'),
(382, 201, 1, '0.50'),
(383, 201, 5, '0.50'),
(384, 201, 17, '0.50'),
(385, 202, 14, '0.50'),
(386, 203, 1, '0.50'),
(387, 203, 5, '0.50'),
(388, 204, 17, '0.50'),
(389, 204, 1, '0.50'),
(390, 204, 5, '0.50'),
(391, 204, 14, '0.50'),
(392, 205, 13, '0.50'),
(393, 205, 14, '0.50'),
(394, 205, 15, '0.50'),
(395, 205, 9, '0.50'),
(396, 206, 10, '0.50'),
(397, 209, 2, '100.55'),
(398, 212, 4, '100.00'),
(399, 214, 4, '600.00'),
(400, 214, 10, '500.00'),
(401, 215, 2, '300.00'),
(402, 216, 1, '333.55'),
(403, 216, 4, '500.00'),
(404, 217, 13, '50.00'),
(405, 217, 9, '250.00'),
(406, 218, 4, '200.00'),
(407, 219, 2, '1.10'),
(408, 219, 17, '0.50'),
(409, 220, 13, '5.00'),
(410, 220, 14, '5.00'),
(411, 220, 15, '5.00'),
(412, 220, 9, '5.00'),
(413, 221, 2, '1.10'),
(414, 223, 10, '0.50'),
(415, 223, 17, '0.50'),
(416, 225, 17, '0.50'),
(417, 225, 10, '0.50'),
(418, 227, 4, '0.50'),
(419, 227, 10, '0.50'),
(420, 227, 17, '0.50'),
(421, 229, 4, '2.00'),
(422, 229, 12, '6.00'),
(423, 230, 10, '1.00');

-- --------------------------------------------------------

--
-- Table structure for table `cat_ingredient_unit`
--

CREATE TABLE IF NOT EXISTS `cat_ingredient_unit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cat_ingredient_unit`
--

INSERT INTO `cat_ingredient_unit` (`id`, `name`, `short_name`) VALUES
(1, 'Liter', 'L'),
(2, 'Darab', 'db'),
(3, 'Kilogram', 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `cat_product`
--

CREATE TABLE IF NOT EXISTS `cat_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_group_id` int(11) DEFAULT NULL,
  `vat_group_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `prescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `more_info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F3FB86FB35E4B3D0` (`product_group_id`),
  KEY `IDX_F3FB86FBE8C706E8` (`vat_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;

--
-- Dumping data for table `cat_product`
--

INSERT INTO `cat_product` (`id`, `product_group_id`, `vat_group_id`, `name`, `short_name`, `price`, `is_active`, `prescription`, `more_info`) VALUES
(3, 1, 1, 'Arany Ászok 0,5l', 'Arász05', '450.00', 1, '', ''),
(4, 1, 1, 'Borsodi 0,5l', 'Bors-0,5', '22.00', 1, 'Ez vanEz vanEz vanEz vanEz van', ''),
(5, 2, 1, 'Dreher üv.', 'Dreher-üv', '530.00', 1, 'hamar elfogy', ''),
(6, 2, 1, 'Szalon üv.', 'szaloüv', '300.00', 1, NULL, ''),
(7, 3, 1, 'Coca cola 0,5l', 'Coca cola 0,5l', '250.00', 1, NULL, ''),
(8, 3, 2, 'Pepsi 0,3l', 'Pepsi03', '200.00', 1, 'Kedvenc', ''),
(9, 4, 1, 'Ropi', '', '150.00', 1, NULL, ''),
(10, 5, 1, 'Vodka 0,5dl', '', '600.00', 1, NULL, ''),
(11, 5, 1, 'Hubertus 0,5dl', 'hubi05', '1150.00', 1, NULL, ''),
(12, 5, 1, 'Jagermaister 0,5dl', 'jagmaist05', '1590.00', 1, NULL, ''),
(13, 6, 1, 'Tejes kávé', '', '200.00', 1, '', ''),
(14, 6, 1, 'Eszpresszó', '', '240.00', 1, NULL, ''),
(15, 7, 1, 'Olasz rizling 0,3dl', '', '340.00', 1, NULL, ''),
(18, 2, 2, 'Kozel', 'Kozel', '200.00', 1, 'Jó kis röe', ''),
(19, 2, 1, 'Szobi szörp', 'Szobi', '542.00', 1, 'Kedvencem', ''),
(35, 1, 1, 'Sör+ropi', 'Sörropi', '300.00', 0, '', ''),
(39, 6, 1, 'Hosszú kávé', '', '400.00', 1, 'Semmi extra', ''),
(40, 1, 1, '7Up 0,3lxx', '7Up03', '344.00', 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cat_product_aware_storage_type`
--

CREATE TABLE IF NOT EXISTS `cat_product_aware_storage_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storage_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_43ED29A2B270BFF1` (`storage_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cat_product_aware_storage_type`
--

INSERT INTO `cat_product_aware_storage_type` (`id`, `storage_type_id`) VALUES
(1, 1),
(3, 2),
(4, 9),
(2, 10),
(5, 11);

-- --------------------------------------------------------

--
-- Table structure for table `cat_product_group`
--

CREATE TABLE IF NOT EXISTS `cat_product_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cat_product_group`
--

INSERT INTO `cat_product_group` (`id`, `name`) VALUES
(1, 'Csapolt sörök'),
(2, 'Üveges sörök'),
(3, 'Üdítők'),
(4, 'Sósak'),
(5, 'Tömények'),
(6, 'Kávék'),
(7, 'Folyó bor'),
(8, 'Üveges borok'),
(10, 'Melegételek');

-- --------------------------------------------------------

--
-- Table structure for table `cat_real_storage_type`
--

CREATE TABLE IF NOT EXISTS `cat_real_storage_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storage_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9E06F6E3B270BFF1` (`storage_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `cat_real_storage_type`
--

INSERT INTO `cat_real_storage_type` (`id`, `storage_type_id`) VALUES
(1, 1),
(2, 3),
(12, 11);

-- --------------------------------------------------------

--
-- Table structure for table `cat_stock`
--

CREATE TABLE IF NOT EXISTS `cat_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storage` int(11) DEFAULT NULL,
  `ingredient` int(11) DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F74DE68D547A1B34` (`storage`),
  KEY `IDX_F74DE68D6BAF7870` (`ingredient`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=113 ;

--
-- Dumping data for table `cat_stock`
--

INSERT INTO `cat_stock` (`id`, `storage`, `ingredient`, `amount`) VALUES
(50, 19, 1, '293.50'),
(51, 19, 2, '398.90'),
(52, 19, 4, '596.00'),
(53, 19, 5, '548.50'),
(54, 19, 10, '495.80'),
(55, 19, 12, '506.00'),
(56, 19, 17, '397.00'),
(57, 20, 18, '500.00'),
(58, 20, 20, '401.80'),
(59, 20, 13, '291.00'),
(60, 20, 14, '594.00'),
(61, 20, 15, '490.00'),
(62, 20, 9, '694.50'),
(63, 20, 19, '497.90'),
(64, 3, 1, '1.50'),
(65, 3, 2, '0.00'),
(66, 3, 5, '1.50'),
(67, 3, 10, '1.60'),
(68, 3, 17, '2.00'),
(69, 3, 13, '0.00'),
(70, 3, 15, '0.00'),
(71, 3, 19, '0.00'),
(72, 3, 18, '0.00'),
(73, 3, 14, '0.50'),
(74, 1, 18, '0.00'),
(75, 2, 18, '0.00'),
(76, 1, 12, '1.00'),
(77, 1, 17, '1.50'),
(78, 2, 17, '0.50'),
(79, 2, 1, '1.00'),
(80, 2, 4, '0.50'),
(81, 2, 5, '1.50'),
(82, 2, 12, '0.00'),
(83, 2, 2, '0.50'),
(84, 2, 13, '0.50'),
(85, 2, 14, '0.50'),
(86, 2, 15, '0.50'),
(87, 2, 9, '0.50'),
(88, 3, 12, '1.00'),
(89, 1, 1, '0.00'),
(90, 1, 4, '0.00'),
(91, 1, 5, '0.00'),
(92, 1, 10, '0.00'),
(93, 1, 13, '5.00'),
(94, 1, 14, '5.00'),
(95, 1, 15, '5.00'),
(96, 1, 9, '5.00'),
(97, 3, 4, '0.00'),
(98, 6, 13, '50.00'),
(99, 1, 2, '0.00'),
(100, 1, 20, '0.00'),
(101, 1, 19, '0.00'),
(102, 6, 1, '66.45'),
(103, 6, 2, '600.00'),
(104, 6, 4, '700.00'),
(105, 6, 5, '933.00'),
(106, 6, 10, '1023.60'),
(107, 6, 12, '900.55'),
(108, 6, 15, '300.00'),
(109, 6, 9, '250.00'),
(110, 2, 10, '0.00'),
(111, 5, 1, '333.55'),
(112, 5, 4, '700.00');

-- --------------------------------------------------------

--
-- Table structure for table `cat_stock_transaction`
--

CREATE TABLE IF NOT EXISTS `cat_stock_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_transaction_type_id` int(11) DEFAULT NULL,
  `from_storage_id` int(11) DEFAULT NULL,
  `to_storage_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `more_info` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_84B19286B65D9CEE` (`stock_transaction_type_id`),
  KEY `IDX_84B1928677611D97` (`from_storage_id`),
  KEY `IDX_84B192867BF639ED` (`to_storage_id`),
  KEY `IDX_84B19286A76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=231 ;

--
-- Dumping data for table `cat_stock_transaction`
--

INSERT INTO `cat_stock_transaction` (`id`, `stock_transaction_type_id`, `from_storage_id`, `to_storage_id`, `user_id`, `date_time`, `more_info`) VALUES
(109, 1, 8, 19, 1, '2016-10-31 21:41:45', ''),
(110, 1, 8, 20, 1, '2016-10-31 21:43:04', ''),
(111, 8, 19, 1, 1, '2016-10-31 21:45:25', ''),
(112, 8, 20, 1, 1, '2016-10-31 21:45:25', ''),
(113, 11, 1, 17, 1, '2016-10-31 21:45:56', ''),
(114, 12, 1, 3, 1, '2016-10-31 21:46:22', ''),
(115, 9, 3, 19, 1, '2016-10-31 21:46:43', ''),
(116, 9, 3, 20, 1, '2016-10-31 21:46:43', ''),
(117, 9, 3, 19, 1, '2016-10-31 21:47:14', ''),
(118, 9, 3, 20, 1, '2016-10-31 21:47:14', ''),
(119, 8, 19, 1, 14, '2016-10-31 22:15:30', ''),
(120, 8, 20, 1, 14, '2016-10-31 22:15:30', ''),
(121, 11, 1, 17, 14, '2016-10-31 22:15:51', ''),
(122, 1, 8, 19, 13, '2016-10-31 22:58:52', ''),
(123, 1, 8, 19, 13, '2016-10-31 23:06:28', 'gdfgad ad d'),
(124, 3, 20, 6, 13, '2016-10-31 23:07:34', 'semmi'),
(125, 2, 20, 8, 13, '2016-10-31 23:08:33', 'Semmi'),
(126, 2, 20, 9, 13, '2016-10-31 23:09:25', 'semmi'),
(127, 6, 19, 12, 13, '2016-10-31 23:11:40', ''),
(128, 6, 13, 19, 13, '2016-10-31 23:11:40', ''),
(129, 6, 19, 14, 13, '2016-10-31 23:11:40', ''),
(130, 4, 19, 4, 13, '2016-10-31 23:12:51', ''),
(131, 6, 19, 19, 1, '2016-11-01 00:36:36', ''),
(132, 6, 13, 19, 1, '2016-11-01 00:36:36', ''),
(133, 6, 19, 19, 1, '2016-11-01 00:36:36', ''),
(134, 6, 19, 19, 1, '2016-11-01 00:37:02', ''),
(135, 6, 13, 19, 1, '2016-11-01 00:37:02', ''),
(136, 6, 19, 19, 1, '2016-11-01 00:37:02', ''),
(137, 6, 19, 12, 1, '2016-11-01 00:38:38', ''),
(138, 6, 19, 19, 1, '2016-11-01 00:38:38', ''),
(139, 6, 19, 19, 1, '2016-11-01 00:38:38', ''),
(141, 8, 19, 2, 1, '2016-11-01 10:41:34', ''),
(142, 8, 20, 2, 1, '2016-11-01 10:41:34', ''),
(143, 8, 19, 3, 1, '2016-11-01 10:43:24', ''),
(144, 8, 20, 3, 1, '2016-11-01 10:43:24', ''),
(145, 8, 19, 3, 1, '2016-11-01 10:44:08', ''),
(146, 8, 20, 3, 1, '2016-11-01 10:44:08', ''),
(147, 8, 19, 3, 1, '2016-11-01 10:44:47', ''),
(148, 8, 20, 3, 1, '2016-11-01 10:44:47', ''),
(149, 8, 19, 3, 1, '2016-11-01 11:21:49', ''),
(150, 8, 20, 3, 1, '2016-11-01 11:21:49', ''),
(151, 10, 1, 4, 1, '2016-11-01 12:14:27', ''),
(152, 8, 19, 1, 1, '2016-11-01 14:17:47', ''),
(153, 8, 20, 1, 1, '2016-11-01 14:17:47', ''),
(154, 12, 1, 2, 1, '2016-11-01 14:55:22', ''),
(155, 12, 2, 1, 1, '2016-11-01 14:56:58', 'semmi'),
(156, 8, 19, 1, 1, '2016-11-01 15:02:30', ''),
(157, 8, 20, 1, 1, '2016-11-01 15:02:30', ''),
(158, 8, 19, 1, 1, '2016-11-01 15:13:16', ''),
(159, 8, 20, 1, 1, '2016-11-01 15:13:16', ''),
(160, 8, 19, 1, 1, '2016-11-01 15:18:35', ''),
(161, 8, 20, 1, 1, '2016-11-01 15:18:35', ''),
(162, 8, 19, 1, 1, '2016-11-01 15:27:50', ''),
(163, 8, 20, 1, 1, '2016-11-01 15:27:50', ''),
(164, 11, 1, 17, 1, '2016-11-01 15:28:20', 'bapa'),
(165, 8, 19, 1, 1, '2016-11-01 15:45:48', ''),
(166, 8, 20, 1, 1, '2016-11-01 15:45:48', ''),
(167, 11, 1, 17, 1, '2016-11-01 15:46:10', ''),
(168, 1, 9, 6, 1, '2016-11-01 21:24:18', 'Próba mozgatás'),
(169, 1, 11, 6, 1, '2016-11-02 18:48:30', ''),
(170, 8, 19, 1, 1, '2016-11-02 18:50:37', ''),
(171, 8, 20, 1, 1, '2016-11-02 18:50:37', ''),
(172, 6, 20, 20, 1, '2016-11-02 18:54:55', 'semmi'),
(173, 6, 13, 20, 1, '2016-11-02 18:54:55', 'semmi'),
(174, 6, 20, 20, 1, '2016-11-02 18:54:55', 'semmi'),
(175, 1, 8, 6, 1, '2016-11-02 19:18:21', 'semmi cuccli'),
(176, 9, 1, 19, 1, '2016-11-02 19:38:51', 'semmi'),
(177, 9, 1, 20, 1, '2016-11-02 19:38:51', 'semmi'),
(178, 8, 19, 1, 1, '2016-11-02 19:46:47', ''),
(179, 8, 20, 1, 1, '2016-11-02 19:46:47', ''),
(180, 11, 1, 17, 1, '2016-11-02 19:48:31', ''),
(181, 11, 1, 17, 1, '2016-11-02 19:52:04', ''),
(182, 11, 3, 17, 1, '2016-11-02 20:35:37', ''),
(183, 8, 19, 2, 1, '2016-11-02 20:38:06', ''),
(184, 8, 20, 2, 1, '2016-11-02 20:38:06', ''),
(185, 11, 2, 17, 1, '2016-11-02 20:38:15', ''),
(188, 7, 9, 3, 1, '2016-11-02 20:50:25', ''),
(189, 10, 3, 4, 1, '2016-11-02 20:50:32', ''),
(190, 8, 19, 3, 1, '2016-11-02 20:50:50', ''),
(191, 8, 20, 3, 1, '2016-11-02 20:50:50', ''),
(192, 9, 3, 19, 1, '2016-11-02 20:50:56', ''),
(193, 9, 3, 20, 1, '2016-11-02 20:50:56', ''),
(194, 8, 19, 2, 1, '2016-11-03 05:35:11', ''),
(195, 8, 20, 2, 1, '2016-11-03 05:35:11', ''),
(196, 8, 19, 1, 1, '2016-11-03 06:40:22', ''),
(197, 8, 20, 1, 1, '2016-11-03 06:40:22', ''),
(198, 9, 1, 19, 1, '2016-11-03 08:34:49', ''),
(199, 9, 1, 20, 1, '2016-11-03 08:34:49', ''),
(200, 10, 2, 4, 1, '2016-11-03 08:40:20', ''),
(201, 8, 19, 2, 1, '2016-11-03 08:40:58', ''),
(202, 8, 20, 2, 1, '2016-11-03 08:40:58', ''),
(203, 12, 2, 2, 1, '2016-11-03 08:41:31', 'semmi'),
(204, 12, 2, 3, 1, '2016-11-03 08:42:02', 'semmi'),
(205, 10, 2, 4, 1, '2016-11-03 08:49:29', ''),
(206, 8, 19, 2, 1, '2016-11-04 07:17:47', ''),
(207, 8, 20, 2, 1, '2016-11-04 07:17:47', ''),
(208, 6, 19, 19, 1, '2016-11-04 08:17:22', 'Semmi extra'),
(209, 6, 13, 19, 1, '2016-11-04 08:17:22', 'Semmi extra'),
(210, 6, 19, 19, 1, '2016-11-04 08:17:22', 'Semmi extra'),
(211, 6, 6, 6, 1, '2016-11-04 08:19:03', ''),
(212, 6, 13, 6, 1, '2016-11-04 08:19:03', ''),
(213, 6, 6, 6, 1, '2016-11-04 08:19:03', ''),
(214, 1, 10, 6, 1, '2016-11-04 08:20:27', 'semmi'),
(215, 2, 6, 8, 1, '2016-11-04 08:21:21', ''),
(216, 3, 6, 5, 1, '2016-11-04 08:22:23', ''),
(217, 4, 6, 4, 1, '2016-11-04 08:24:28', ''),
(218, 7, 13, 5, 1, '2016-11-04 08:25:41', ''),
(219, 8, 19, 1, 1, '2016-11-04 08:26:32', ''),
(220, 8, 20, 1, 1, '2016-11-04 08:26:32', ''),
(221, 11, 1, 17, 1, '2016-11-04 08:28:19', 'semmi'),
(223, 8, 19, 1, 1, '2016-11-05 05:50:34', ''),
(224, 8, 20, 1, 1, '2016-11-05 05:50:34', ''),
(225, 9, 1, 19, 1, '2016-11-07 05:52:09', ''),
(226, 9, 1, 20, 1, '2016-11-07 05:52:09', ''),
(227, 8, 19, 2, 1, '2016-11-10 00:58:48', ''),
(228, 8, 20, 2, 1, '2016-11-10 00:58:48', ''),
(229, 1, 8, 19, 1, '2016-11-13 19:09:16', ''),
(230, 11, 2, 17, 1, '2016-11-13 19:10:00', 'tk');

-- --------------------------------------------------------

--
-- Table structure for table `cat_stock_transaction_type`
--

CREATE TABLE IF NOT EXISTS `cat_stock_transaction_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `string_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_636782474AC2F1F0` (`string_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `cat_stock_transaction_type`
--

INSERT INTO `cat_stock_transaction_type` (`id`, `name`, `string_id`) VALUES
(1, 'Bevételezés', 'receive'),
(2, 'Visszárú', 'return'),
(3, 'Átvételezés', 'move'),
(4, 'Selejtezés (alapanyag)', 'discardingredient'),
(5, 'Selejtezés (termék)', 'discardproduct'),
(6, 'Készletkorrekció (leltár)', 'stockcorrection'),
(7, 'Alapanyag mozgatás', 'universal'),
(8, 'Rendelés', 'order'),
(9, 'Visszavételezés', 'productback'),
(10, 'Rendelt termék selejtezése', 'productbacktotrash'),
(11, 'Értékesítés', 'paying'),
(12, 'Rendelési tétel áthelyezés', 'moveorderitem');

-- --------------------------------------------------------

--
-- Table structure for table `cat_storage`
--

CREATE TABLE IF NOT EXISTS `cat_storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storage_type` int(11) DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_74CB996285F39C3C` (`storage_type`),
  KEY `IDX_74CB99629B2A6C7E` (`supplier`),
  KEY `IDX_74CB9962727ACA70` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `cat_storage`
--

INSERT INTO `cat_storage` (`id`, `storage_type`, `supplier`, `parent_id`, `name`) VALUES
(1, 1, 1, NULL, 'Asztal-1'),
(2, 1, 1, NULL, 'Asztal-2\r\n'),
(3, 1, 1, NULL, 'Asztal-3'),
(4, 2, 1, NULL, 'SELEJT\r\n'),
(5, 3, 1, NULL, '1. raktár (Bogáncs utca)'),
(6, 3, 1, NULL, '2. raktár (Konkoly utca)'),
(8, 4, 5, NULL, 'Coca cola store'),
(9, 4, 2, NULL, 'Mogyi store'),
(10, 4, 3, NULL, 'Papsi store'),
(11, 4, 4, NULL, 'Alkesz kft'),
(12, 5, 1, NULL, 'KÉSZLETHIÁNY'),
(13, 6, 1, NULL, 'KÉSZLETTÖBBLET'),
(14, 7, 1, NULL, 'KÉSZLETEGYENLŐ'),
(17, 9, 1, NULL, 'FOGYASZTÁS'),
(18, 10, 1, NULL, 'HELYI KÉSZLET'),
(19, 11, 1, 18, 'Pult'),
(20, 11, 1, 18, 'Konyha');

-- --------------------------------------------------------

--
-- Table structure for table `cat_storage_type`
--

CREATE TABLE IF NOT EXISTS `cat_storage_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `string_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_24E868624AC2F1F0` (`string_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `cat_storage_type`
--

INSERT INTO `cat_storage_type` (`id`, `name`, `string_id`) VALUES
(1, 'Rendelési egység ', 'destination'),
(2, 'Selejt', 'discard'),
(3, 'Saját raktár', 'ownstock'),
(4, 'Beszállító', 'supplier'),
(5, 'Készlethiány', 'stockcorrectionNegative'),
(6, 'Készlettöbblet', 'stockCorrectionPositive'),
(7, 'Készletegyenlő', 'stockCorrectionNull'),
(9, 'Fogyasztás', 'consumption'),
(10, 'Helyi Készlet', 'localstorage'),
(11, 'Standhely', 'stand');

-- --------------------------------------------------------

--
-- Table structure for table `cat_supplier`
--

CREATE TABLE IF NOT EXISTS `cat_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `more_info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cat_supplier`
--

INSERT INTO `cat_supplier` (`id`, `name`, `tax_number`, `contact_person`, `tel_number`, `email`, `seat`, `site`, `more_info`) VALUES
(1, 'Vendéglátó Kft. TULAJDONOS', '8005265321', 'Kóka Károly', '+36304052445', 'kokakarcsi@coca-cola.hu', '1111 Budapest Tömő utca 12.', '', 'A szoftver használója'),
(2, 'Mogyi Kft.', '3456546433', 'Mogyorósi János', '+36305042446', 'mogyijani@mogyi.hu', '3300 Eger Kiseged utca 13.', '3300 Eger Nagy utca 13.', 'Van 10% kedvezményünk'),
(3, 'Pepsi Hungary Kft', '7764838765', 'Pepe Gábor', '+36706543524', 'pepgabi@pepsi.hu', '1230 Budapest Kossuth Lajos utca 33.', NULL, NULL),
(4, 'Alkesz Kft', '544563765', 'Alki Katalin', '+36206545364', 'alkikati@alkesz.hu', '5500 Bük Ábrányos út 55.', '5500 Bük Ábrányos út 166.', 'Semmi'),
(5, 'Coca Cola kft', '344542676', 'Tullajtos Róbert', '+365435546', 'vendegl@vendegll.hu', '1124 Budapest Böszörményi út 12.', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `cat_vat_group`
--

CREATE TABLE IF NOT EXISTS `cat_vat_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vat_value` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cat_vat_group`
--

INSERT INTO `cat_vat_group` (`id`, `name`, `vat_value`) VALUES
(1, 'ÁLTALÁNOS', '0.27'),
(2, 'Melegétel', '0.15');

-- --------------------------------------------------------

--
-- Table structure for table `ord_daily_income`
--

CREATE TABLE IF NOT EXISTS `ord_daily_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL,
  `net_income` decimal(8,2) NOT NULL,
  `vat_amount` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `ord_daily_income`
--

INSERT INTO `ord_daily_income` (`id`, `date_time`, `net_income`, `vat_amount`) VALUES
(2, '2016-10-30 19:28:05', '1716.53', '463.47'),
(3, '2016-10-31 22:15:51', '11513.18', '3066.82'),
(4, '2016-11-01 15:46:10', '13338.38', '3559.62'),
(5, '2016-11-02 20:38:15', '2539.27', '664.73'),
(6, '2016-11-04 08:28:19', '708.66', '191.34'),
(7, '2016-11-13 19:10:00', '393.70', '106.30');

-- --------------------------------------------------------

--
-- Table structure for table `ord_order_item_in_storage`
--

CREATE TABLE IF NOT EXISTS `ord_order_item_in_storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storage_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `stock_transaction_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EEE8D0B25CC5DB90` (`storage_id`),
  KEY `IDX_EEE8D0B24584665A` (`product_id`),
  KEY `IDX_EEE8D0B288237244` (`stock_transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=138 ;

--
-- Dumping data for table `ord_order_item_in_storage`
--

INSERT INTO `ord_order_item_in_storage` (`id`, `storage_id`, `product_id`, `amount`, `price`, `stock_transaction_id`) VALUES
(78, 17, 4, 2, '44.00', 113),
(79, 17, 7, 2, '500.00', 121),
(80, 19, 10, 1, '600.00', 115),
(81, 19, 11, 2, '2300.00', 117),
(82, 17, 8, 2, '400.00', 113),
(83, 4, 35, 2, '66886.00', 151),
(84, 17, 10, 2, '1200.00', 164),
(85, 17, 4, 1, '22.00', 164),
(86, 17, 4, 2, '44.00', 182),
(87, 17, 4, 2, '44.00', 182),
(88, 17, 10, 2, '1200.00', 182),
(89, 17, 4, 1, '22.00', 182),
(90, 4, 40, 2, '688.00', 189),
(91, 17, 4, 2, '44.00', 185),
(92, 17, 11, 1, '1150.00', 185),
(93, 17, 40, 1, '344.00', 164),
(94, 17, 12, 1, '1590.00', 164),
(95, 17, 4, 2, '44.00', 164),
(96, 17, 7, 1, '250.00', 164),
(97, 17, 3, 1, '450.00', 164),
(98, 17, 4, 1, '22.00', 164),
(99, 17, 7, 1, '250.00', 164),
(100, 17, 10, 3, '1800.00', 164),
(101, 17, 11, 1, '1150.00', 164),
(102, 17, 40, 1, '344.00', 164),
(103, 17, 5, 1, '530.00', 164),
(104, 17, 6, 1, '300.00', 164),
(105, 17, 8, 1, '200.00', 164),
(106, 17, 12, 1, '1590.00', 164),
(107, 17, 13, 1, '200.00', 164),
(108, 17, 14, 1, '240.00', 164),
(109, 17, 18, 1, '200.00', 164),
(110, 17, 4, 1, '22.00', 164),
(111, 17, 10, 4, '2400.00', 164),
(112, 17, 5, 5, '2650.00', 164),
(113, 17, 7, 1, '250.00', 164),
(114, 17, 10, 1, '600.00', 164),
(115, 17, 7, 1, '250.00', 167),
(116, 19, 11, 1, '1150.00', 176),
(117, 19, 11, 1, '1150.00', 176),
(118, 17, 6, 1, '300.00', 181),
(119, 17, 8, 1, '200.00', 181),
(120, 17, 13, 1, '200.00', 180),
(121, 19, 10, 1, '600.00', 192),
(122, 3, 11, 1, '1150.00', 190),
(123, 4, 10, 1, '600.00', 200),
(124, 4, 8, 1, '200.00', 205),
(125, 19, 10, 1, '600.00', 198),
(126, 3, 4, 1, '22.00', 204),
(127, 3, 11, 1, '1150.00', 204),
(128, 3, 5, 1, '530.00', 204),
(129, 17, 7, 1, '250.00', 230),
(130, 17, 3, 2, '900.00', 221),
(131, 19, 11, 1, '1150.00', 225),
(132, 1, 8, 10, '2000.00', 220),
(133, 19, 7, 1, '250.00', 225),
(134, 1, 11, 1, '1150.00', 223),
(135, 17, 7, 1, '250.00', 230),
(136, 2, 10, 1, '600.00', 227),
(137, 2, 11, 1, '1150.00', 227);

-- --------------------------------------------------------

--
-- Table structure for table `ord_product_moving`
--

CREATE TABLE IF NOT EXISTS `ord_product_moving` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_transaction_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7DB1C38B88237244` (`stock_transaction_id`),
  KEY `IDX_7DB1C38B4584665A` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=257 ;

--
-- Dumping data for table `ord_product_moving`
--

INSERT INTO `ord_product_moving` (`id`, `stock_transaction_id`, `product_id`, `amount`) VALUES
(134, 111, 4, 2),
(135, 111, 7, 2),
(136, 111, 10, 1),
(137, 111, 11, 2),
(138, 112, 8, 2),
(139, 112, 35, 2),
(140, 113, 4, 2),
(141, 113, 8, 2),
(142, 114, 10, 1),
(143, 114, 11, 2),
(144, 115, 10, 1),
(145, 117, 11, 2),
(146, 119, 10, 2),
(147, 121, 7, 2),
(148, 141, 4, 1),
(149, 143, 4, 2),
(150, 145, 4, 2),
(151, 147, 10, 2),
(152, 149, 4, 1),
(153, 149, 40, 2),
(154, 151, 35, 2),
(155, 152, 4, 2),
(156, 152, 11, 1),
(157, 152, 40, 1),
(158, 153, 12, 1),
(159, 154, 10, 2),
(160, 154, 4, 2),
(161, 154, 11, 1),
(162, 155, 10, 2),
(163, 155, 4, 1),
(164, 156, 4, 2),
(165, 156, 7, 1),
(166, 158, 3, 1),
(167, 158, 4, 1),
(168, 158, 7, 1),
(169, 158, 10, 3),
(170, 158, 11, 1),
(171, 158, 40, 1),
(172, 159, 5, 1),
(173, 159, 6, 1),
(174, 159, 8, 1),
(175, 159, 12, 1),
(176, 159, 13, 1),
(177, 159, 14, 1),
(178, 159, 18, 1),
(179, 160, 4, 1),
(180, 160, 10, 4),
(181, 161, 5, 5),
(182, 162, 7, 1),
(183, 162, 10, 1),
(184, 164, 10, 2),
(185, 164, 4, 1),
(186, 164, 40, 1),
(187, 164, 12, 1),
(188, 164, 4, 2),
(189, 164, 7, 1),
(190, 164, 3, 1),
(191, 164, 4, 1),
(192, 164, 7, 1),
(193, 164, 10, 3),
(194, 164, 11, 1),
(195, 164, 40, 1),
(196, 164, 5, 1),
(197, 164, 6, 1),
(198, 164, 8, 1),
(199, 164, 12, 1),
(200, 164, 13, 1),
(201, 164, 14, 1),
(202, 164, 18, 1),
(203, 164, 4, 1),
(204, 164, 10, 4),
(205, 164, 5, 5),
(206, 164, 7, 1),
(207, 164, 10, 1),
(208, 165, 7, 1),
(209, 165, 11, 1),
(210, 167, 7, 1),
(211, 170, 11, 1),
(212, 171, 6, 1),
(213, 171, 8, 1),
(214, 176, 11, 1),
(215, 176, 11, 1),
(216, 179, 13, 1),
(217, 180, 13, 1),
(218, 181, 6, 1),
(219, 181, 8, 1),
(220, 182, 4, 2),
(221, 182, 4, 2),
(222, 182, 10, 2),
(223, 182, 4, 1),
(224, 185, 4, 2),
(225, 185, 11, 1),
(226, 189, 40, 2),
(227, 190, 10, 1),
(228, 190, 11, 1),
(229, 192, 10, 1),
(230, 194, 10, 1),
(231, 195, 8, 1),
(232, 196, 10, 1),
(233, 198, 10, 1),
(234, 200, 10, 1),
(235, 201, 4, 1),
(236, 201, 11, 1),
(237, 202, 5, 1),
(238, 203, 4, 1),
(239, 204, 4, 1),
(240, 204, 11, 1),
(241, 204, 5, 1),
(242, 205, 8, 1),
(243, 206, 7, 1),
(244, 219, 3, 2),
(245, 219, 11, 1),
(246, 220, 8, 10),
(247, 221, 3, 2),
(248, 223, 7, 1),
(249, 223, 11, 1),
(250, 225, 11, 1),
(251, 225, 7, 1),
(252, 227, 7, 1),
(253, 227, 10, 1),
(254, 227, 11, 1),
(255, 230, 7, 1),
(256, 230, 7, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app_event_log`
--
ALTER TABLE `app_event_log`
  ADD CONSTRAINT `FK_369FF223401B253C` FOREIGN KEY (`event_type_id`) REFERENCES `app_event_type` (`id`),
  ADD CONSTRAINT `FK_369FF223A76ED395` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`id`);

--
-- Constraints for table `app_user`
--
ALTER TABLE `app_user`
  ADD CONSTRAINT `FK_88BDF3E9D60322AC` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`id`);

--
-- Constraints for table `cat_ingredient`
--
ALTER TABLE `cat_ingredient`
  ADD CONSTRAINT `FK_B64D53818C5289C9` FOREIGN KEY (`ingredient_group_id`) REFERENCES `cat_ingredient_group` (`id`),
  ADD CONSTRAINT `FK_B64D5381F98A7BCB` FOREIGN KEY (`ingredient_unit_id`) REFERENCES `cat_ingredient_unit` (`id`);

--
-- Constraints for table `cat_ingredient_in_product`
--
ALTER TABLE `cat_ingredient_in_product`
  ADD CONSTRAINT `FK_20BA3B5F4584665A` FOREIGN KEY (`product_id`) REFERENCES `cat_product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_20BA3B5F933FE08C` FOREIGN KEY (`ingredient_id`) REFERENCES `cat_ingredient` (`id`);

--
-- Constraints for table `cat_ingredient_moving`
--
ALTER TABLE `cat_ingredient_moving`
  ADD CONSTRAINT `FK_FA0211A488237244` FOREIGN KEY (`stock_transaction_id`) REFERENCES `cat_stock_transaction` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FA0211A4933FE08C` FOREIGN KEY (`ingredient_id`) REFERENCES `cat_ingredient` (`id`);

--
-- Constraints for table `cat_product`
--
ALTER TABLE `cat_product`
  ADD CONSTRAINT `FK_F3FB86FB35E4B3D0` FOREIGN KEY (`product_group_id`) REFERENCES `cat_product_group` (`id`),
  ADD CONSTRAINT `FK_F3FB86FBE8C706E8` FOREIGN KEY (`vat_group_id`) REFERENCES `cat_vat_group` (`id`);

--
-- Constraints for table `cat_product_aware_storage_type`
--
ALTER TABLE `cat_product_aware_storage_type`
  ADD CONSTRAINT `FK_43ED29A2B270BFF1` FOREIGN KEY (`storage_type_id`) REFERENCES `cat_storage_type` (`id`);

--
-- Constraints for table `cat_real_storage_type`
--
ALTER TABLE `cat_real_storage_type`
  ADD CONSTRAINT `FK_9E06F6E3B270BFF1` FOREIGN KEY (`storage_type_id`) REFERENCES `cat_storage_type` (`id`);

--
-- Constraints for table `cat_stock`
--
ALTER TABLE `cat_stock`
  ADD CONSTRAINT `FK_F74DE68D547A1B34` FOREIGN KEY (`storage`) REFERENCES `cat_storage` (`id`),
  ADD CONSTRAINT `FK_F74DE68D6BAF7870` FOREIGN KEY (`ingredient`) REFERENCES `cat_ingredient` (`id`);

--
-- Constraints for table `cat_stock_transaction`
--
ALTER TABLE `cat_stock_transaction`
  ADD CONSTRAINT `FK_84B1928677611D97` FOREIGN KEY (`from_storage_id`) REFERENCES `cat_storage` (`id`),
  ADD CONSTRAINT `FK_84B192867BF639ED` FOREIGN KEY (`to_storage_id`) REFERENCES `cat_storage` (`id`),
  ADD CONSTRAINT `FK_84B19286A76ED395` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`id`),
  ADD CONSTRAINT `FK_84B19286B65D9CEE` FOREIGN KEY (`stock_transaction_type_id`) REFERENCES `cat_stock_transaction_type` (`id`);

--
-- Constraints for table `cat_storage`
--
ALTER TABLE `cat_storage`
  ADD CONSTRAINT `FK_74CB9962727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `cat_storage` (`id`),
  ADD CONSTRAINT `FK_74CB996285F39C3C` FOREIGN KEY (`storage_type`) REFERENCES `cat_storage_type` (`id`),
  ADD CONSTRAINT `FK_74CB99629B2A6C7E` FOREIGN KEY (`supplier`) REFERENCES `cat_supplier` (`id`);

--
-- Constraints for table `ord_order_item_in_storage`
--
ALTER TABLE `ord_order_item_in_storage`
  ADD CONSTRAINT `FK_EEE8D0B24584665A` FOREIGN KEY (`product_id`) REFERENCES `cat_product` (`id`),
  ADD CONSTRAINT `FK_EEE8D0B25CC5DB90` FOREIGN KEY (`storage_id`) REFERENCES `cat_storage` (`id`),
  ADD CONSTRAINT `FK_EEE8D0B288237244` FOREIGN KEY (`stock_transaction_id`) REFERENCES `cat_stock_transaction` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ord_product_moving`
--
ALTER TABLE `ord_product_moving`
  ADD CONSTRAINT `FK_7DB1C38B4584665A` FOREIGN KEY (`product_id`) REFERENCES `cat_product` (`id`),
  ADD CONSTRAINT `FK_7DB1C38B88237244` FOREIGN KEY (`stock_transaction_id`) REFERENCES `cat_stock_transaction` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
