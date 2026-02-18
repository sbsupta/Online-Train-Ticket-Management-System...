-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql103.infinityfree.com
-- Generation Time: Feb 18, 2026 at 09:42 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39667088_if0_39667088_ottms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(2, 'SB\'s Team', 'sbsupta1245@gmail.com', '$2y$10$x.X5TxMp.gFTzd/vvugeLOOdB1w7GN1/2uBdpyAzbiddfFKfEik8S'),
(4, 'Tonni', 'tonni@gmail.com', '$2y$10$04N3Wm4HQI6fTgD8nEPlS.V98VLRmlo55h86QafFtBQdYEBEcIe8O');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `created_at`) VALUES
(6, 'AJKE JAITE DIMU NAH....', 'DHOR DIYA JABAN GA...', '2025-08-09 15:10:29'),
(7, 'Dipjol --', 'Aho Vatija Aho...', '2025-08-09 15:16:08'),
(10, 'Nachiketa Chakraborty ‧ in 2009-', 'তুমি আসবে বলেই...', '2025-08-11 16:00:22'),
(11, 'Train Dealy', 'Dear Passengers,\r\nThe Padma Express will be delayed by 1 hour due to operational reasons. We regret the inconvenience and appreciate your patience.', '2025-09-02 16:55:50'),
(12, 'Off Day of Rupsha Express', 'Dear Passengers,\r\nThe Rupsha Express will remain off on its scheduled off day. Please plan your journey accordingly.', '2025-09-02 16:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `train_id` int(11) DEFAULT NULL,
  `journey_date` date DEFAULT NULL,
  `seats_booked` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `payment_date` datetime DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `seat_number` varchar(10) DEFAULT NULL,
  `passenger_name` varchar(100) DEFAULT NULL,
  `passenger_age` int(11) DEFAULT NULL,
  `passenger_gender` varchar(10) DEFAULT NULL,
  `travel_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `train_id`, `journey_date`, `seats_booked`, `payment_status`, `payment_date`, `class_id`, `seat_number`, `passenger_name`, `passenger_age`, `passenger_gender`, `travel_date`) VALUES
(2, 1, 5, '2025-05-17', '1', 'paid', '2025-05-16 09:26:10', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 5, '2025-05-20', '1', 'paid', '2025-05-16 21:05:01', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 1, 11, '2025-05-29', '2', 'paid', '2025-05-19 14:36:53', 7, NULL, NULL, NULL, NULL, NULL),
(19, 1, 1, '2025-05-28', '1', 'paid', '2025-05-21 23:08:25', 3, NULL, NULL, NULL, NULL, NULL),
(21, 2, 13, '2025-05-23', '5', 'pending', NULL, 16, NULL, NULL, NULL, NULL, NULL),
(22, 2, 14, '2024-06-20', '1', 'paid', '2025-05-22 16:38:14', 17, NULL, NULL, NULL, NULL, NULL),
(24, 5, 1, '2025-07-24', '2', 'paid', '2025-07-05 13:11:53', 3, NULL, NULL, NULL, NULL, NULL),
(45, 8, 2, '2025-08-11', 'B1,B2', 'paid', '2025-08-09 10:15:05', 22, NULL, NULL, NULL, NULL, NULL),
(46, 2, 15, '2025-08-14', 'A1,A2,A3', 'paid', '2025-08-11 08:43:29', 18, NULL, NULL, NULL, NULL, NULL),
(47, 9, 3, '2025-08-12', 'A1,A2,A3', 'paid', '2025-08-11 09:10:26', 28, NULL, NULL, NULL, NULL, NULL),
(49, 6, 42, '2025-08-12', 'B1,B2', 'paid', '2025-08-11 09:53:01', 104, NULL, NULL, NULL, NULL, NULL),
(50, 6, 27, '2025-08-12', 'B1,B2', 'paid', '2025-08-11 09:55:15', 58, NULL, NULL, NULL, NULL, NULL),
(52, 2, 2, '2025-08-14', 'B1,B2', 'paid', '2025-08-12 01:52:52', 22, NULL, NULL, NULL, NULL, NULL),
(57, 11, 18, '2025-08-13', 'A1,A2', 'paid', '2025-08-12 09:43:12', 30, NULL, NULL, NULL, NULL, NULL),
(58, 11, 18, '2025-08-13', 'A1,A2', 'paid', '2025-08-12 09:43:45', 30, NULL, NULL, NULL, NULL, NULL),
(59, 7, 18, '2025-08-14', 'B1,B2', 'paid', '2025-08-12 22:41:47', 30, NULL, NULL, NULL, NULL, NULL),
(61, 13, 3, '2025-08-31', 'B1', 'paid', '2025-08-28 22:42:09', 26, NULL, NULL, NULL, NULL, NULL),
(62, 2, 18, '2025-09-10', 'A1,B3', 'paid', '2025-09-02 07:15:05', 30, NULL, NULL, NULL, NULL, NULL),
(63, 12, 18, '2025-09-05', 'C1,C2,C3,C4', 'paid', '2025-09-02 09:41:47', 30, NULL, NULL, NULL, NULL, NULL),
(65, 2, 32, '2025-09-04', 'B1,B2', 'paid', '2025-09-02 21:31:39', 72, NULL, NULL, NULL, NULL, NULL),
(66, 2, 23, '2025-09-12', 'A1,A2', 'paid', '2025-09-02 22:11:33', 45, NULL, NULL, NULL, NULL, NULL),
(67, 2, 15, '2025-12-11', 'A2,B2', 'paid', '2025-12-03 05:28:09', 19, NULL, NULL, NULL, NULL, NULL),
(68, 14, 23, '2026-01-21', 'A1', 'paid', '2026-01-13 23:52:07', 45, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `feedback`, `submitted_at`, `rating`) VALUES
(8, 2, 'Very Good Service...', '2025-05-22 09:07:14', 0),
(9, 2, 'qwertyuio\r\n', '2025-08-09 13:48:15', 2),
(10, 2, 'hare krishna...', '2025-08-09 14:37:07', 4),
(11, 6, 'Not bad ', '2025-08-09 15:40:15', 3),
(13, 8, 'Service is Good.', '2025-08-09 17:07:09', 4),
(14, 9, 'Excellent..', '2025-08-11 16:11:51', 5),
(15, 9, 'Excellent and thank you ', '2025-08-11 16:12:15', 5),
(16, 9, 'Excellent and thank you ', '2025-08-11 16:12:33', 5),
(17, 9, 'Excellent and thank you ', '2025-08-11 16:12:45', 5),
(18, 2, 'qwertyuikm', '2025-08-11 16:13:00', 3),
(19, 11, 'aqwsedrftgyhujn bvfcdxsderftgyhjmk\r\n', '2025-08-12 16:40:42', 4),
(20, 2, 'Kora server', '2025-09-02 14:15:41', 5),
(21, 2, 'Good Service.', '2025-09-02 16:43:49', 5),
(22, 2, 'Sir Marks Ektu Baraya Diyen...', '2025-09-03 04:25:04', 5),
(23, 2, ' Slide 1: Methodology\r\n\r\n“Here is the methodology we followed for developing our project...\r\nWe used the Agile process model because it allows quick releases and continuous feedback...\r\nOur approach was simple: we started with user stories, then moved to design, development, testing, and refinement...\r\nFor tools, we worked with PHP and MySQL for the backend, HTML, CSS, and JavaScript for the frontend, and FPDF for ticket generation...\r\nThis helped us build fast, test often, and improve continuously...”\r\n\r\n🔹 Slide 2: Architecture Diagram\r\n\r\n“This is the architecture diagram of our system...\r\nAt the entry point, we use CDN edge services to deliver content faster...\r\nRequests are handled by two application servers which manage API routing and business logic...\r\nThen we have different services like authentication, payment gateway, live chat, feedback, and the PDF/QR generator...\r\nFinally, everything is stored in the data layer, where we maintain a primary database with replication and file storage for tickets and QR codes...\r\nBackups and asynchronous updates make the system more reliable...”\r\n\r\n🔹 Slide 3: UML Class Diagram\r\n\r\n“This slide shows the UML Class Diagram of our system...\r\nThe User class contains login details and can make a Booking...\r\nEach Booking is connected to a Train and a Seat, with details like booking date and payment status...\r\nThe Admin class manages the system and can post Announcements...\r\nWe also track LoginDetails such as login and logout times...\r\nAnd finally, we have a Feedback class that records seat condition and user feedback...\r\nThis diagram explains the main entities and how they interact within our project...”', '2025-09-03 04:28:24', 5),
(24, 2, 'good ', '2025-09-03 05:13:04', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `seat_number` int(11) DEFAULT NULL,
  `journey_date` date DEFAULT NULL,
  `from_station` varchar(100) DEFAULT NULL,
  `to_station` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `class_id`, `seat_number`, `journey_date`, `from_station`, `to_station`) VALUES
(1, 11, 26, 0, '2025-08-13', 'Noakhali', 'Dhaka'),
(2, 11, 26, 0, '2025-08-13', 'Noakhali', 'Dhaka'),
(3, 11, 26, 0, '2025-08-13', 'Noakhali', 'Dhaka'),
(4, 11, 26, 0, '2025-08-13', 'Noakhali', 'Dhaka'),
(5, 11, 30, 0, '2025-08-13', 'Dhaka', 'Rajshahi'),
(6, 11, 30, 0, '2025-08-13', 'Dhaka', 'Rajshahi'),
(7, 11, 30, 0, '2025-08-13', 'Dhaka', 'Rajshahi'),
(8, 11, 30, 0, '2025-08-13', 'Dhaka', 'Rajshahi'),
(9, 7, 30, 0, '2025-08-14', 'Dhaka', 'Rajshahi'),
(10, 7, 30, 0, '2025-08-14', 'Dhaka', 'Rajshahi'),
(11, 2, 27, 0, '2025-08-28', 'Noakhali', 'Dhaka'),
(12, 13, 26, 0, '2025-08-31', 'Noakhali', 'Dhaka'),
(13, 2, 30, 0, '2025-09-10', 'Dhaka', 'Rajshahi'),
(14, 2, 30, 0, '2025-09-10', 'Dhaka', 'Rajshahi'),
(15, 12, 30, 0, '2025-09-05', 'Dhaka', 'Rajshahi'),
(16, 12, 30, 0, '2025-09-05', 'Dhaka', 'Rajshahi'),
(17, 12, 30, 0, '2025-09-05', 'Dhaka', 'Rajshahi'),
(18, 12, 30, 0, '2025-09-05', 'Dhaka', 'Rajshahi'),
(19, 2, 31, 0, '2025-09-05', 'Dhaka', 'Rajshahi'),
(20, 2, 31, 0, '2025-09-05', 'Dhaka', 'Rajshahi'),
(21, 2, 31, 0, '2025-09-05', 'Dhaka', 'Rajshahi'),
(22, 2, 31, 0, '2025-09-05', 'Dhaka', 'Rajshahi'),
(23, 2, 72, 0, '2025-09-04', 'Rajshahi', 'Dhaka'),
(24, 2, 72, 0, '2025-09-04', 'Rajshahi', 'Dhaka'),
(25, 2, 45, 0, '2025-09-12', 'Dhaka', 'Chittagong'),
(26, 2, 45, 0, '2025-09-12', 'Dhaka', 'Chittagong'),
(27, 2, 19, 0, '2025-12-11', 'Rajshahi', 'Chittagong'),
(28, 2, 19, 0, '2025-12-11', 'Rajshahi', 'Chittagong'),
(29, 14, 45, 0, '2026-01-21', 'Dhaka', 'Chittagong');

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE `trains` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `from_station` varchar(100) DEFAULT NULL,
  `to_station` varchar(100) DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `seats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trains`
--

INSERT INTO `trains` (`id`, `name`, `from_station`, `to_station`, `departure_time`, `seats`) VALUES
(1, 'Intercity Express', 'Dhaka', 'Chittagong', '08:00:00', 100),
(2, 'Sundarban Express', 'Dhaka', 'Khulna', '09:30:00', 120),
(3, 'Upakul Express', 'Noakhali', 'Dhaka', '06:00:00', 90),
(4, 'Chattala Express', 'Chittagong', 'Dhaka', '18:00:00', 110),
(7, 'Drutojan Express', 'Dhaka', 'Noakhali', '00:01:00', 69),
(15, 'Jonaki Express', 'Rajshahi', 'Chittagong', '07:05:00', 100),
(16, 'Lal_mona Express', 'Noakhali', 'Dhaka', '09:10:00', 100),
(17, 'Mrittika Express', 'Dhaka', 'Faridpur', '08:40:00', 160),
(18, 'Padma Express', 'Dhaka', 'Rajshahi', '07:00:00', 200),
(19, 'Mohanagar Express', 'Dhaka', 'Sitakunda', '16:30:00', 180),
(20, 'Kapotaksha Express', 'Khulna', 'Rajshahi', '06:15:00', 150),
(21, 'Tista Express', 'Dhaka', 'Dewanganj', '07:30:00', 160),
(22, 'Parabat Express', 'Dhaka', 'Sylhet', '06:40:00', 170),
(23, 'Subarna Express', 'Dhaka', 'Chittagong', '07:00:00', 180),
(24, 'Lal_moni Express', 'Dhaka', 'Lalmonirhat', '21:45:00', 200),
(25, 'Maitree Express', 'Dhaka', 'Kolkata', '08:15:00', 160),
(26, 'Ekota Express', 'Dhaka', 'Dinajpur', '10:00:00', 190),
(27, 'Bijoya Express', 'Dhaka', 'Chittagong', '23:00:00', 175),
(28, 'Dhumketu Express', 'Dhaka', 'Rajshahi', '06:00:00', 185),
(29, 'Sonar Bangla Express', 'Dhaka', 'Chittagong', '17:00:00', 200),
(30, 'Mohua Express', 'Dhaka', 'Mymensingh', '15:20:00', 150),
(31, 'Turna Express', 'Dhaka', 'Chittagong', '23:30:00', 180),
(32, 'Modhumoti Express', 'Rajshahi', 'Dhaka', '07:45:00', 170),
(33, 'Paharika Express', 'Chittagong', 'Sylhet', '09:00:00', 160),
(34, 'Udayan Express', 'Chittagong', 'Sylhet', '21:50:00', 165),
(35, 'Jamuna Express', 'Dhaka', 'Bangabandhu Bridge West', '16:45:00', 150),
(36, 'Chitra Express', 'Dhaka', 'Khulna', '19:00:00', 175),
(37, 'Mahananda Express', 'Chapainawabganj', 'Dhaka', '04:00:00', 140),
(38, 'Barendra Express', 'Rajshahi', 'Chilahati', '15:00:00', 150),
(39, 'Rupsha Express', 'Khulna', 'Chilahati', '07:30:00', 200),
(40, 'Simanta Express', 'Khulna', 'Chilahati', '21:00:00', 200),
(41, 'Titas Commuter', 'Akhaura', 'Dhaka', '05:45:00', 120),
(42, 'Mohamaya Express', 'Dhaka', 'Chittagong', '14:20:00', 185),
(43, 'Shonar Tori Express', 'Dhaka', 'Khulna', '06:10:00', 190);

-- --------------------------------------------------------

--
-- Table structure for table `train_classes`
--

CREATE TABLE `train_classes` (
  `id` int(11) NOT NULL,
  `train_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `fare` decimal(10,2) NOT NULL,
  `seats` int(11) NOT NULL DEFAULT 0,
  `seat_rows` int(11) NOT NULL DEFAULT 0,
  `seat_columns` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `train_classes`
--

INSERT INTO `train_classes` (`id`, `train_id`, `class_name`, `fare`, `seats`, `seat_rows`, `seat_columns`) VALUES
(1, 1, 'General', '300.00', 0, 0, 0),
(2, 1, 'Sleeper', '500.00', 0, 0, 0),
(3, 1, 'AC', '800.00', 0, 0, 0),
(18, 15, 'AC', '500.00', 0, 10, 10),
(19, 15, 'Sleeper', '800.00', 0, 4, 5),
(20, 15, 'General', '400.00', 0, 30, 10),
(21, 2, 'AC', '500.00', 0, 4, 2),
(22, 2, 'Sleeper', '1000.00', 0, 4, 2),
(23, 16, 'Sleeper', '500.00', 0, 4, 3),
(24, 16, 'AC', '600.00', 0, 4, 5),
(25, 16, 'General', '300.00', 0, 4, 17),
(26, 3, 'AC', '500.00', 0, 4, 6),
(27, 3, 'Sleeper', '800.00', 0, 4, 5),
(28, 3, 'General', '400.00', 0, 4, 11),
(29, 2, 'General', '300.00', 0, 4, 5),
(30, 18, 'AC', '900.00', 40, 10, 4),
(31, 18, 'Sleeper', '1200.00', 60, 15, 4),
(32, 18, 'General', '500.00', 100, 20, 5),
(33, 19, 'AC', '800.00', 36, 9, 4),
(34, 19, 'Sleeper', '1000.00', 54, 9, 6),
(35, 19, 'General', '400.00', 90, 18, 5),
(36, 20, 'AC', '950.00', 30, 10, 3),
(37, 20, 'Sleeper', '1500.00', 50, 10, 5),
(38, 20, 'General', '600.00', 70, 14, 5),
(39, 21, 'AC', '500.00', 20, 5, 4),
(40, 21, 'Sleeper', '800.00', 50, 10, 5),
(41, 21, 'General', '300.00', 90, 18, 5),
(42, 22, 'AC', '700.00', 28, 7, 4),
(43, 22, 'Sleeper', '900.00', 56, 14, 4),
(44, 22, 'General', '350.00', 86, 18, 5),
(45, 23, 'AC', '950.00', 40, 10, 4),
(46, 23, 'Sleeper', '1300.00', 60, 15, 4),
(47, 23, 'General', '500.00', 80, 16, 5),
(48, 24, 'AC', '900.00', 50, 10, 5),
(49, 24, 'Sleeper', '1400.00', 70, 14, 5),
(50, 24, 'General', '400.00', 80, 20, 4),
(51, 25, 'AC', '1000.00', 40, 10, 4),
(52, 25, 'Sleeper', '1500.00', 50, 10, 5),
(53, 25, 'General', '600.00', 70, 14, 5),
(54, 26, 'AC', '800.00', 36, 9, 4),
(55, 26, 'Sleeper', '1200.00', 54, 9, 6),
(56, 26, 'General', '350.00', 100, 20, 5),
(57, 27, 'AC', '750.00', 28, 7, 4),
(58, 27, 'Sleeper', '900.00', 56, 14, 4),
(59, 27, 'General', '350.00', 91, 13, 7),
(60, 28, 'AC', '920.00', 40, 10, 4),
(61, 28, 'Sleeper', '1250.00', 60, 15, 4),
(62, 28, 'General', '480.00', 85, 17, 5),
(63, 29, 'AC', '1000.00', 50, 10, 5),
(64, 29, 'Sleeper', '1500.00', 70, 14, 5),
(65, 29, 'General', '600.00', 80, 20, 4),
(66, 30, 'AC', '600.00', 24, 6, 4),
(67, 30, 'Sleeper', '850.00', 48, 8, 6),
(68, 30, 'General', '300.00', 78, 13, 6),
(69, 31, 'AC', '880.00', 36, 9, 4),
(70, 31, 'Sleeper', '1150.00', 54, 9, 6),
(71, 31, 'General', '400.00', 90, 18, 5),
(72, 32, 'AC', '850.00', 36, 9, 4),
(73, 32, 'Sleeper', '1200.00', 54, 9, 6),
(74, 32, 'General', '400.00', 80, 20, 4),
(75, 33, 'AC', '780.00', 32, 8, 4),
(76, 33, 'Sleeper', '1050.00', 48, 8, 6),
(77, 33, 'General', '350.00', 80, 16, 5),
(78, 34, 'AC', '820.00', 36, 9, 4),
(79, 34, 'Sleeper', '1100.00', 54, 9, 6),
(80, 34, 'General', '380.00', 75, 15, 5),
(81, 35, 'AC', '700.00', 30, 10, 3),
(82, 35, 'Sleeper', '950.00', 45, 9, 5),
(83, 35, 'General', '320.00', 75, 15, 5),
(84, 36, 'AC', '870.00', 35, 7, 5),
(85, 36, 'Sleeper', '1250.00', 55, 11, 5),
(86, 36, 'General', '500.00', 85, 17, 5),
(87, 37, 'AC', '650.00', 24, 6, 4),
(88, 37, 'Sleeper', '900.00', 46, 8, 6),
(89, 37, 'General', '300.00', 70, 14, 5),
(90, 38, 'AC', '700.00', 28, 7, 4),
(91, 38, 'Sleeper', '950.00', 50, 10, 5),
(92, 38, 'General', '320.00', 72, 12, 6),
(93, 39, 'AC', '950.00', 50, 10, 5),
(94, 39, 'Sleeper', '1400.00', 70, 14, 5),
(95, 39, 'General', '500.00', 80, 20, 4),
(96, 40, 'AC', '980.00', 50, 10, 5),
(97, 40, 'Sleeper', '1450.00', 70, 14, 5),
(98, 40, 'General', '550.00', 80, 20, 4),
(99, 41, 'AC', '500.00', 20, 5, 4),
(100, 41, 'Sleeper', '800.00', 40, 8, 5),
(101, 41, 'General', '300.00', 60, 12, 5),
(102, 42, 'AC', '950.00', 40, 10, 4),
(103, 42, 'Sleeper', '1300.00', 60, 15, 4),
(104, 42, 'General', '500.00', 85, 17, 5),
(105, 28, 'Hshshs', '500.00', 0, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`) VALUES
(1, 'Tonni', 'tonni@gmail.com', '$2y$10$RvxUZCf8AfJnSS7h2DdlF.7usWKE6cRnkUrJOq30bFXT5XFhYwEc.', NULL),
(2, 'SB_Supta', 'sbsupta1245@gmail.com', '$2y$10$rs0Nqtvuh2VJz4EI1E8.m.IOuYifaX8EWoUmVbHM8g/4KLRDuqzxe', NULL),
(3, 'Sabbir Sir', 'Sabbir@gmail.com', '$2y$10$3VJ77UMqbtlnRK.istL3F.OGRzAGrR1asSlmoeBwdFQ5/gvgzuBtq', NULL),
(6, 'Bijoya banik', 'bbanik182@gmail.com', '$2y$10$iKfjotrkx.9ttDXy3iS9ie4w7OlBOOg3JE0mrUaL/Zu5QU/l0.wCi', NULL),
(7, 'Mahedi Ether', 'mahediether10@gmail.com', '$2y$10$6yXVllTjrPEez2bNEyJGCe4RWKFbvZq1ydzmbHxtDkbY6YliQY9v6', NULL),
(8, 'MD. Mehedi Hasan', 'mehedi.hassan281201@gmail.com', '$2y$10$z1q.1YaHPLliPfxXygpcdOumvwJNIP6N3IJTpEkhbIOlpxp2.n96u', NULL),
(9, 'Anwesha Banik', 'anweshabanik99@gmail.com', '$2y$10$X2f3hec8ti6T9ODosJP2s.kOP03EUMC1rnnpiT5lqVqmbyObNaPnq', NULL),
(11, 'RAHUL PAUL APU', 'rahul@gmail.com', '$2y$10$Wvn9Z3lxjR8J.8qsMpWEEuKxv41Bj09NezdWJ8GcQ1Un81OR/qcri', '01715183244'),
(12, 'MD. Mehedi Hasan', 'mehedi.hassan281299@gmail.com', '$2y$10$/BaHecaQLOcyA4lOr/Bt9.JetVNNDEK83WOmgQYmUw3M5VJdhV7i6', '01757650845'),
(13, 'Binoy', 'banik@gmail.com', '$2y$10$rmeScDSOO6EgOSDEOvPwb.Y4ZvN91i6Pvit7YwTEkRnZAz46xfFcG', '01712457836'),
(14, 'Hiya Saha', 'orpritasaha152@gmail.com', '$2y$10$5txqipHCNSxVQLofood5tu2As.pI/FSj9Jw9DMNgm0B2OmDyNMmDy', '01626384007');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trains`
--
ALTER TABLE `trains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `train_classes`
--
ALTER TABLE `train_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `train_id` (`train_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `trains`
--
ALTER TABLE `trains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `train_classes`
--
ALTER TABLE `train_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `train_classes`
--
ALTER TABLE `train_classes`
  ADD CONSTRAINT `train_classes_ibfk_1` FOREIGN KEY (`train_id`) REFERENCES `trains` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
