-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 04, 2025 at 10:15 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spa_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `service_id` int NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `staff_id` (`staff_id`),
  KEY `service_id` (`service_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `client_id`, `staff_id`, `service_id`, `appointment_date`, `appointment_time`, `status`, `notes`, `created_at`) VALUES
(24, 22, 10, 50, '2025-07-31', '19:09:00', 'Completed', '', '2025-07-31 16:09:39'),
(22, 20, 10, 23, '2025-07-31', '15:38:00', 'Completed', '', '2025-07-31 12:38:53'),
(23, 21, 10, 22, '2025-07-31', '17:06:00', 'Completed', '', '2025-07-31 14:06:44');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_services`
--

DROP TABLE IF EXISTS `appointment_services`;
CREATE TABLE IF NOT EXISTS `appointment_services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appointment_id` int NOT NULL,
  `service_id` int NOT NULL,
  `staff_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `service_id` (`service_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT 'Female',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
CREATE TABLE IF NOT EXISTS `commissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  KEY `service_id` (`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appointment_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash','Mpesa') NOT NULL,
  `transaction_code` varchar(50) DEFAULT NULL,
  `paid_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `appointment_id`, `amount`, `payment_method`, `transaction_code`, `paid_at`, `notes`) VALUES
(17, 24, 5000.00, 'Cash', '', '2025-07-31 19:10:55', ''),
(16, 23, 8000.00, 'Cash', '', '2025-07-31 17:07:08', '');

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

DROP TABLE IF EXISTS `payrolls`;
CREATE TABLE IF NOT EXISTS `payrolls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` int NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_appointments` int DEFAULT '0',
  `total_sales` decimal(10,2) DEFAULT '0.00',
  `commission_rate` decimal(5,2) DEFAULT NULL,
  `total_commission` decimal(10,2) DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `earnings` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `staff_id`, `start_date`, `end_date`, `total_appointments`, `total_sales`, `commission_rate`, `total_commission`, `generated_at`, `notes`, `status`, `earnings`) VALUES
(61, 11, '2025-08-03', '2025-08-03', 0, 0.00, 10.00, 0.00, '2025-08-02 22:59:17', 'Includes tips: KES 0.00', 'unpaid', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int DEFAULT '0',
  `supplier_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_products_supplier` (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `quantity`, `supplier_id`, `created_at`) VALUES
(9, 'wig', 'for plaiting', 1500.00, 293, NULL, '2025-08-01 13:00:22');

-- --------------------------------------------------------

--
-- Table structure for table `product_sales`
--

DROP TABLE IF EXISTS `product_sales`;
CREATE TABLE IF NOT EXISTS `product_sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('Cash','Mpesa') DEFAULT NULL,
  `transaction_code` varchar(50) DEFAULT NULL,
  `sold_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_sales`
--

INSERT INTO `product_sales` (`id`, `product_id`, `quantity`, `total`, `payment_method`, `transaction_code`, `sold_at`) VALUES
(4, 9, 1, 1500.00, '', '', '2025-08-01 13:03:31'),
(3, 9, 1, 1500.00, '', '', '2025-08-01 13:02:04'),
(5, 9, 1, 1500.00, '', '', '2025-08-01 13:03:59'),
(6, 9, 1, 1500.00, '', '', '2025-08-01 13:04:01'),
(7, 9, 1, 1500.00, '', '', '2025-08-01 13:05:31'),
(8, 9, 1, 1500.00, '', '', '2025-08-01 13:09:38'),
(9, 9, 1, 1500.00, '', '', '2025-08-01 13:12:03');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category_id` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `category_id`, `price`, `duration_minutes`, `description`, `created_at`) VALUES
(13, 'Anti-Acne Facial', 1, 65.00, 75, 'Helps reduce acne, inflammation, and breakouts.', '2025-07-29 17:45:06'),
(12, 'Deep Cleansing Facial', 1, 60.00, 70, 'Targets clogged pores and oily skin for a clean, smooth face.', '2025-07-29 17:45:06'),
(11, 'Hydrating Facial', 1, 55.00, 60, 'Restores skin moisture balance for a radiant look.', '2025-07-29 17:45:06'),
(9, 'braiding', 6, 300.00, NULL, 'plating of hair', '2025-07-29 13:30:14'),
(10, 'Classic European Facial', 1, 50.00, 60, 'A traditional cleansing facial to refresh and hydrate the skin.', '2025-07-29 17:45:06'),
(14, 'Brightening Facial', 1, 70.00, 60, 'Evens skin tone and restores a natural glow.', '2025-07-29 17:45:06'),
(15, 'Oxygen Facial', 1, 80.00, 60, 'Boosts cell regeneration and hydration with oxygen infusion.', '2025-07-29 17:45:06'),
(16, 'Gold Facial', 1, 90.00, 75, 'Luxury facial treatment with gold particles for glowing skin.', '2025-07-29 17:45:06'),
(17, 'LED Light Therapy Facial', 1, 85.00, 45, 'Uses LED light to target acne, wrinkles, and pigmentation.', '2025-07-29 17:45:06'),
(18, 'Collagen Rejuvenation Facial', 1, 95.00, 80, 'Promotes collagen production for firm, youthful skin.', '2025-07-29 17:45:06'),
(19, 'Swedish Massage', 2, 70.00, 60, 'Gentle massage with long strokes to promote relaxation.', '2025-07-29 17:45:06'),
(20, 'Deep Tissue Massage', 2, 80.00, 75, 'Focuses on deeper layers to relieve muscle tension.', '2025-07-29 17:45:06'),
(21, 'Hot Stone Massage', 2, 85.00, 80, 'Uses heated stones to release tight muscles and stress.', '2025-07-29 17:45:06'),
(22, 'Aromatherapy Massage', 2, 75.00, 60, 'Uses essential oils to enhance relaxation and mood.', '2025-07-29 17:45:06'),
(23, 'Thai Massage', 2, 90.00, 90, 'Traditional Thai stretching and pressure therapy.', '2025-07-29 17:45:06'),
(24, 'Reflexology Foot Massage', 2, 50.00, 45, 'Stimulates reflex points on the feet for overall wellness.', '2025-07-29 17:45:06'),
(25, 'Sports Massage', 2, 85.00, 60, 'Ideal for athletes to relieve muscle soreness and injuries.', '2025-07-29 17:45:06'),
(26, 'Prenatal Massage', 2, 80.00, 60, 'Gentle massage tailored for pregnant women.', '2025-07-29 17:45:06'),
(27, 'Couples Massage', 2, 150.00, 90, 'Relaxing massage for two in a shared private room.', '2025-07-29 17:45:06'),
(28, 'Full Body Scrub (Salt or Sugar)', 3, 60.00, 50, 'Removes dead skin cells leaving skin smooth and fresh.', '2025-07-29 17:45:06'),
(29, 'Coffee Scrub', 3, 65.00, 50, 'Invigorating scrub that boosts circulation and skin glow.', '2025-07-29 17:45:06'),
(30, 'Chocolate Body Wrap', 3, 80.00, 70, 'Detoxifying and nourishing chocolate-based body wrap.', '2025-07-29 17:45:06'),
(31, 'Mud Wrap', 3, 75.00, 60, 'Mineral-rich mud draws out impurities and firms skin.', '2025-07-29 17:45:06'),
(32, 'Herbal Detox Wrap', 3, 85.00, 70, 'Uses natural herbs for detoxification and skin renewal.', '2025-07-29 17:45:06'),
(33, 'Seaweed Body Wrap', 3, 90.00, 70, 'Rich in minerals for skin hydration and detox.', '2025-07-29 17:45:06'),
(34, 'Slimming Body Treatment', 3, 95.00, 80, 'Targets cellulite and excess water retention for slimming effect.', '2025-07-29 17:45:06'),
(35, 'Full Body Waxing', 4, 120.00, 90, 'Complete body waxing for smooth, hair-free skin.', '2025-07-29 17:45:06'),
(36, 'Bikini/Brazilian Wax', 4, 60.00, 45, 'Hair removal for intimate areas.', '2025-07-29 17:45:06'),
(37, 'Underarm Wax', 4, 20.00, 20, 'Quick and clean underarm hair removal.', '2025-07-29 17:45:06'),
(38, 'Eyebrow Wax', 4, 15.00, 15, 'Shaping and grooming eyebrows using wax.', '2025-07-29 17:45:06'),
(39, 'Leg Wax (Half)', 4, 30.00, 30, 'Removes unwanted hair from lower or upper legs.', '2025-07-29 17:45:06'),
(40, 'Leg Wax (Full)', 4, 50.00, 50, 'Complete hair removal from both legs.', '2025-07-29 17:45:06'),
(41, 'Threading (Eyebrows, Upper Lip, Chin)', 4, 25.00, 30, 'Precise hair removal using thread technique.', '2025-07-29 17:45:06'),
(42, 'Laser Hair Removal Session', 4, 100.00, 40, 'Long-term hair reduction using laser technology.', '2025-07-29 17:45:06'),
(43, 'Basic Manicure', 5, 20.00, 30, 'Trimming, shaping, and basic nail care.', '2025-07-29 17:45:06'),
(44, 'Spa Manicure', 5, 30.00, 45, 'Includes exfoliation and hand massage.', '2025-07-29 17:45:06'),
(45, 'French Manicure', 5, 35.00, 50, 'Classic manicure with French-style nail tips.', '2025-07-29 17:45:06'),
(46, 'Gel/UV Manicure', 5, 40.00, 50, 'Long-lasting manicure with UV gel polish.', '2025-07-29 17:45:06'),
(47, 'Classic Pedicure', 5, 25.00, 40, 'Basic nail and foot care.', '2025-07-29 17:45:06'),
(48, 'Spa Pedicure', 5, 35.00, 50, 'Relaxing pedicure with scrubs and massage.', '2025-07-29 17:45:06'),
(49, 'Paraffin Wax Hand & Foot Treatment', 5, 30.00, 40, 'Softens skin and soothes tired hands/feet.', '2025-07-29 17:45:06'),
(50, 'Nail Art Design', 5, 20.00, 30, 'Customized nail decoration and styling.', '2025-07-29 17:45:06'),
(51, 'Women’s Haircut', 6, 25.00, 40, 'Professional haircut tailored to your style.', '2025-07-29 17:45:06'),
(52, 'Men’s Haircut', 6, 15.00, 30, 'Quick and clean haircut for men.', '2025-07-29 17:45:06'),
(53, 'Kids Haircut', 6, 10.00, 20, 'Gentle haircut service for children.', '2025-07-29 17:45:06'),
(54, 'Blow Dry & Styling', 6, 20.00, 30, 'Hair drying and styling service.', '2025-07-29 17:45:06'),
(55, 'Full Hair Coloring', 6, 70.00, 90, 'Complete hair color change with professional dyes.', '2025-07-29 17:45:06'),
(56, 'Highlights', 6, 50.00, 60, 'Partial or full highlights for added hair dimension.', '2025-07-29 17:45:06'),
(57, 'Root Touch-Up', 6, 40.00, 50, 'Covers regrowth areas for a fresh look.', '2025-07-29 17:45:06'),
(58, 'Keratin Treatment', 6, 100.00, 120, 'Smooths and strengthens hair with keratin infusion.', '2025-07-29 17:45:06'),
(59, 'Hair Spa Treatment', 6, 50.00, 60, 'Deep conditioning therapy for healthy hair.', '2025-07-29 17:45:06'),
(60, 'Bridal Hairstyling', 6, 80.00, 90, 'Special hairstyling for brides and events.', '2025-07-29 17:45:06'),
(61, 'Day Makeup', 7, 40.00, 45, 'Light, natural makeup for daytime occasions.', '2025-07-29 17:45:06'),
(62, 'Evening/Party Makeup', 7, 60.00, 60, 'Glamorous makeup for night events or parties.', '2025-07-29 17:45:06'),
(63, 'Bridal Makeup', 7, 120.00, 90, 'Full wedding makeup package for brides.', '2025-07-29 17:45:06'),
(64, 'Airbrush Makeup', 7, 100.00, 75, 'Flawless airbrushed makeup finish.', '2025-07-29 17:45:06'),
(65, 'Photoshoot Makeup', 7, 80.00, 60, 'Makeup tailored for photography sessions.', '2025-07-29 17:45:06'),
(66, 'Eye Makeup Only', 7, 30.00, 30, 'Focus on enhancing eye beauty only.', '2025-07-29 17:45:06'),
(67, 'Makeup Trials', 7, 50.00, 50, 'Trial makeup before the final event look.', '2025-07-29 17:45:06'),
(68, 'Eyebrow Shaping', 8, 15.00, 20, 'Professional shaping of eyebrows.', '2025-07-29 17:45:06'),
(69, 'Eyebrow Tinting', 8, 20.00, 25, 'Temporary color enhancement for eyebrows.', '2025-07-29 17:45:06'),
(70, 'Eyelash Extensions (Classic)', 8, 80.00, 90, 'Natural look with single-lash extensions.', '2025-07-29 17:45:06'),
(71, 'Eyelash Extensions (Volume)', 8, 100.00, 100, 'Full, voluminous eyelash look.', '2025-07-29 17:45:06'),
(72, 'Eyelash Lift & Tint', 8, 70.00, 60, 'Lifting and tinting natural lashes.', '2025-07-29 17:45:06'),
(73, 'Eyebrow Lamination', 8, 60.00, 50, 'Straightens and sets brows for a fuller look.', '2025-07-29 17:45:06'),
(74, 'Half-Day Spa Package', 9, 180.00, 180, 'Relaxing package with massage, facial, and manicure.', '2025-07-29 17:45:06'),
(75, 'Full-Day Spa Retreat', 9, 300.00, 360, 'Luxury full-day pampering experience.', '2025-07-29 17:45:06'),
(76, 'Couple’s Spa Package', 9, 250.00, 240, 'Spa treatment designed for couples.', '2025-07-29 17:45:06'),
(77, 'Bride-to-Be Spa Package', 9, 280.00, 300, 'Complete beauty prep package for brides.', '2025-07-29 17:45:06'),
(78, 'Relax & Rejuvenate Combo', 9, 200.00, 210, 'Massage, facial, and mani-pedi combo package.', '2025-07-29 17:45:06'),
(79, 'Chemical Peel', 10, 90.00, 50, 'Removes dead skin layers for a youthful look.', '2025-07-29 17:45:06'),
(80, 'Microdermabrasion', 10, 100.00, 60, 'Exfoliates and renews overall skin tone and texture.', '2025-07-29 17:45:06'),
(81, 'Microneedling', 10, 120.00, 60, 'Stimulates collagen for firmer skin.', '2025-07-29 17:45:06'),
(82, 'Dermaplaning', 10, 80.00, 45, 'Removes dead skin and peach fuzz for smooth skin.', '2025-07-29 17:45:06'),
(83, 'Acne Scar Treatment', 10, 110.00, 70, 'Reduces appearance of acne scars and marks.', '2025-07-29 17:45:06'),
(84, 'Skin Brightening Peel', 10, 95.00, 50, 'Targets dullness and uneven skin tone.', '2025-07-29 17:45:06'),
(85, 'Anti-Pigmentation Treatment', 10, 100.00, 60, 'Lightens dark spots and hyperpigmentation.', '2025-07-29 17:45:06'),
(86, 'Essential Oil Full Body Massage', 11, 75.00, 60, 'Massage with essential oils for relaxation and healing.', '2025-07-29 17:45:06'),
(87, 'Aromatherapy Steam Inhalation', 11, 40.00, 30, 'Inhalation therapy to open airways and relax.', '2025-07-29 17:45:06'),
(88, 'Relaxing Aromatherapy Facial', 11, 60.00, 45, 'Facial using essential oils for soothing skin and mind.', '2025-07-29 17:45:06'),
(89, 'Anti-Wrinkle Facial', 12, 90.00, 60, 'Reduces appearance of fine lines and wrinkles.', '2025-07-29 17:45:06'),
(90, 'Botox Treatment', 12, 200.00, 30, 'Non-surgical treatment for wrinkle reduction.', '2025-07-29 17:45:06'),
(91, 'Collagen Booster Therapy', 12, 120.00, 50, 'Stimulates collagen for youthful, firm skin.', '2025-07-29 17:45:06'),
(92, 'Radiofrequency Skin Tightening', 12, 130.00, 60, 'Uses RF energy to tighten and lift skin.', '2025-07-29 17:45:06'),
(93, 'Vitamin C Infusion Facial', 12, 85.00, 60, 'Brightens and revitalizes tired skin with Vitamin C.', '2025-07-29 17:45:06'),
(94, 'Pre-Wedding Glow Package', 13, 200.00, 180, 'Skincare and relaxation treatments before the wedding.', '2025-07-29 17:45:06'),
(95, 'Wedding Day Full Beauty Package', 13, 300.00, 240, 'Full makeup, hair, and beauty prep for brides.', '2025-07-29 17:45:06'),
(96, 'Bridal Hair & Makeup Trial Sessions', 13, 120.00, 120, 'Trial run for bridal hair and makeup.', '2025-07-29 17:45:06'),
(97, 'Mehendi & Bridal Nail Care', 13, 100.00, 90, 'Henna art and nail grooming for brides.', '2025-07-29 17:45:06'),
(98, 'Reflexology Therapy', 14, 50.00, 45, 'Targets pressure points to promote healing and relaxation.', '2025-07-29 17:45:06'),
(99, 'Hydrotherapy Bath', 14, 60.00, 40, 'Water-based therapy for relaxation and detoxification.', '2025-07-29 17:45:06'),
(100, 'Acupuncture Session', 14, 70.00, 50, 'Traditional Chinese therapy using fine needles.', '2025-07-29 17:45:06'),
(101, 'Reiki Healing', 14, 65.00, 45, 'Energy healing technique to balance body and mind.', '2025-07-29 17:45:06'),
(102, 'Men’s Haircut & Beard Grooming', 15, 25.00, 40, 'Complete men’s haircut and beard styling.', '2025-07-29 17:45:06'),
(103, 'Men’s Facial', 15, 50.00, 45, 'Cleansing and hydrating facial for men.', '2025-07-29 17:45:06'),
(104, 'Men’s Sports Massage', 15, 80.00, 60, 'Deep tissue massage designed for men.', '2025-07-29 17:45:06'),
(105, 'Men’s Manicure & Pedicure', 15, 35.00, 50, 'Hand and foot grooming for men.', '2025-07-29 17:45:06'),
(106, 'Men’s Anti-Aging Skin Treatment', 15, 90.00, 60, 'Reduces wrinkles and revitalizes men’s skin.', '2025-07-29 17:45:06'),
(107, 'Teen Facial (Gentle)', 16, 40.00, 45, 'Mild cleansing facial for teenage skin.', '2025-07-29 17:45:06'),
(108, 'Teen Manicure & Pedicure', 16, 25.00, 50, 'Basic nail care tailored for teens.', '2025-07-29 17:45:06'),
(109, 'Teen Hair Styling', 16, 20.00, 30, 'Trendy hairstyles designed for teenagers.', '2025-07-29 17:45:06'),
(110, 'Teen Acne Treatment Facial', 16, 50.00, 60, 'Targets acne and prevents future breakouts for teens.', '2025-07-29 17:45:06');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
CREATE TABLE IF NOT EXISTS `service_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `description`) VALUES
(1, 'Facials', 'Various facial treatments for cleansing,  and rejuvenating the skin.'),
(2, 'Massages', 'Relaxing massages such as Swedish, deep tissue, hot stone, and more.'),
(3, 'Body Treatments', 'Body scrubs, wraps, and detox treatments for skin exfoliation and nourishment.'),
(4, 'Hair Removal', 'Waxing, threading, and other techniques for hair removal.'),
(5, 'Manicures & Pedicures', 'Nail care including trimming, shaping, polishing, and spa treatments.'),
(6, 'Hair Services', 'Haircuts, styling, coloring, and treatments like hair spa and keratin.'),
(7, 'Makeup Services', 'Professional makeup for events, weddings, photo shoots, and more.'),
(8, 'Eyebrow & Eyelash', 'Brow shaping, tinting, and eyelash extensions and lifting.'),
(9, 'Spa Packages', 'Bundled services offering a combination of treatments for full-body care.'),
(10, 'Skin Treatments', 'Advanced skincare such as microneedling, chemical peels, and dermaplaning.'),
(11, 'Aromatherapy', 'Use of essential oils for therapeutic massage and relaxation.'),
(12, 'Anti-Aging Treatments', 'Treatments designed to reduce signs of aging and promote youthful skin.'),
(13, 'Bridal Packages', 'Complete beauty services for brides including hair, makeup, and skincare.'),
(14, 'Wellness Therapies', 'Holistic treatments like reflexology, hydrotherapy, and acupuncture.'),
(15, 'Men’s Spa Services', 'Specialized spa treatments tailored for men’s skincare and grooming needs.'),
(16, 'Teen Services', 'Gentle services for teenage skin care and grooming.'),
(18, 'omena', 'for eating people');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'global_commission_percentage', '10');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT 'Female',
  `role` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `commission_rate` decimal(5,2) DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `role_id` int DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `full_name`, `phone`, `email`, `password`, `gender`, `role`, `salary`, `created_at`, `commission_rate`, `is_active`, `last_login`, `role_id`) VALUES
(11, 'Super Admin', '0712345678', 'admin@spa.com', '$2y$10$ifCXHPR5TciFk8qeQePZUe6en.U561RDq2KIODB8OGDfe8WG6cVqy', 'Male', 'Super Admin', 0.00, '2025-08-02 06:21:50', 0.00, 1, '2025-08-02 06:22:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

DROP TABLE IF EXISTS `tips`;
CREATE TABLE IF NOT EXISTS `tips` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appointment_id` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `tip_date` date DEFAULT NULL,
  `staff_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tips`
--

INSERT INTO `tips` (`id`, `appointment_id`, `amount`, `tip_date`, `staff_id`) VALUES
(11, 24, 6000.00, '2025-07-31', 10),
(10, 23, 2000.00, '2025-07-31', 10),
(9, 22, 5000.00, '2025-07-31', 10);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
