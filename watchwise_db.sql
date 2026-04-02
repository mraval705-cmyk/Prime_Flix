-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2026 at 11:22 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `watchwise_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `is_active`) VALUES
(1, 'What is Watchwise?', 'Watchwise is a streaming platform for movies and shows.', 1),
(2, 'How much does it cost?', 'Plans start at ₹149 per month.', 1),
(3, 'Can I watch on mobile?', 'Yes, Watchwise works on mobile, laptop and smart TV.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(20) DEFAULT '⭐',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `title`, `description`, `icon`, `is_active`) VALUES
(1, 'Watch on any device', 'Stream on mobile, laptop, smart TV and tablet.', '📱', 1),
(2, 'Download and go', 'Save your favourite content and watch offline anytime.', '⬇️', 1),
(3, 'Unlimited entertainment', 'Enjoy movies, shows and trending content without limits.', '🎬', 1);

-- --------------------------------------------------------

--
-- Table structure for table `footer_links`
--

CREATE TABLE `footer_links` (
  `id` int NOT NULL,
  `section_name` varchar(100) NOT NULL,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `footer_links`
--

INSERT INTO `footer_links` (`id`, `section_name`, `label`, `url`, `sort_order`, `is_active`) VALUES
(1, 'Company', 'About Us', '#', 1, 1),
(2, 'Company', 'Careers', '#', 2, 1),
(3, 'Company', 'Press', '#', 3, 1),
(4, 'Company', 'Investors', '#', 4, 1),
(5, 'Support', 'Help Centre', '#', 1, 1),
(6, 'Support', 'FAQ', '#', 2, 1),
(7, 'Support', 'Account', '#', 3, 1),
(8, 'Support', 'Contact Us', '#', 4, 1),
(9, 'Legal', 'Terms of Use', '#', 1, 1),
(10, 'Legal', 'Privacy Policy', '#', 2, 1),
(11, 'Legal', 'Cookie Policy', '#', 3, 1),
(12, 'Legal', 'Corporate Information', '#', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `image_url` text NOT NULL,
  `button_text` varchar(100) DEFAULT 'Get Started',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `image_url`, `button_text`, `is_active`) VALUES
(1, 'Unlimited movies, TV shows and more.', 'Watch anywhere. Cancel anytime. Plans start at ₹149.', 'https://image.tmdb.org/t/p/original/rMZonJhnHk0uPqQW524qA7tYmIQ.jpg', 'Get Started', 1);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `release_year` varchar(10) DEFAULT NULL,
  `rating` varchar(10) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL,
  `moods` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `image_url`, `category`, `release_year`, `rating`, `trailer_url`, `moods`, `is_active`) VALUES
(1, 'Pathaan', 'Action spy thriller film', 'https://i.pinimg.com/736x/e3/94/c8/e394c8badb8e6b25f7ff9524ca2f0da6.jpg', 'trending', '2023', '8.2', 'https://www.youtube.com/embed/vqu4z34wENw', NULL, 1),
(2, 'Jawan', 'Mass action entertainer film', 'https://i.pinimg.com/736x/c5/8f/9e/c58f9e717269248657d5aed8be007ac0.jpg', 'trending', '2023', '8.3', 'https://www.youtube.com/embed/COv52Qyctws', NULL, 1),
(3, 'Animal', 'Crime action drama film', 'https://i.pinimg.com/736x/41/7a/da/417adafb005ffd20eb7b86ea3240a46f.jpg', 'trending', '2023', '8.1', 'https://www.youtube.com/embed/8FkLRUJj-o0', NULL, 1),
(4, 'Gadar 2', 'Patriotic action drama sequel', 'https://i.pinimg.com/1200x/9c/90/2c/9c902cc0dd2b7dcd2d972f2daabf307f.jpg', 'trending', '2023', '7.9', 'https://www.youtube.com/embed/mljj92tRwlk', NULL, 1),
(5, '3 Idiots', 'Comedy drama about friendship and college life', 'https://i.pinimg.com/1200x/24/39/45/243945e5d42280015632836c25892f72.jpg', 'trending', '2009', '9.1', 'https://www.youtube.com/embed/xvszmNXdM4w', NULL, 1),
(6, 'Dangal', 'Sports drama based on wrestling', 'https://i.pinimg.com/736x/af/de/37/afde3751c64eab6a269ff37881f6a38e.jpg', 'trending', '2016', '9.0', 'https://www.youtube.com/embed/6lciajX2bVE', NULL, 1),
(7, 'PK', 'Comedy drama with social message', 'https://i.pinimg.com/736x/f8/b2/c4/f8b2c434ccea75a8e018bc882152040d.jpg', 'trending', '2014', '8.8', 'https://www.youtube.com/embed/SOXWc32k4zA', NULL, 1),
(8, 'Bajrangi Bhaijaan', 'Emotional family adventure film', 'https://i.pinimg.com/1200x/45/cb/4a/45cb4a8d3dd51482b503551f42b4005f.jpg', 'trending', '2015', '8.9', 'https://www.youtube.com/embed/4nwAra0mz_Q', NULL, 1),
(9, 'Shershaah', 'War biographical patriotic film', 'https://i.pinimg.com/1200x/82/54/e2/8254e207d4a3ce72162b95463c933fe6.jpg', 'trending', '2021', '8.7', 'https://www.youtube.com/embed/Q0FTXnefVBA', NULL, 1),
(10, 'Kabir Singh', 'Intense romantic drama film', 'https://i.pinimg.com/736x/55/98/62/5598625371f61e6aa90004ba7b06d91e.jpg', 'trending', '2019', '8.0', 'https://www.youtube.com/embed/RiANSSgCuJk', NULL, 1),
(11, 'Drishyam 2', 'Crime suspense thriller film', 'https://i.pinimg.com/1200x/3d/76/3f/3d763f679db4ba93c03d93e6e7b33a56.jpg', 'trending', '2022', '8.4', 'https://www.youtube.com/embed/cxA2y9Tgl7o', NULL, 1),
(12, 'Bhool Bhulaiyaa 2', 'Horror comedy entertainer', 'https://i.pinimg.com/736x/77/27/10/77271039d5ed8772fc752edc3cc7fc90.jpg', 'trending', '2022', '7.8', 'https://www.youtube.com/embed/lzY1SuHFWXo', NULL, 1),
(13, 'War', 'Stylish action thriller film', 'https://i.pinimg.com/736x/3b/5d/17/3b5d17da4f8d207beb8e4db08fbb3a31.jpg', 'trending', '2019', '8.1', 'https://www.youtube.com/embed/tQ0mzXRk-oM', NULL, 1),
(14, 'Tiger Zinda Hai', 'Spy action blockbuster film', 'https://i.pinimg.com/1200x/bb/c3/20/bbc320f050657d38cada60e7068bd5d3.jpg', 'trending', '2017', '8.0', 'https://www.youtube.com/embed/ePO5M5DE01I', NULL, 1),
(15, 'Sultan', 'Sports drama with emotion', 'https://i.pinimg.com/736x/07/6f/7f/076f7f10ceb57941fff3a9ae35a51029.jpg', 'trending', '2016', '8.2', 'https://www.youtube.com/embed/wPxqcq6Byq0', NULL, 1),
(16, 'Chhichhore', 'Friendship and life lesson drama', 'https://i.pinimg.com/1200x/74/c6/58/74c658e3ab3b01dd712ad2b1d50800a6.jpg', 'trending', '2019', '8.6', 'https://www.youtube.com/embed/dxbU8ftQ0Gg', NULL, 1),
(17, 'Yeh Jawaani Hai Deewani', 'Romantic friendship drama film', 'https://i.pinimg.com/736x/a0/74/5c/a0745ced0892a37ee1ab9f30b04bc30e.jpg', 'trending', '2013', '8.5', 'https://www.youtube.com/embed/qAoz9bTQmGw', NULL, 1),
(18, 'Zindagi Na Milegi Dobara', 'Travel friendship drama film', 'https://i.pinimg.com/1200x/92/ee/00/92ee00cad14a11ac7f3f89d52cfbaf0b.jpg', 'trending', '2011', '8.7', 'https://www.youtube.com/embed/FJrpcDgC3zU', NULL, 1),
(19, 'Raazi', 'Spy thriller emotional drama', 'https://i.pinimg.com/1200x/84/c9/50/84c9504c1bdbfc617ff3b07b1e580b90.jpg', 'trending', '2018', '8.3', 'https://www.youtube.com/embed/YjMSttRJrhA', NULL, 1),
(20, 'Andhadhun', 'Dark comedy suspense thriller', 'https://i.pinimg.com/736x/24/c8/a3/24c8a3c30bf8a3ac8b4ba489754bc2bb.jpg', 'trending', '2018', '8.9', 'https://www.youtube.com/embed/2iVYI99VGaw', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `my_list`
--

CREATE TABLE `my_list` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `movie_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `purpose` varchar(50) DEFAULT 'forgot_password',
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `otp_requests`
--

INSERT INTO `otp_requests` (`id`, `email`, `otp`, `purpose`, `expires_at`, `is_used`, `created_at`) VALUES
(1, 'virat121212@gmail.com', '470601', 'forgot_password', '2026-04-01 13:13:45', 1, '2026-04-01 18:33:45'),
(2, 'virat121212@gmail.com', '195619', 'forgot_password', '2026-04-01 13:13:53', 1, '2026-04-01 18:33:53'),
(3, 'virat121212@gmail.com', '562575', 'forgot_password', '2026-04-01 13:13:58', 1, '2026-04-01 18:33:58'),
(4, 'virat121212@gmail.com', '407967', 'forgot_password', '2026-04-01 13:14:02', 1, '2026-04-01 18:34:02'),
(5, 'virat121212@gmail.com', '979746', 'forgot_password', '2026-04-01 13:15:25', 1, '2026-04-01 18:35:25'),
(6, 'virat121212@gmail.com', '669485', 'forgot_password', '2026-04-01 13:20:15', 0, '2026-04-01 18:40:15'),
(7, 'mraval705@rku.ac.in', '484345', 'forgot_password', '2026-04-01 14:59:57', 1, '2026-04-01 20:19:57'),
(8, 'mraval705@rku.ac.in', '125575', 'forgot_password', '2026-04-01 15:08:23', 1, '2026-04-01 20:28:23'),
(9, 'mraval705@rku.ac.in', '188734', 'forgot_password', '2026-04-01 15:14:36', 1, '2026-04-01 20:34:36'),
(10, 'mraval705@rku.ac.in', '517604', 'forgot_password', '2026-04-01 15:15:41', 1, '2026-04-01 20:35:41'),
(11, 'mraval705@rku.ac.in', '901255', 'forgot_password', '2026-04-01 15:22:55', 1, '2026-04-01 20:42:55'),
(12, 'mraval705@rku.ac.in', '692947', 'forgot_password', '2026-04-01 15:25:49', 1, '2026-04-01 20:45:49'),
(13, 'minal6789@gmail.com', '989978', 'forgot_password', '2026-04-01 21:38:42', 1, '2026-04-02 02:58:42'),
(14, 'minal6789@gmail.com', '299662', 'forgot_password', '2026-04-01 21:49:22', 1, '2026-04-02 03:09:22'),
(15, 'minal3434@gmail.com', '800130', 'forgot_password', '2026-04-01 22:01:43', 1, '2026-04-02 03:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `otp`, `created_at`) VALUES
(1, 'mraval705@rku.ac.in', '511414', '2026-03-30 06:40:52'),
(2, 'virat44@gmail.com', '396360', '2026-03-31 01:40:12');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_logs`
--

CREATE TABLE `password_reset_logs` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `old_password` varchar(255) NOT NULL,
  `new_password` varchar(255) NOT NULL,
  `reset_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT 'success'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `password_reset_logs`
--

INSERT INTO `password_reset_logs` (`id`, `email`, `otp`, `old_password`, `new_password`, `reset_at`, `status`) VALUES
(1, 'minal3434@gmail.com', '800130', '$2y$10$Rwbnh75mAyjS5ZCoxSPi3u0y4/Qpd6r2m7DqZ9TM4sMZ4KIu3D.ii', '$2y$10$SQwdPi.bPyZI/t4fPHFrh.h1Dtih2IG.Wt2QFBdyLoyAmtOMkvSJC', '2026-04-02 03:23:19', 'success');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `plan` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `upi_app` varchar(100) DEFAULT NULL,
  `upi_id` varchar(100) DEFAULT NULL,
  `card_name` varchar(100) DEFAULT NULL,
  `card_number` varchar(30) DEFAULT NULL,
  `expiry_date` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `email`, `plan`, `amount`, `payment_method`, `upi_app`, `upi_id`, `card_name`, `card_number`, `expiry_date`) VALUES
(1, 'virat22@gmail.com', 'Basic', 199.00, 'UPI', 'Google Pay', 'virat22@gmail.com', NULL, NULL, NULL),
(2, 'virat221212@gmail.com', 'Basic', 199.00, 'UPI', 'Google Pay', 'virat221212@gmail.com', NULL, NULL, NULL),
(3, 'virat121212@gmail.com', 'Standard', 499.00, 'UPI', 'Google Pay', 'virat121212@gmail.com', NULL, NULL, NULL),
(4, 'virat121212@gmail.com', 'Standard', 499.00, 'UPI', 'Google Pay', 'virat121212@gmail.com', NULL, NULL, NULL),
(5, 'mraval75@rku.ac.in', 'Basic', 199.00, 'UPI', 'Google Pay', 'mraval705@rku.ac.in', NULL, NULL, NULL),
(6, 'minal6789@gmail.com', 'Mobile', 149.00, 'UPI', 'Google Pay', 'minal6789@gmail.com', NULL, NULL, NULL),
(7, 'minal3434@gmail.com', 'Standard', 499.00, 'Card', '', '', NULL, NULL, NULL),
(8, 'minal4545@gmail.com', 'Standard', 499.00, 'Card', '', '', 'minal', 'XXXX XXXX XXXX 1254', '12/12');

-- --------------------------------------------------------

--
-- Table structure for table `search_history`
--

CREATE TABLE `search_history` (
  `id` int NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `search_term` varchar(255) NOT NULL,
  `searched_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `search_history`
--

INSERT INTO `search_history` (`id`, `user_email`, `search_term`, `searched_at`) VALUES
(1, 'minal3434@gmail.com', 'pat', '2026-04-02 03:53:00'),
(2, 'minal3434@gmail.com', 'fergtrg', '2026-04-02 03:53:05'),
(3, 'minal3434@gmail.com', 'anim', '2026-04-02 03:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `plan` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `plan`, `payment_status`, `created_at`) VALUES
(1, 'mraval705@rku.ac.in', 'Minal@22122005', 'Basic', 'Success', '2026-03-24 07:16:14'),
(2, 'viru@gmail.com', '$2y$10$RulVORWPSznu0DsCwWlGF.38/pHtYhSnpHViPRzQSIA6J9WMXjLaS', 'Basic', 'Success', '2026-03-24 07:22:51'),
(3, 'jsdfghj@qwer.sd', '$2y$10$WULE.1XNFAoR0N2k5EmXYeJz3fcKoPiO3q3Dxq/eGtgZYO1qlqGva', 'Premium', 'Success', '2026-03-25 11:56:37'),
(4, '1234@gmail.com', '$2y$10$rer.6P3PZlAnVVylM.h0A.x2oFw5PYzzf7hBpLPGXJ6WkZc1jmMGq', 'Standard', 'Success', '2026-03-26 21:22:01'),
(5, '12345@gmail.com', '$2y$10$krU07KPloWCjjUzA4fsK3OZHG0K1G7YtLFuY3t4hY6ak9.yuQz8Je', 'Mobile', 'Success', '2026-03-26 21:28:17'),
(6, 'minu@gmail.com', '$2y$10$Xw7rK5kVwAVE0etL6LTRkOkIHVOp5rQQlJI5jpGC45hNoS8hVxB4S', 'Mobile', 'Success', '2026-03-26 21:37:59'),
(7, 'minu1@gmail.com', '$2y$10$uzq7QO1tXKv3Ri1b5BfYQ.oIG5PsXZBU5QalKrpHaOHJjwn31.tV2', 'Mobile', 'Success', '2026-03-26 21:43:23'),
(8, 'minu2@gmail.com', '$2y$10$RKP5uCGBJyHlak7Yi7mLxu8DTFu6m6V2B4f0zSdz1t0KUbs.y95oK', 'Mobile', 'Success', '2026-03-26 21:48:44'),
(9, 'minalraval2212@gmail.com', '$2y$10$Y/Q4usCGCxW.hMOHnSYsWercMxfrLTwlylNjcotou.N5Pmu7nb3Ge', 'Mobile', 'Success', '2026-03-30 12:12:04'),
(10, 'minal90@gmail.com', '$2y$10$.D848y42xv5afM6kS6SLMuzHo3ih2qeTWu4lUghxOIOnS3c8fzmte', 'Mobile', 'Success', '2026-03-30 12:29:10'),
(11, 'virat2212@gmail.com', '$2y$10$nzYFItvCeDOXsK3CP8J.n.QzcZE3iIxwvEJV4k6U.FRbud8jISHWS', 'Premium', 'Success', '2026-03-30 13:30:30'),
(12, 'minal345@gmail.com', '$2y$10$S5S5Ouk7hL5M51jp7k2GEueUZCPzswethsZnLHMDcoBKdtOCOzQiW', 'Mobile', 'Success', '2026-03-30 13:51:08'),
(13, 'virat44@gmail.com', '$2y$10$86V4xaI4hQe8ZOKc9FUkZ.K5llaYDVMsMkDk7CtK5nRFfWPhoP1rm', 'Mobile', 'Success', '2026-03-31 01:34:53'),
(14, 'minalraval221@gmail.com', '$2y$10$alS.9ERlzYUVEjGiOATeMOv2RMM3.Hn2vMyVGPBFg/IS6pG/GK17e', 'Premium', 'Pending', '2026-04-01 12:17:38'),
(15, 'minal222@gmail.com', '$2y$10$0rkFCQ3RMhzDjKWExlfL4esVoQpH2uQeVh7yLfgM9J9VtP/w.uqeO', 'Mobile', 'Pending', '2026-04-01 12:18:19'),
(16, 'virat22@gmail.com', 'Minal9090', 'Basic', 'Success', '2026-04-01 12:34:44'),
(17, 'virat221212@gmail.com', '$2y$10$2XQ1hioNFSSgfD93ULoyn.1tE1txu9V.IxdmOxh4gZF67VwLjO7Wu', 'Basic', 'Success', '2026-04-01 12:40:05'),
(18, 'virat121212@gmail.com', '$2y$10$09RXagzQnDvLFxFXcvtUReOsajiZZ2dJsZGH/cLQFq4yVH/XlRhPe', 'Standard', 'Success', '2026-04-01 12:58:13'),
(19, 'mraval75@rku.ac.in', '$2y$10$blA0eGlgvtBjcFjKDQ.8c.s/07jihXnKrcNxsGPRWkAtfpavuhdtC', 'Basic', 'Success', '2026-04-01 21:22:54'),
(20, 'minal6789@gmail.com', '$2y$10$3TP2Vrl97g5Cfz2O8xF3H.hu9mx/QRDC3CmJexDQUVWXb.KeZOX7a', 'Mobile', 'Success', '2026-04-01 21:27:19'),
(21, 'minal3434@gmail.com', '$2y$10$SQwdPi.bPyZI/t4fPHFrh.h1Dtih2IG.Wt2QFBdyLoyAmtOMkvSJC', 'Standard', 'Success', '2026-04-01 21:51:33'),
(22, 'minal4545@gmail.com', '$2y$10$.AWhEiHZAcE0x03zDuEAN.DzTbGHwOZPiX/oFsxPhrhjTwcTX/BKS', 'Standard', 'Success', '2026-04-01 22:04:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_links`
--
ALTER TABLE `footer_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_list`
--
ALTER TABLE `my_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_logs`
--
ALTER TABLE `password_reset_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_history`
--
ALTER TABLE `search_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `footer_links`
--
ALTER TABLE `footer_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `my_list`
--
ALTER TABLE `my_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_reset_logs`
--
ALTER TABLE `password_reset_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `search_history`
--
ALTER TABLE `search_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
