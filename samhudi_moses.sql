-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 14, 2026 at 10:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `samhudi`
--

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

CREATE TABLE `families` (
  `id` int(11) NOT NULL,
  `family_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `family_members`
--

CREATE TABLE `family_members` (
  `id` int(11) NOT NULL,
  `family_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `father_id` int(11) DEFAULT NULL,
  `mother_id` int(11) DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `gender` enum('L','P') DEFAULT NULL,
  `birth_place` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `death_date` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_alive` tinyint(1) DEFAULT 1,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family_members`
--

INSERT INTO `family_members` (`id`, `family_id`, `user_id`, `father_id`, `mother_id`, `full_name`, `gender`, `birth_place`, `birth_date`, `death_date`, `phone`, `email`, `occupation`, `address`, `photo`, `is_alive`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, 'H.M Samhudi', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'approved', '2026-07-13 18:27:38', '2026-07-13 18:27:38');

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE `forums` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `id` int(11) NOT NULL,
  `forum_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foundations`
--

CREATE TABLE `foundations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marriages`
--

CREATE TABLE `marriages` (
  `id` int(11) NOT NULL,
  `husband_id` int(11) DEFAULT NULL,
  `wife_id` int(11) DEFAULT NULL,
  `marriage_date` date DEFAULT NULL,
  `status` enum('married','divorced','widowed') DEFAULT 'married',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `status` enum('draft','publish') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
--

CREATE TABLE `news_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_category_relation`
--

CREATE TABLE `news_category_relation` (
  `news_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expired_at` datetime NOT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `otp_code`, `expired_at`, `is_used`, `created_at`) VALUES
(7, 6, '757625', '2026-07-02 06:23:24', 1, '2026-07-02 06:13:24'),
(8, 7, '654510', '2026-07-02 07:09:13', 1, '2026-07-02 06:59:13'),
(9, 7, '477909', '2026-07-02 08:06:38', 1, '2026-07-02 07:56:39');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expired_at`, `used`, `created_at`) VALUES
(1, 7, 'ab73077e714cd2bf8b9d0cad919b1b4575934c86e7754474ed06b4393de54758', '2026-07-02 08:24:21', 1, '2026-07-02 07:54:21'),
(2, 6, 'b2dc25fb1383e3a2eabcf48c25af9fb0f5671b032b86698964a3a27e1d390162', '2026-07-02 08:38:20', 1, '2026-07-02 08:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','member') DEFAULT 'member',
  `family_id` int(11) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `phone`, `password`, `role`, `family_id`, `is_verified`, `status`, `created_at`, `updated_at`) VALUES
(6, 'Test', NULL, 'pegestore1110@gmail.com', NULL, '$2y$12$eR5u9HsjjG.Y.WGm3Cdw7euUFYUn1s8jkFOKRDrzNEcciubbuwNuO', 'member', NULL, 1, 'active', '2026-07-02 06:13:24', '2026-07-02 08:09:14'),
(7, 'Alif', NULL, 'alifmuzakki1110@gmail.com', NULL, '$2y$12$p1Xa7KtNLxBbuAwC5zA0M.9onzWlanm6U0xmGZTw2esQv29r.4hF.', 'member', NULL, 1, 'active', '2026-07-02 06:59:13', '2026-07-02 07:57:00'),
(8, 'Admin Utama', 'admin_samhudi', 'admin@samhudi.com', '081234567890', '$2a$12$85VctWPtQOIDAzTZAz8CBO3O.Zsd8Viar4T0yHgAKrbBMqlRiQQP.', 'admin', NULL, 1, 'active', '2026-07-02 10:12:13', '2026-07-02 10:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `wills`
--

CREATE TABLE `wills` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` enum('private','public') DEFAULT 'private',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wills`
--

INSERT INTO `wills` (`id`, `title`, `content`, `file_pdf`, `created_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Iman & Ketakwaan', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'public', '2026-07-13 08:39:21', '2026-07-13 08:39:21'),
(2, 'Iman & Ketakwaan', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'public', '2026-07-13 08:39:21', '2026-07-13 08:39:21'),
(3, 'Iman & Ketakwaan', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'public', '2026-07-13 08:39:21', '2026-07-13 08:39:21'),
(4, 'Iman & Ketakwaan', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'public', '2026-07-13 08:39:21', '2026-07-13 08:39:21'),
(5, 'Iman & Ketakwaan', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'public', '2026-07-13 08:39:21', '2026-07-13 08:39:21'),
(6, 'Iman & Ketakwaan', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'public', '2026-07-13 08:39:21', '2026-07-13 08:39:21'),
(10, 'Test sudah bisa edit', 'Test sudah bisa edit', NULL, NULL, 'private', '2026-07-13 09:04:28', '2026-07-13 09:59:32'),
(11, 'Point 8', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'private', '2026-07-13 10:06:12', '2026-07-13 10:06:12'),
(12, 'Point 9', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'private', '2026-07-13 10:06:12', '2026-07-13 10:06:12'),
(13, 'Point 10', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'private', '2026-07-13 10:06:12', '2026-07-13 10:06:12'),
(14, 'Point 11', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'private', '2026-07-13 10:06:12', '2026-07-13 10:06:12'),
(15, 'Point 12', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'private', '2026-07-13 10:06:12', '2026-07-13 10:06:12'),
(16, 'Point 13', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'private', '2026-07-13 10:06:12', '2026-07-13 10:06:12'),
(17, 'Point 14', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'private', '2026-07-13 10:06:12', '2026-07-13 10:06:12'),
(18, 'Point 15', 'Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur\'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.', NULL, NULL, 'private', '2026-07-13 10:06:12', '2026-07-13 10:06:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `family_members`
--
ALTER TABLE `family_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `family_id` (`family_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `father_id` (`father_id`),
  ADD KEY `mother_id` (`mother_id`);

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `foundations`
--
ALTER TABLE `foundations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marriages`
--
ALTER TABLE `marriages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `husband_id` (`husband_id`),
  ADD KEY `wife_id` (`wife_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `news_categories`
--
ALTER TABLE `news_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_category_relation`
--
ALTER TABLE `news_category_relation`
  ADD PRIMARY KEY (`news_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `family_id` (`family_id`);

--
-- Indexes for table `wills`
--
ALTER TABLE `wills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `families`
--
ALTER TABLE `families`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family_members`
--
ALTER TABLE `family_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foundations`
--
ALTER TABLE `foundations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marriages`
--
ALTER TABLE `marriages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wills`
--
ALTER TABLE `wills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `family_members`
--
ALTER TABLE `family_members`
  ADD CONSTRAINT `family_members_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`),
  ADD CONSTRAINT `family_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `family_members_ibfk_3` FOREIGN KEY (`father_id`) REFERENCES `family_members` (`id`),
  ADD CONSTRAINT `family_members_ibfk_4` FOREIGN KEY (`mother_id`) REFERENCES `family_members` (`id`);

--
-- Constraints for table `forums`
--
ALTER TABLE `forums`
  ADD CONSTRAINT `forums_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `forum_comments_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `forum_comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `forum_comments` (`id`);

--
-- Constraints for table `marriages`
--
ALTER TABLE `marriages`
  ADD CONSTRAINT `marriages_ibfk_1` FOREIGN KEY (`husband_id`) REFERENCES `family_members` (`id`),
  ADD CONSTRAINT `marriages_ibfk_2` FOREIGN KEY (`wife_id`) REFERENCES `family_members` (`id`);

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `news_category_relation`
--
ALTER TABLE `news_category_relation`
  ADD CONSTRAINT `news_category_relation_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_category_relation_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD CONSTRAINT `otp_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`);

--
-- Constraints for table `wills`
--
ALTER TABLE `wills`
  ADD CONSTRAINT `wills_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
