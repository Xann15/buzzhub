-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Okt 2023 pada 15.43
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myapp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `comment`
--

INSERT INTO `comment` (`id`, `comment_id`, `user_id`, `comment`, `createdAt`) VALUES
(108, 311, 78, '🕊️', '2023-08-21 12:00:06'),
(109, 316, 79, '💕', '2023-08-21 12:35:52'),
(110, 318, 78, '👍🏻', '2023-08-26 10:13:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `direct_message`
--

CREATE TABLE `direct_message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `media` blob DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `direct_message`
--

INSERT INTO `direct_message` (`id`, `user_id`, `people_id`, `message`, `media`, `time`) VALUES
(236, 78, 81, 'wibu wibu', NULL, '2023-08-22 00:54:28'),
(237, 81, 78, 'Apa kuntul', NULL, '2023-08-22 00:56:45'),
(238, 83, 81, 'mikel sipit', NULL, '2023-08-22 01:21:42'),
(239, 83, 81, 'mikel sipit', NULL, '2023-08-22 01:21:45'),
(240, 81, 83, 'Ajo kontol', NULL, '2023-08-22 01:22:43'),
(241, 83, 81, 'p.balap mio', NULL, '2023-08-22 01:43:54'),
(242, 78, 82, 'iya', NULL, '2023-08-26 10:21:45'),
(243, 78, 80, 'i need 0.05ETH for joined friend.tech', NULL, '2023-08-26 10:23:02'),
(244, 79, 78, 'can i get verification?', NULL, '2023-08-26 10:55:27'),
(245, 79, 78, 'p', NULL, '2023-08-26 10:56:08'),
(246, 79, 78, 'p', NULL, '2023-08-26 10:56:09'),
(247, 79, 78, 'p', NULL, '2023-08-26 10:56:09'),
(248, 79, 78, 'p', NULL, '2023-08-26 10:56:09'),
(249, 79, 78, 'can i get verification?', NULL, '2023-08-26 10:56:23'),
(250, 80, 78, 'ok, send us your wallet address', NULL, '2023-08-26 11:00:35'),
(251, 85, 78, 'aku ped', NULL, '2023-08-30 21:17:05'),
(252, 85, 78, 'pedo', NULL, '2023-08-30 21:17:18'),
(253, 78, 86, '&lt;script defer src=&quot;https://use.fontawesome.com/releases/v5.15.4/js/all.js&quot; integrity=&quot;sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc&quot; crossorigin=&quot;anonymous&quot;&gt;&lt;/script&gt;', NULL, '2023-08-30 22:47:07'),
(254, 85, 86, 'are u gae', NULL, '2023-08-30 23:36:01'),
(255, 85, 86, 'are u gae', NULL, '2023-08-30 23:36:01'),
(256, 85, 86, 'are u gae', NULL, '2023-08-30 23:36:01'),
(257, 78, 79, 'nope', NULL, '2023-09-10 11:04:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `followers` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `followers`, `createdAt`) VALUES
(663, 78, 79, '2023-08-21 11:21:17'),
(682, 79, 78, '2023-08-21 11:47:51'),
(683, 81, 78, '2023-08-22 00:44:59'),
(686, 78, 81, '2023-08-22 01:16:33'),
(688, 82, 81, '2023-08-22 01:17:01'),
(689, 82, 83, '2023-08-22 01:21:04'),
(690, 82, 78, '2023-08-30 21:01:29'),
(691, 78, 85, '2023-08-30 21:16:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `following`
--

CREATE TABLE `following` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `following` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `following`
--

INSERT INTO `following` (`id`, `user_id`, `following`, `createdAt`) VALUES
(664, 79, 78, '2023-08-21 11:21:17'),
(683, 78, 79, '2023-08-21 11:47:51'),
(684, 78, 81, '2023-08-22 00:44:59'),
(687, 81, 78, '2023-08-22 01:16:33'),
(689, 81, 82, '2023-08-22 01:17:01'),
(690, 83, 82, '2023-08-22 01:21:04'),
(691, 78, 82, '2023-08-30 21:01:29'),
(692, 85, 78, '2023-08-30 21:16:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hashtag`
--

CREATE TABLE `hashtag` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `hashtag` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `hashtag`
--

INSERT INTO `hashtag` (`id`, `post_id`, `hashtag`) VALUES
(1, 311, 'fyp'),
(2, 311, 'motivasi'),
(3, 311, 'selfimprovement'),
(4, 311, 'selfdevelopment'),
(5, 311, 'panjialdno'),
(6, 312, 'martis'),
(7, 312, 'fyp'),
(8, 312, 'mlbb'),
(9, 312, 'mobilelegends'),
(10, 312, 'alok'),
(11, 312, 'topglobal'),
(12, 313, 'kiww'),
(13, 315, 'fyp'),
(14, 317, 'fyp'),
(15, 317, 'sky'),
(16, 318, 'fyp'),
(17, 318, 'saham'),
(18, 318, 'rups'),
(19, 318, 'tbk'),
(20, 318, 'fypdongg'),
(21, 319, 'fionyjkt48'),
(22, 319, 'fyp'),
(23, 320, 'fionyjkt48'),
(24, 320, 'fyp'),
(25, 321, 'fionyjkt48'),
(26, 321, 'fyp'),
(27, 321, 'oshiku'),
(28, 324, 'bulan'),
(29, 324, 'bulanbiru'),
(30, 324, 'bluemoon'),
(31, 325, 'pedo'),
(32, 341, 'pedo'),
(33, 399, 'wallpapper'),
(34, 399, 'hd'),
(35, 399, '4k'),
(36, 399, 'windows'),
(37, 399, 'fyp');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `posted_id` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `posted_id`, `createdAt`) VALUES
(747, 78, 311, 78, '2023-08-21 11:59:57'),
(748, 79, 311, 78, '2023-08-21 12:19:47'),
(749, 79, 315, 79, '2023-08-21 12:30:58'),
(750, 79, 316, 79, '2023-08-21 12:32:22'),
(751, 79, 317, 79, '2023-08-21 22:45:07'),
(752, 78, 322, 82, '2023-08-22 00:44:49'),
(753, 78, 323, 82, '2023-08-22 00:52:54'),
(754, 78, 318, 80, '2023-08-26 10:13:11'),
(755, 78, 320, 81, '2023-08-26 10:20:10'),
(756, 78, 317, 79, '2023-08-26 10:20:58'),
(757, 78, 324, 82, '2023-08-30 21:00:55'),
(760, 78, 325, 86, '2023-08-30 22:59:07'),
(761, 86, 325, 86, '2023-08-30 23:15:01'),
(764, 78, 399, 78, '2023-09-10 11:00:44'),
(765, 78, 312, 78, '2023-09-10 11:03:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `list_direct_message`
--

CREATE TABLE `list_direct_message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `last_message` text DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `list_direct_message`
--

INSERT INTO `list_direct_message` (`id`, `user_id`, `people_id`, `last_message`, `time`) VALUES
(77, 78, 81, 'Apa kuntul', '2023-08-22 00:56:45'),
(78, 81, 78, 'Apa kuntul', '2023-08-22 00:56:45'),
(79, 83, 81, 'p.balap mio', '2023-08-22 01:43:54'),
(80, 81, 83, 'p.balap mio', '2023-08-22 01:43:54'),
(81, 78, 82, 'iya', '2023-08-26 10:21:45'),
(82, 78, 80, 'ok, send us your wallet address', '2023-08-26 11:00:35'),
(83, 79, 78, 'nope', '2023-09-10 11:04:10'),
(84, 80, 78, 'ok, send us your wallet address', '2023-08-26 11:00:35'),
(85, 85, 78, 'pedo', '2023-08-30 21:17:18'),
(86, 78, 86, '&lt;script defer src=&quot;https://use.fontawesome.com/releases/v5.15.4/js/all.js&quot; integrity=&quot;sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc&quot; crossorigin=&quot;anonymous&quot;&gt;&lt;/script&gt;', '2023-08-30 22:47:07'),
(87, 85, 86, 'are u gae', '2023-08-30 23:36:01'),
(88, 78, 79, 'nope', '2023-09-10 11:04:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `notification`
--

INSERT INTO `notification` (`id`, `user_id`, `person_id`, `post_id`, `type`, `time`) VALUES
(824, 78, 79, NULL, 'visitProfile', '2023-08-21 11:16:38'),
(825, 78, 79, NULL, 'follow', '2023-08-21 11:21:17'),
(826, 79, 78, NULL, 'visitProfile', '2023-08-21 11:22:54'),
(845, 79, 78, NULL, 'follow', '2023-08-21 11:47:51'),
(846, 78, 79, 311, 'likePost', '2023-08-21 12:19:47'),
(847, 80, 78, NULL, 'visitProfile', '2023-08-22 00:17:23'),
(848, 82, 78, NULL, 'visitProfile', '2023-08-22 00:19:14'),
(849, 81, 78, NULL, 'visitProfile', '2023-08-22 00:19:17'),
(850, 82, 78, 322, 'likePost', '2023-08-22 00:44:49'),
(851, 81, 78, NULL, 'follow', '2023-08-22 00:44:59'),
(852, 82, 78, 323, 'likePost', '2023-08-22 00:52:54'),
(854, 82, 81, NULL, 'visitProfile', '2023-08-22 01:14:30'),
(855, 78, 81, NULL, 'visitProfile', '2023-08-22 01:14:52'),
(857, 78, 81, NULL, 'follow', '2023-08-22 01:16:33'),
(859, 82, 81, NULL, 'follow', '2023-08-22 01:17:01'),
(860, 82, 83, NULL, 'visitProfile', '2023-08-22 01:21:01'),
(861, 82, 83, NULL, 'follow', '2023-08-22 01:21:04'),
(862, 81, 83, NULL, 'visitProfile', '2023-08-22 01:21:30'),
(863, 79, 82, NULL, 'visitProfile', '2023-08-22 01:22:54'),
(864, 84, 78, NULL, 'visitProfile', '2023-08-22 01:32:12'),
(865, 80, 78, 318, 'comment', '2023-08-26 10:13:09'),
(866, 80, 78, 318, 'likePost', '2023-08-26 10:13:11'),
(867, 81, 78, 320, 'likePost', '2023-08-26 10:20:10'),
(868, 79, 78, 317, 'likePost', '2023-08-26 10:20:58'),
(869, 82, 78, NULL, 'visitProfile', '2023-08-26 10:21:31'),
(870, 80, 78, NULL, 'visitProfile', '2023-08-26 10:21:53'),
(871, 78, 79, NULL, 'visitProfile', '2023-08-26 10:53:19'),
(872, 80, 82, NULL, 'visitProfile', '2023-08-30 20:56:29'),
(873, 82, 78, 324, 'likePost', '2023-08-30 21:00:55'),
(874, 82, 78, NULL, 'visitProfile', '2023-08-30 21:01:27'),
(875, 82, 78, NULL, 'follow', '2023-08-30 21:01:29'),
(876, 78, 82, NULL, 'visitProfile', '2023-08-30 21:06:22'),
(877, 78, 85, NULL, 'visitProfile', '2023-08-30 21:16:47'),
(878, 78, 85, NULL, 'follow', '2023-08-30 21:16:56'),
(879, 86, 78, NULL, 'visitProfile', '2023-08-30 22:46:50'),
(880, 78, 86, NULL, 'visitProfile', '2023-08-30 22:47:33'),
(883, 86, 78, 325, 'likePost', '2023-08-30 22:59:07'),
(884, 86, 85, NULL, 'visitProfile', '2023-08-30 23:35:38'),
(886, 85, 78, NULL, 'visitProfile', '2023-08-30 23:45:10'),
(887, 85, 78, NULL, 'visitProfile', '2023-09-10 11:03:07'),
(888, 86, 78, NULL, 'visitProfile', '2023-09-10 11:03:08'),
(889, 87, 78, NULL, 'visitProfile', '2023-09-10 11:03:15'),
(890, 79, 78, NULL, 'visitProfile', '2023-09-10 11:06:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `post` longblob DEFAULT NULL,
  `tweet` text DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `hastag` varchar(25) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `show_comment` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `type`, `post`, `tweet`, `caption`, `hastag`, `createdAt`, `show_comment`) VALUES
(311, 78, 'video', 0x36346533393761333561386134382e32333238333236392e6d7034, NULL, '😁 @acubadak #fyp #motivasi #selfimprovement #selfdevelopment #panjialdno', NULL, '2023-08-21 11:58:11', 'true'),
(312, 78, 'post', 0x36346533396231343837653335322e36363434373932382e6a7067, NULL, 'ashura.. #martis #fyp #mlbb #mobilelegends #alok #topglobal', NULL, '2023-08-26 15:19:46', 'true'),
(315, 79, 'video', 0x36346533396634666539623862352e35313739383137312e6d7034, NULL, '#fyp', NULL, '2023-08-21 12:30:55', 'true'),
(316, 79, 'video', 0x36346533396639663763396262312e32353530313538342e6d7034, NULL, 'i miss you much', NULL, '2023-08-21 12:32:15', 'true'),
(317, 79, 'video', 0x36346534326631383530373038342e32303934343339342e6d7034, NULL, '🌌 #fyp #sky', NULL, '2023-08-21 22:44:24', 'true'),
(318, 80, 'video', 0x36346534333066613137323132342e32303830393233352e6d7034, NULL, 'RUPS Nkcl 📈 #fyp #saham #rups #tbk #fypdongg', NULL, '2023-08-21 22:52:26', 'true'),
(320, 81, 'video', 0x36346534343663313830356630382e31303333343037302e6d7034, NULL, 'Cepio🤍#fionyjkt48#fyp', NULL, '2023-08-26 15:19:46', 'true'),
(321, 81, 'video', 0x36346534343730396439363330362e39363833353936352e6d7034, NULL, 'Cepiooo#fionyjkt48#fyp#oshiku', NULL, '2023-08-26 15:19:46', 'true'),
(322, 82, 'video', 0x36346534346165383466316564352e30303536343239382e6d7034, NULL, 'TRIO POKE', NULL, '2023-08-22 00:43:04', 'true'),
(323, 82, 'video', 0x36346534346237623639633834352e33343631363734332e6d7034, NULL, 'Kontol!!!', NULL, '2023-08-22 00:45:31', 'true'),
(324, 82, 'post', 0x36346566663430353664393338372e36313636333734362e6a7067, NULL, 'BLUE MOON #bulan#bulanbiru#bluemoon', NULL, '2023-08-30 20:59:33', 'true'),
(325, 86, 'post', 0x36346630306662343135613434312e38373135393830302e6a7067, NULL, 'info abang ini lagi nyari anak kecil #pedo', NULL, '2023-08-30 22:57:40', 'true'),
(342, 78, 'tweet', NULL, 'tian pedo', NULL, NULL, '2023-08-30 23:38:12', 'true'),
(399, 78, 'post', 0x36346664653831653165386161392e36313831363435322e6a7067, NULL, 'windows 11 black rose wallpapper hd #wallpapper #hd #4k #windows #fyp', NULL, '2023-09-10 11:00:30', 'true'),
(400, 78, 'tweet', NULL, 'hallo', NULL, NULL, '2023-09-10 11:02:21', 'true');

-- --------------------------------------------------------

--
-- Struktur dari tabel `story`
--

CREATE TABLE `story` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `media` blob DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `story`
--

INSERT INTO `story` (`id`, `user_id`, `type`, `media`, `post_id`, `time`) VALUES
(25, 79, 'post', 0x36346561313633623063353538372e38313538373537312e6a7067, NULL, '2023-08-26 10:11:55'),
(26, 82, 'video', 0x36346566663533363966373162392e34333133333335382e6d7034, NULL, '2023-08-30 21:04:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role` varchar(25) DEFAULT NULL,
  `profile_picture` blob DEFAULT NULL,
  `verified` int(1) NOT NULL,
  `account` varchar(50) DEFAULT NULL,
  `theme` varchar(25) DEFAULT NULL,
  `username` varchar(75) DEFAULT NULL,
  `name` varchar(75) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `followers` int(11) NOT NULL,
  `following` int(11) DEFAULT NULL,
  `joined` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `role`, `profile_picture`, `verified`, `account`, `theme`, `username`, `name`, `password`, `bio`, `followers`, `following`, `joined`) VALUES
(78, 'user', 0x2e2e2f2e2e2f6173736574732f757365725f70726f66696c655f706963747572652f313639323633373234332e706e67, 1, 'public', 'dark', 'panjialdno', 'Xann', '$2y$10$i36lTPyRUBVVjD0TwF6ymOGW5E5aH1gy3WnbcXpJSly6hJHFritgm', NULL, 0, NULL, '08/2023'),
(79, 'user', 0x2e2e2f2e2e2f6173736574732f757365725f70726f66696c655f706963747572652f313639323633383431342e706e67, 0, 'public', NULL, 'user150622', 'qwerty', '$2y$10$mkQ2Vy1mKsaS2QCT.YqdneSiGpdc95f66i80xkesMVrQQp/9JI51W', NULL, 0, NULL, '08/2023'),
(80, 'user', 0x64656661756c742e6a7067, 0, 'public', NULL, 'otto', 'otto.', '$2y$10$62HWWVs4RxOyntFKaEoGJ.spuN49bSgY26XMq/Gs0NgtIOh8DQnoe', NULL, 0, NULL, '08/2023'),
(81, 'user', 0x2e2e2f2e2e2f6173736574732f757365725f70726f66696c655f706963747572652f313639323638313733352e706e67, 1, 'public', 'dark', 'chel1. z', 'Chell', '$2y$10$KzKDY3X2EPAByskkPunayup4Yre2RYHeHyDCHYEfin14mRzOEIeAO', NULL, 0, NULL, '08/2023'),
(82, 'user', 0x2e2e2f2e2e2f6173736574732f757365725f70726f66696c655f706963747572652f313639323638313539352e706e67, 1, 'public', 'dark', 'skyler', 'Skyler', '$2y$10$Ax2Szykk0TtHjbf5yWnU5ufmlr3Gx4Z.Qvxirlj6bSmLDOA1UoXnW', 'Be your self and never surender\r\n', 0, NULL, '08/2023'),
(83, 'user', 0x64656661756c742e6a7067, 0, 'public', 'dark', '5tp_batam123', 'Haikal', '$2y$10$ZWjbWU1v6Rw9jYTiArzMV.eaxG0bZ5smdATYST/1PmtO2i6rrj07S', NULL, 0, NULL, '08/2023'),
(84, 'user', 0x64656661756c742e6a7067, 1, 'public', NULL, 'noms_20', 'mr.noms', '$2y$10$KKiYGZ.r7MYJmeykjCJ9OupxUjAVNUmdasEWZuZVW3GHfZ9vQ.s7a', NULL, 0, NULL, '08/2023'),
(85, 'user', 0x2e2e2f2e2e2f6173736574732f757365725f70726f66696c655f706963747572652f313639333434383138392e706e67, 0, 'public', 'dark', 'sprcl', 'Ctian', '$2y$10$ydwllgHOwTwec14VgViJ0OI8os9Mz4HOJpQY/C4ee56HJu6Uk.n8a', NULL, 0, NULL, '08/2023'),
(86, 'user', 0x64656661756c742e6a7067, 0, 'public', NULL, '4boyzee', '4boyze', '$2y$10$qXS1j8Zo69fWCWwDg2XU1uwP5yWwVjyClqvcRalQY1K8hebO0n0QK', NULL, 0, NULL, '08/2023'),
(87, 'user', 0x64656661756c742e6a7067, 0, 'public', 'dark', 'falx', 'fal', '$2y$10$Z6Ksc.OC7QCR89NcV095QOWBZrBuC/Yvic/cyCA7e0etDKK9CWr.q', NULL, 0, NULL, '09/2023'),
(88, 'user', 0x64656661756c742e6a7067, 0, 'public', NULL, 'max', 'max', '$2y$10$eUpgQ/R39IztTw4/yddd7.V8GXuuKIKjdQgi04CXYSUn/C8vB8mjG', NULL, 0, NULL, '10/2023');

-- --------------------------------------------------------

--
-- Struktur dari tabel `views`
--

CREATE TABLE `views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `story_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `views`
--

INSERT INTO `views` (`id`, `user_id`, `post_id`, `story_id`) VALUES
(361, 78, 311, NULL),
(362, 79, 312, NULL),
(364, 79, 311, NULL),
(365, 79, 315, NULL),
(366, 79, 316, NULL),
(367, 79, 317, NULL),
(370, 81, 322, NULL),
(371, 78, 322, NULL),
(372, 78, 321, NULL),
(373, 81, 321, NULL),
(374, 78, 320, NULL),
(375, 78, 323, NULL),
(376, 81, 312, NULL),
(377, 82, 315, NULL),
(378, 82, 317, NULL),
(379, 82, 312, NULL),
(380, 84, 323, NULL),
(381, 78, 318, NULL),
(382, 78, 312, NULL),
(383, 78, 315, NULL),
(384, 78, 317, NULL),
(385, 78, 316, NULL),
(386, 82, 321, NULL),
(387, 82, 318, NULL),
(388, 78, 324, NULL),
(389, 82, 324, NULL),
(390, 78, 325, NULL),
(392, 78, 399, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `direct_message`
--
ALTER TABLE `direct_message`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `followers` (`followers`);

--
-- Indeks untuk tabel `following`
--
ALTER TABLE `following`
  ADD PRIMARY KEY (`id`),
  ADD KEY `following` (`following`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `hashtag`
--
ALTER TABLE `hashtag`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indeks untuk tabel `list_direct_message`
--
ALTER TABLE `list_direct_message`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indeks untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `story`
--
ALTER TABLE `story`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`post_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`post_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `story_id` (`story_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT untuk tabel `direct_message`
--
ALTER TABLE `direct_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT untuk tabel `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=692;

--
-- AUTO_INCREMENT untuk tabel `following`
--
ALTER TABLE `following`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=693;

--
-- AUTO_INCREMENT untuk tabel `hashtag`
--
ALTER TABLE `hashtag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=766;

--
-- AUTO_INCREMENT untuk tabel `list_direct_message`
--
ALTER TABLE `list_direct_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT untuk tabel `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=891;

--
-- AUTO_INCREMENT untuk tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=401;

--
-- AUTO_INCREMENT untuk tabel `story`
--
ALTER TABLE `story`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT untuk tabel `views`
--
ALTER TABLE `views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=393;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `following`
--
ALTER TABLE `following`
  ADD CONSTRAINT `following_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `story`
--
ALTER TABLE `story`
  ADD CONSTRAINT `story_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `views`
--
ALTER TABLE `views`
  ADD CONSTRAINT `views_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `views_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `views_ibfk_3` FOREIGN KEY (`story_id`) REFERENCES `story` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
