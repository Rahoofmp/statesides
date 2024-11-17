-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2021 at 04:13 PM
-- Server version: 8.0.23-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pine_tree`
--

-- --------------------------------------------------------

--
-- Table structure for table `activate_inactivate_history`
--

CREATE TABLE `activate_inactivate_history` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `current_status` varchar(11) NOT NULL,
  `new_status` varchar(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '2017-10-10 00:00:00',
  `qualified_by` varchar(11) NOT NULL DEFAULT 'code'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `done_by` int DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
  `activity` varchar(400) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `notification_status` tinyint(1) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `sub_admin_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `user_id`, `ip`, `done_by`, `date`, `activity`, `notification_status`, `data`, `sub_admin_id`) VALUES
(1, 101, '192.168.1.17', 100, '2021-04-21 09:53:59', 'user registered', 0, 'a:4:{s:7:\"user_id\";i:101;s:10:\"first_name\";s:4:\"pack\";s:5:\"email\";s:18:\"packager@gmail.com\";s:6:\"mobile\";s:10:\"9488483196\";}', 0),
(2, 102, '192.168.1.17', 100, '2021-04-21 09:54:40', 'user registered', 0, 'a:4:{s:7:\"user_id\";i:102;s:10:\"first_name\";s:5:\"store\";s:5:\"email\";s:21:\"storekeeper@gmail.com\";s:6:\"mobile\";s:10:\"9600212024\";}', 0),
(3, 103, '192.168.1.17', 100, '2021-04-21 09:55:49', 'user registered', 0, 'a:4:{s:7:\"user_id\";i:103;s:10:\"first_name\";s:8:\"ghfghfgh\";s:5:\"email\";s:15:\"sanal@gmail.com\";s:6:\"mobile\";s:10:\"9488483198\";}', 0),
(4, 104, '192.168.1.17', 100, '2021-04-21 09:56:21', 'user registered', 0, 'a:4:{s:7:\"user_id\";i:104;s:10:\"first_name\";s:8:\"ghfghfgh\";s:5:\"email\";s:17:\"ibrahim@gmail.com\";s:6:\"mobile\";s:8:\"45678912\";}', 0),
(5, 100, '192.168.1.17', 100, '2021-04-21 09:58:12', 'login', 0, '', 0),
(6, 100, '127.0.0.1', 100, '2021-04-21 10:56:14', 'login', 0, '', 0),
(7, 100, '192.168.1.17', 100, '2021-04-21 11:33:19', 'update_company_settings', 0, 'a:5:{s:12:\"website_name\";s:42:\"Pine Tree Lane Furniture Manufacturing LLC\";s:7:\"address\";s:47:\"Cyath Contracting LLC\r\nDubai Investment Park -1\";s:5:\"email\";s:14:\"info@Cyath.org\";s:5:\"phone\";s:14:\"+91 1234567890\";s:6:\"update\";s:7:\"website\";}', 0),
(8, 100, '192.168.1.17', 100, '2021-04-21 11:33:41', 'update_password_admin', 0, 'a:5:{s:8:\"username\";s:5:\"admin\";s:12:\"new_password\";s:6:\"123123\";s:16:\"confirm_password\";s:6:\"123123\";s:17:\"credential_update\";s:8:\"password\";s:9:\"ticket_id\";a:1:{s:7:\"message\";s:58:\"Error: Error: Could not execute: /usr/sbin/sendmail -t -i \";}}', 0),
(9, 100, '192.168.1.17', 100, '2021-04-21 11:33:46', 'logout', 0, '', 0),
(10, 100, '192.168.1.17', 100, '2021-04-21 11:33:51', 'login', 0, '', 0),
(11, 100, '192.168.1.17', 100, '2021-04-21 11:34:13', 'update_password_admin', 0, 'a:5:{s:8:\"username\";s:5:\"admin\";s:12:\"new_password\";s:8:\"admin123\";s:16:\"confirm_password\";s:8:\"admin123\";s:17:\"credential_update\";s:8:\"password\";s:9:\"ticket_id\";a:1:{s:7:\"message\";s:58:\"Error: Error: Could not execute: /usr/sbin/sendmail -t -i \";}}', 0),
(12, 100, '192.168.1.17', 100, '2021-04-21 11:53:02', 'logout', 0, '', 0),
(13, 101, '192.168.1.17', 101, '2021-04-21 11:53:35', 'login', 0, '', 0),
(14, 101, '192.168.1.17', 101, '2021-04-21 12:44:17', 'logout', 0, '', 0),
(15, 102, '192.168.1.17', 102, '2021-04-21 12:44:21', 'login', 0, '', 0),
(16, 100, '127.0.0.1', 100, '2021-04-21 12:49:58', 'logout', 0, '', 0),
(17, 102, '127.0.0.1', 102, '2021-04-21 12:50:04', 'login', 0, '', 0),
(18, 102, '127.0.0.1', 102, '2021-04-21 12:54:59', 'logout', 0, '', 0),
(19, 101, '127.0.0.1', 101, '2021-04-21 12:55:05', 'login', 0, '', 0),
(20, 102, '127.0.0.1', 102, '2021-04-21 12:56:04', 'login', 0, '', 0),
(21, 102, '127.0.0.1', 102, '2021-04-21 12:58:55', 'logout', 0, '', 0),
(22, 100, '127.0.0.1', 100, '2021-04-21 12:58:59', 'login', 0, '', 0),
(23, 102, '192.168.1.17', 102, '2021-04-21 14:12:33', 'update_password_admin', 0, 'a:4:{s:12:\"new_password\";s:6:\"123123\";s:16:\"confirm_password\";s:6:\"123123\";s:17:\"credential_update\";s:8:\"password\";s:9:\"ticket_id\";a:1:{s:7:\"message\";s:58:\"Error: Error: Could not execute: /usr/sbin/sendmail -t -i \";}}', 0),
(24, 102, '192.168.1.17', 102, '2021-04-21 14:12:46', 'update_password_admin', 0, 'a:4:{s:12:\"new_password\";s:6:\"123456\";s:16:\"confirm_password\";s:6:\"123456\";s:17:\"credential_update\";s:8:\"password\";s:9:\"ticket_id\";a:1:{s:7:\"message\";s:58:\"Error: Error: Could not execute: /usr/sbin/sendmail -t -i \";}}', 0),
(25, 102, '192.168.1.17', 102, '2021-04-21 14:22:17', 'logout', 0, '', 0),
(26, 103, '192.168.1.17', 103, '2021-04-21 14:23:11', 'login', 0, '', 0),
(27, 103, '192.168.1.17', 103, '2021-04-21 14:24:23', 'logout', 0, '', 0),
(28, 101, '192.168.1.17', 101, '2021-04-21 14:24:31', 'login', 0, '', 0),
(29, 101, '192.168.1.17', 101, '2021-04-21 14:24:42', 'logout', 0, '', 0),
(30, 102, '192.168.1.17', 102, '2021-04-21 14:24:54', 'login', 0, '', 0),
(31, 102, '192.168.1.17', 102, '2021-04-21 14:26:58', 'Delivery status changed', 0, 'a:3:{s:6:\"status\";s:16:\"send_to_delivery\";s:13:\"update_status\";s:6:\"update\";s:11:\"delivery_id\";s:1:\"4\";}', 0),
(32, 102, '192.168.1.17', 102, '2021-04-21 14:27:19', 'logout', 0, '', 0),
(33, 103, '192.168.1.17', 103, '2021-04-21 14:27:28', 'login', 0, '', 0),
(34, 100, '127.0.0.1', 100, '2021-04-21 14:27:29', 'login', 0, '', 0),
(35, 101, '127.0.0.1', 101, '2021-04-21 14:32:21', 'logout', 0, '', 0),
(36, 102, '127.0.0.1', 102, '2021-04-21 14:32:30', 'login', 0, '', 0),
(37, 100, '127.0.0.1', 100, '2021-04-21 14:33:16', 'logout', 0, '', 0),
(38, 103, '127.0.0.1', 103, '2021-04-21 14:33:27', 'login', 0, '', 0),
(39, 103, '127.0.0.1', 103, '2021-04-21 14:35:11', 'logout', 0, '', 0),
(40, 100, '127.0.0.1', 100, '2021-04-21 14:38:43', 'login', 0, '', 0),
(41, 103, '192.168.1.17', 103, '2021-04-21 14:49:11', 'logout', 0, '', 0),
(42, 101, '192.168.1.17', 101, '2021-04-21 14:49:18', 'login', 0, '', 0),
(43, 102, '127.0.0.1', 102, '2021-04-21 14:50:05', 'logout', 0, '', 0),
(44, 103, '127.0.0.1', 103, '2021-04-21 14:50:09', 'login', 0, '', 0),
(45, 101, '192.168.1.17', 101, '2021-04-21 15:00:44', 'logout', 0, '', 0),
(46, 103, '192.168.1.17', 103, '2021-04-21 15:00:49', 'login', 0, '', 0),
(47, 103, '192.168.1.17', 103, '2021-04-21 15:03:17', 'logout', 0, '', 0),
(48, 102, '192.168.1.17', 102, '2021-04-21 15:03:39', 'login', 0, '', 0),
(49, 102, '192.168.1.17', 102, '2021-04-21 15:08:10', 'Delivery status changed', 0, 'a:3:{s:6:\"status\";s:16:\"send_to_delivery\";s:13:\"update_status\";s:6:\"update\";s:11:\"delivery_id\";s:1:\"6\";}', 0),
(50, 102, '192.168.1.17', 102, '2021-04-21 15:08:13', 'logout', 0, '', 0),
(51, 103, '192.168.1.17', 103, '2021-04-21 15:08:18', 'login', 0, '', 0),
(52, 103, '192.168.1.17', 103, '2021-04-21 15:21:16', 'logout', 0, '', 0),
(53, 102, '192.168.1.17', 102, '2021-04-21 15:21:34', 'login', 0, '', 0),
(54, 102, '192.168.1.17', 102, '2021-04-21 15:22:26', 'Delivery status changed', 0, 'a:3:{s:6:\"status\";s:16:\"send_to_delivery\";s:13:\"update_status\";s:6:\"update\";s:11:\"delivery_id\";s:1:\"7\";}', 0),
(55, 102, '192.168.1.17', 102, '2021-04-21 15:22:36', 'logout', 0, '', 0),
(56, 103, '192.168.1.17', 103, '2021-04-21 15:22:41', 'login', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int NOT NULL,
  `country_name` varchar(128) NOT NULL DEFAULT 'NA',
  `country_code` varchar(2) NOT NULL DEFAULT 'NA',
  `phone_code` varchar(10) NOT NULL DEFAULT '0',
  `iso_code_3` varchar(3) NOT NULL DEFAULT 'NA',
  `lang_ref_id` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `country_name`, `country_code`, `phone_code`, `iso_code_3`, `lang_ref_id`) VALUES
(1, 'Afghanistan', 'AF', '+93', 'AFG', 1),
(2, 'Albania', 'AL', '+355', 'ALB', 1),
(3, 'Algeria', 'DZ', '+213', 'DZA', 1),
(4, 'American Samoa', 'AS', '+1', 'ASM', 1),
(5, 'Andorra', 'AD', '+376', 'AND', 1),
(6, 'Angola', 'AO', '+244', 'AGO', 1),
(7, 'Anguilla', 'AI', '+1', 'AIA', 1),
(8, 'Antarctica', 'AQ', '+672', 'ATA', 1),
(9, 'Antigua and Barbuda', 'AG', '+1', 'ATG', 1),
(10, 'Argentina', 'AR', '+54', 'ARG', 1),
(11, 'Armenia', 'AM', '+374', 'ARM', 1),
(12, 'Aruba', 'AW', '+297', 'ABW', 1),
(13, 'Australia', 'AU', '+61', 'AUS', 1),
(14, 'Austria', 'AT', '+43', 'AUT', 1),
(15, 'Azerbaijan', 'AZ', '+994', 'AZE', 1),
(16, 'Bahamas', 'BS', '+1', 'BHS', 1),
(17, 'Bahrain', 'BH', '+973', 'BHR', 1),
(18, 'Bangladesh', 'BD', '+880', 'BGD', 1),
(19, 'Barbados', 'BB', '+1', 'BRB', 1),
(20, 'Belarus', 'BY', '+375', 'BLR', 1),
(21, 'Belgium', 'BE', '+32', 'BEL', 1),
(22, 'Belize', 'BZ', '+501', 'BLZ', 1),
(23, 'Benin', 'BJ', '+229', 'BEN', 1),
(24, 'Bermuda', 'BM', '+1', 'BMU', 1),
(25, 'Bhutan', 'BT', '+975', 'BTN', 1),
(26, 'Bolivia', 'BO', '+591', 'BOL', 1),
(27, 'Bosnia and Herzegovina', 'BA', '+387', 'BIH', 1),
(28, 'Botswana', 'BW', '+267', 'BWA', 1),
(29, 'Bouvet Island', 'BV', '+20', 'BVT', 1),
(30, 'Brazil', 'BR', '+55', 'BRA', 1),
(31, 'British Indian Ocean Territory', 'IO', '+20', 'IOT', 1),
(32, 'Brunei Darussalam', 'BN', '+673', 'BRN', 1),
(33, 'Bulgaria', 'BG', '+359', 'BGR', 1),
(34, 'Burkina Faso', 'BF', '+226', 'BFA', 1),
(35, 'Burundi', 'BI', '+257', 'BDI', 1),
(36, 'Cambodia', 'KH', '+855', 'KHM', 1),
(37, 'Cameroon', 'CM', '+237', 'CMR', 1),
(38, 'Canada', 'CA', '+1', 'CAN', 1),
(39, 'Cape Verde', 'CV', '+238', 'CPV', 1),
(40, 'Cayman Islands', 'KY', '+1', 'CYM', 1),
(41, 'Central African Republic', 'CF', '+236', 'CAF', 1),
(42, 'Chad', 'TD', '+235', 'TCD', 1),
(43, 'Chile', 'CL', '+56', 'CHL', 1),
(44, 'China', 'CN', '+86', 'CHN', 1),
(45, 'Christmas Island', 'CX', '+61', 'CXR', 1),
(46, 'Cocos (Keeling) Islands', 'CC', '+61', 'CCK', 1),
(47, 'Colombia', 'CO', '+57', 'COL', 1),
(48, 'Comoros', 'KM', '+269', 'COM', 1),
(49, 'Congo', 'CG', '+242', 'COG', 1),
(50, 'Cook Islands', 'CK', '+682', 'COK', 1),
(51, 'Costa Rica', 'CR', '+506', 'CRI', 1),
(52, 'Cote D\'Ivoire', 'CI', '+225', 'CIV', 1),
(53, 'Croatia', 'HR', '+385', 'HRV', 1),
(54, 'Cuba', 'CU', '+53', 'CUB', 1),
(55, 'Cyprus', 'CY', '+357', 'CYP', 1),
(56, 'Czech Republic', 'CZ', '+420', 'CZE', 1),
(57, 'Denmark', 'DK', '+45', 'DNK', 1),
(58, 'Djibouti', 'DJ', '+253', 'DJI', 1),
(59, 'Dominica', 'DM', '+1', 'DMA', 1),
(60, 'Dominican Republic', 'DO', '+1', 'DOM', 1),
(61, 'East Timor', 'TL', '+670', 'TLS', 1),
(62, 'Ecuador', 'EC', '+593', 'ECU', 1),
(63, 'Egypt', 'EG', '+20', 'EGY', 1),
(64, 'El Salvador', 'SV', '+503', 'SLV', 1),
(65, 'Equatorial Guinea', 'GQ', '+240', 'GNQ', 1),
(66, 'Eritrea', 'ER', '+291', 'ERI', 1),
(67, 'Estonia', 'EE', '+372', 'EST', 1),
(68, 'Ethiopia', 'ET', '+251', 'ETH', 1),
(69, 'Falkland Islands (Malvinas)', 'FK', '+500', 'FLK', 1),
(70, 'Faroe Islands', 'FO', '+298', 'FRO', 1),
(71, 'Fiji', 'FJ', '+679', 'FJI', 1),
(72, 'Finland', 'FI', '+358', 'FIN', 1),
(74, 'France, Metropolitan', 'FR', '+0', 'FRA', 1),
(75, 'French Guiana', 'GF', '+224', 'GUF', 1),
(76, 'French Polynesia', 'PF', '+689', 'PYF', 1),
(77, 'French Southern Territories', 'TF', '+262', 'ATF', 1),
(78, 'Gabon', 'GA', '+241', 'GAB', 1),
(79, 'Gambia', 'GM', '+220', 'GMB', 1),
(80, 'Georgia', 'GE', '+995', 'GEO', 1),
(81, 'Germany', 'DE', '+49', 'DEU', 1),
(82, 'Ghana', 'GH', '+233', 'GHA', 1),
(83, 'Gibraltar', 'GI', '+350', 'GIB', 1),
(84, 'Greece', 'GR', '+30', 'GRC', 1),
(85, 'Greenland', 'GL', '+299', 'GRL', 1),
(86, 'Grenada', 'GD', '+1', 'GRD', 1),
(87, 'Guadeloupe', 'GP', '+20', 'GLP', 1),
(88, 'Guam', 'GU', '+1', 'GUM', 1),
(89, 'Guatemala', 'GT', '+502', 'GTM', 1),
(90, 'Guinea', 'GN', '+224', 'GIN', 1),
(91, 'Guinea-Bissau', 'GW', '+245', 'GNB', 1),
(92, 'Guyana', 'GY', '+592', 'GUY', 1),
(93, 'Haiti', 'HT', '+509', 'HTI', 1),
(94, 'Heard and Mc Donald Islands', 'HM', '+0', 'HMD', 1),
(95, 'Honduras', 'HN', '+504', 'HND', 1),
(96, 'Hong Kong', 'HK', '+852', 'HKG', 1),
(97, 'Hungary', 'HU', '+36', 'HUN', 1),
(98, 'Iceland', 'IS', '+354', 'ISL', 1),
(99, 'India', 'IN', '+91', 'IND', 1),
(100, 'Indonesia', 'ID', '+62', 'IDN', 1),
(101, 'Iran (Islamic Republic of)', 'IR', '+98', 'IRN', 1),
(102, 'Iraq', 'IQ', '+964', 'IRQ', 1),
(103, 'Ireland', 'IE', '+353', 'IRL', 1),
(104, 'Israel', 'IL', '+972', 'ISR', 1),
(105, 'Italy', 'IT', '+39', 'ITA', 1),
(106, 'Jamaica', 'JM', '+1', 'JAM', 1),
(107, 'Japan', 'JP', '+81', 'JPN', 1),
(108, 'Jordan', 'JO', '+962', 'JOR', 1),
(109, 'Kazakhstan', 'KZ', '+7', 'KAZ', 1),
(110, 'Kenya', 'KE', '+254', 'KEN', 1),
(111, 'Kiribati', 'KI', '+686', 'KIR', 1),
(112, 'North Korea', 'KP', '+850', 'PRK', 1),
(113, 'Korea, Republic of', 'KR', '+82', 'KOR', 1),
(114, 'Kuwait', 'KW', '+965', 'KWT', 1),
(115, 'Kyrgyzstan', 'KG', '+996', 'KGZ', 1),
(116, 'Lao People\'s Democratic Republic', 'LA', '+856', 'LAO', 1),
(117, 'Latvia', 'LV', '+371', 'LVA', 1),
(118, 'Lebanon', 'LB', '+961', 'LBN', 1),
(119, 'Lesotho', 'LS', '+266', 'LSO', 1),
(120, 'Liberia', 'LR', '+231', 'LBR', 1),
(121, 'Libyan Arab Jamahiriya', 'LY', '+218', 'LBY', 1),
(122, 'Liechtenstein', 'LI', '+423', 'LIE', 1),
(123, 'Lithuania', 'LT', '+370', 'LTU', 1),
(124, 'Luxembourg', 'LU', '+352', 'LUX', 1),
(125, 'Macau', 'MO', '+853', 'MAC', 1),
(126, 'FYROM', 'MK', '+389', 'MKD', 1),
(127, 'Madagascar', 'MG', '+261', 'MDG', 1),
(128, 'Malawi', 'MW', '+265', 'MWI', 1),
(129, 'Malaysia', 'MY', '+60', 'MYS', 1),
(130, 'Maldives', 'MV', '+960', 'MDV', 1),
(131, 'Mali', 'ML', '+223', 'MLI', 1),
(132, 'Malta', 'MT', '+356', 'MLT', 1),
(133, 'Marshall Islands', 'MH', '+692', 'MHL', 1),
(134, 'Martinique', 'MQ', '+222', 'MTQ', 1),
(135, 'Mauritania', 'MR', '+222', 'MRT', 1),
(136, 'Mauritius', 'MU', '+230', 'MUS', 1),
(137, 'Mayotte', 'YT', '+262', 'MYT', 1),
(138, 'Mexico', 'MX', '+52', 'MEX', 1),
(139, 'Micronesia, Federated States of', 'FM', '+691', 'FSM', 1),
(140, 'Moldova, Republic of', 'MD', '+373', 'MDA', 1),
(141, 'Monaco', 'MC', '+377', 'MCO', 1),
(142, 'Mongolia', 'MN', '+976', 'MNG', 1),
(143, 'Montserrat', 'MS', '+1', 'MSR', 1),
(144, 'Morocco', 'MA', '+212', 'MAR', 1),
(145, 'Mozambique', 'MZ', '+258', 'MOZ', 1),
(146, 'Myanmar', 'MM', '+95', 'MMR', 1),
(147, 'Namibia', 'NA', '+264', 'NAM', 1),
(148, 'Nauru', 'NR', '+674', 'NRU', 1),
(149, 'Nepal', 'NP', '+977', 'NPL', 1),
(150, 'Netherlands', 'NL', '+31', 'NLD', 1),
(151, 'Netherlands Antilles', 'AN', '+599', 'ANT', 1),
(152, 'New Caledonia', 'NC', '+687', 'NCL', 1),
(153, 'New Zealand', 'NZ', '+64', 'NZL', 1),
(154, 'Nicaragua', 'NI', '+505', 'NIC', 1),
(155, 'Niger', 'NE', '+227', 'NER', 1),
(156, 'Nigeria', 'NG', '+234', 'NGA', 1),
(157, 'Niue', 'NU', '+683', 'NIU', 1),
(158, 'Norfolk Island', 'NF', '+672', 'NFK', 1),
(159, 'Northern Mariana Islands', 'MP', '+1', 'MNP', 1),
(160, 'Norway', 'NO', '+47', 'NOR', 1),
(161, 'Oman', 'OM', '+968', 'OMN', 1),
(162, 'Pakistan', 'PK', '+92', 'PAK', 1),
(163, 'Palau', 'PW', '+680', 'PLW', 1),
(164, 'Panama', 'PA', '+507', 'PAN', 1),
(165, 'Papua New Guinea', 'PG', '+675', 'PNG', 1),
(166, 'Paraguay', 'PY', '+595', 'PRY', 1),
(167, 'Peru', 'PE', '+51', 'PER', 1),
(168, 'Philippines', 'PH', '+63', 'PHL', 1),
(169, 'Pitcairn', 'PN', '+870', 'PCN', 1),
(170, 'Poland', 'PL', '+48', 'POL', 1),
(171, 'Portugal', 'PT', '+351', 'PRT', 1),
(172, 'Puerto Rico', 'PR', '+1', 'PRI', 1),
(173, 'Qatar', 'QA', '+974', 'QAT', 1),
(174, 'Reunion', 'RE', '+20', 'REU', 1),
(175, 'Romania', 'RO', '+40', 'ROM', 1),
(176, 'Russian Federation', 'RU', '+7', 'RUS', 1),
(177, 'Rwanda', 'RW', '+250', 'RWA', 1),
(178, 'Saint Kitts and Nevis', 'KN', '+1', 'KNA', 1),
(179, 'Saint Lucia', 'LC', '+1', 'LCA', 1),
(180, 'Saint Vincent and the Grenadines', 'VC', '+1', 'VCT', 1),
(181, 'Samoa', 'WS', '+685', 'WSM', 1),
(182, 'San Marino', 'SM', '+378', 'SMR', 1),
(183, 'Sao Tome and Principe', 'ST', '+239', 'STP', 1),
(184, 'Saudi Arabia', 'SA', '+966', 'SAU', 1),
(185, 'Senegal', 'SN', '+221', 'SEN', 1),
(186, 'Seychelles', 'SC', '+248', 'SYC', 1),
(187, 'Sierra Leone', 'SL', '+232', 'SLE', 1),
(188, 'Singapore', 'SG', '+65', 'SGP', 1),
(189, 'Slovak Republic', 'SK', '+421', 'SVK', 1),
(190, 'Slovenia', 'SI', '+386', 'SVN', 1),
(191, 'Solomon Islands', 'SB', '+677', 'SLB', 1),
(192, 'Somalia', 'SO', '+252', 'SOM', 1),
(193, 'South Africa', 'ZA', '+27', 'ZAF', 1),
(194, 'South Georgia &amp; South Sandwich Islands', 'GS', '+500', 'SGS', 1),
(195, 'Spain', 'ES', '+34', 'ESP', 1),
(196, 'Sri Lanka', 'LK', '+94', 'LKA', 1),
(197, 'St. Helena', 'SH', '+290', 'SHN', 1),
(198, 'St. Pierre and Miquelon', 'PM', '+508', 'SPM', 1),
(199, 'Sudan', 'SD', '+249', 'SDN', 1),
(200, 'Suriname', 'SR', '+597', 'SUR', 1),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', '+47', 'SJM', 1),
(202, 'Swaziland', 'SZ', '+268', 'SWZ', 1),
(203, 'Sweden', 'SE', '+46', 'SWE', 1),
(204, 'Switzerland', 'CH', '+41', 'CHE', 1),
(205, 'Syrian Arab Republic', 'SY', '+963', 'SYR', 1),
(206, 'Taiwan', 'TW', '+886', 'TWN', 1),
(207, 'Tajikistan', 'TJ', '+992', 'TJK', 1),
(208, 'Tanzania, United Republic of', 'TZ', '+255', 'TZA', 1),
(209, 'Thailand', 'TH', '+66', 'THA', 1),
(210, 'Togo', 'TG', '+228', 'TGO', 1),
(211, 'Tokelau', 'TK', '+690', 'TKL', 1),
(212, 'Tonga', 'TO', '+676', 'TON', 1),
(213, 'Trinidad and Tobago', 'TT', '+1', 'TTO', 1),
(214, 'Tunisia', 'TN', '+216', 'TUN', 1),
(215, 'Turkey', 'TR', '+90', 'TUR', 1),
(216, 'Turkmenistan', 'TM', '+993', 'TKM', 1),
(217, 'Turks and Caicos Islands', 'TC', '+1', 'TCA', 1),
(218, 'Tuvalu', 'TV', '+688', 'TUV', 1),
(219, 'Uganda', 'UG', '+256', 'UGA', 1),
(220, 'Ukraine', 'UA', '+380', 'UKR', 1),
(221, 'United Arab Emirates', 'AE', '+971', 'ARE', 1),
(222, 'United Kingdom', 'GB', '+44', 'GBR', 1),
(223, 'United States', 'US', '+1', 'USA', 1),
(224, 'United States Minor Outlying Islands', 'UM', '+0', 'UMI', 1),
(225, 'Uruguay', 'UY', '+598', 'URY', 1),
(226, 'Uzbekistan', 'UZ', '+998', 'UZB', 1),
(227, 'Vanuatu', 'VU', '+678', 'VUT', 1),
(228, 'Vatican City State (Holy See)', 'VA', '+379', 'VAT', 1),
(229, 'Venezuela', 'VE', '+58', 'VEN', 1),
(230, 'Viet Nam', 'VN', '+84', 'VNM', 1),
(231, 'Virgin Islands (British)', 'VG', '+1284', 'VGB', 1),
(232, 'Virgin Islands (U.S.)', 'VI', '+1340', 'VIR', 1),
(233, 'Wallis and Futuna Islands', 'WF', '+681', 'WLF', 1),
(234, 'Western Sahara', 'EH', '+20', 'ESH', 1),
(235, 'Yemen', 'YE', '+967', 'YEM', 1),
(237, 'Democratic Republic of Congo', 'CD', '+243', 'COD', 1),
(238, 'Zambia', 'ZM', '+260', 'ZMB', 1),
(239, 'Zimbabwe', 'ZW', '+263', 'ZWE', 1),
(240, 'Jersey', 'JE', '+20', 'JEY', 1),
(241, 'Guernsey', 'GG', '+20', 'GGY', 1),
(242, 'Montenegro', 'ME', '+382', 'MNE', 1),
(243, 'Serbia', 'RS', '+381', 'SRB', 1),
(244, 'Aaland Islands', 'AX', '+0', 'ALA', 1),
(245, 'Bonaire, Sint Eustatius and Saba', 'BQ', '+599', 'BES', 1),
(246, 'Curacao', 'CW', '+599', 'CUW', 1),
(247, 'Palestinian Territory, Occupied', 'PS', '+970', 'PSE', 1),
(248, 'South Sudan', 'SS', '+211', 'SSD', 1),
(249, 'St. Barthelemy', 'BL', '+590', 'BLM', 1),
(250, 'St. Martin (French part)', 'MF', '+590', 'MAF', 1),
(251, 'Canary Islands', 'IC', '+34', 'ICA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_id` int NOT NULL,
  `title` varchar(32) NOT NULL,
  `code` varchar(3) NOT NULL,
  `symbol_left` varchar(12) NOT NULL,
  `symbol_right` varchar(12) NOT NULL,
  `decimal_place` char(1) NOT NULL,
  `value` float(15,8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `delete_status` varchar(6) NOT NULL DEFAULT 'no',
  `date_modified` datetime NOT NULL,
  `inr_value` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_place`, `value`, `status`, `delete_status`, `date_modified`, `inr_value`) VALUES
(1, 'INR', 'INR', '₹', '', '2', 1.00000000, 1, 'no', '2018-12-01 11:39:21', 69.770387);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_notes`
--

CREATE TABLE `delivery_notes` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `supervisor_id` int NOT NULL DEFAULT '0',
  `project_id` int NOT NULL,
  `code` bigint NOT NULL,
  `description` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `total_items` int NOT NULL,
  `date_created` datetime NOT NULL,
  `driver_id` int NOT NULL,
  `vehicle` varchar(250) NOT NULL,
  `status` varchar(250) NOT NULL COMMENT 'send_to_delivery, delivered, pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery_notes`
--

INSERT INTO `delivery_notes` (`id`, `user_id`, `supervisor_id`, `project_id`, `code`, `description`, `total_items`, `date_created`, `driver_id`, `vehicle`, `status`) VALUES
(1, 100, 0, 1, 10001, '', 0, '2021-04-21 11:21:46', 104, '78956', 'deleted'),
(2, 102, 0, 1, 10002, '', 0, '2021-04-21 12:46:40', 104, 'yutyu', 'deleted'),
(3, 102, 0, 1, 10003, '', 0, '2021-04-21 12:53:23', 104, '123231', 'deleted'),
(4, 102, 0, 1, 10004, '', 0, '2021-04-21 14:21:05', 104, 'gfhfgh', 'send_to_delivery'),
(5, 102, 103, 1, 10005, '', 0, '2021-04-21 14:37:40', 104, 'fdsafdsa', 'partially_delivered'),
(6, 102, 103, 1, 10006, '', 0, '2021-04-21 15:07:32', 104, 'ghfgh', 'delivered'),
(7, 102, 103, 1, 10007, '', 0, '2021-04-21 15:21:44', 104, '', 'delivered');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_packages`
--

CREATE TABLE `delivery_packages` (
  `id` int NOT NULL,
  `delivery_id` int NOT NULL,
  `package_id` int NOT NULL,
  `added_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` varchar(250) NOT NULL COMMENT 'send_to_delivery, delivered, pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `delivery_packages`
--

INSERT INTO `delivery_packages` (`id`, `delivery_id`, `package_id`, `added_date`, `updated_date`, `status`) VALUES
(1, 1, 1, '2021-04-21 11:22:48', '0000-00-00 00:00:00', 'deleted'),
(2, 2, 1, '2021-04-21 12:47:32', '0000-00-00 00:00:00', 'deleted'),
(3, 2, 3, '2021-04-21 12:47:32', '0000-00-00 00:00:00', 'deleted'),
(4, 3, 1, '2021-04-21 12:53:37', '0000-00-00 00:00:00', 'deleted'),
(5, 3, 3, '2021-04-21 12:53:37', '0000-00-00 00:00:00', 'deleted'),
(6, 4, 1, '2021-04-21 14:21:31', '0000-00-00 00:00:00', 'pending'),
(7, 4, 3, '2021-04-21 14:21:31', '0000-00-00 00:00:00', 'pending'),
(8, 5, 8, '2021-04-21 14:56:45', '2021-04-21 14:58:01', 'delivered'),
(9, 5, 9, '2021-04-21 14:56:45', '2021-04-21 14:56:54', 'send_to_delivery'),
(10, 6, 10, '2021-04-21 15:07:55', '2021-04-21 15:08:48', 'delivered'),
(11, 6, 11, '2021-04-21 15:07:55', '2021-04-21 15:08:48', 'delivered'),
(12, 7, 12, '2021-04-21 15:22:14', '2021-04-21 15:22:26', 'send_to_delivery'),
(13, 7, 13, '2021-04-21 15:22:14', '2021-04-21 15:23:23', 'delivered');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int NOT NULL,
  `file_title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `doc_file_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `doc_description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `uploaded_date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
  `sort_order` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_info`
--

CREATE TABLE `employee_info` (
  `user_detail_id` int NOT NULL,
  `user_detail_refid` int NOT NULL DEFAULT '0',
  `user_detail_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `user_detail_second_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `user_detail_mobile` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `user_detail_email` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `user_photo` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'nophoto.jpg',
  `join_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `heading` varchar(20) NOT NULL DEFAULT 'NA',
  `content` longtext NOT NULL,
  `status` varchar(6) NOT NULL DEFAULT 'yes',
  `start_date` datetime NOT NULL DEFAULT '2017-10-10 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '2017-10-10 00:00:00',
  `add_date` datetime NOT NULL DEFAULT '2017-10-10 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '2017-10-10 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `internal_mail_details`
--

CREATE TABLE `internal_mail_details` (
  `id` int NOT NULL,
  `from_user` int NOT NULL DEFAULT '0',
  `to_user` int NOT NULL DEFAULT '0',
  `subject` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `date` datetime DEFAULT '0000-00-10 00:00:00',
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes',
  `type` varchar(10) NOT NULL DEFAULT 'single',
  `message` longtext CHARACTER SET utf8 COLLATE utf8_bin,
  `read_status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `language_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `code` varchar(5) NOT NULL,
  `image` varchar(64) NOT NULL,
  `directory` varchar(32) NOT NULL,
  `site_perm` int NOT NULL,
  `login_perm` int NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `name`, `code`, `image`, `directory`, `site_perm`, `login_perm`, `sort_order`) VALUES
(1, 'English', 'en', 'en.png', 'english', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `login_info`
--

CREATE TABLE `login_info` (
  `user_id` int UNSIGNED NOT NULL,
  `order_id` int NOT NULL DEFAULT '0',
  `user_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'user',
  `user_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `password` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_rank_id` int NOT NULL DEFAULT '0',
  `user_reward_id` int NOT NULL DEFAULT '0',
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '1=active, 0= inactive',
  `position` int NOT NULL DEFAULT '1',
  `father_id` int NOT NULL DEFAULT '0',
  `original_father_id` int NOT NULL,
  `sponsor_id` int NOT NULL DEFAULT '0',
  `first_pair` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `package_id` int NOT NULL DEFAULT '0',
  `joining_date` datetime DEFAULT NULL,
  `user_level` int NOT NULL DEFAULT '0',
  `sponsor_level` int NOT NULL,
  `payment_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `registered_by` varchar(20) NOT NULL DEFAULT 'auto',
  `default_leg` int NOT NULL DEFAULT '1',
  `left_father` int NOT NULL DEFAULT '0',
  `right_father` int NOT NULL DEFAULT '0',
  `left_sponsor` int NOT NULL DEFAULT '0',
  `right_sponsor` int NOT NULL DEFAULT '0',
  `secure_pin` varchar(200) NOT NULL,
  `default_lang` int NOT NULL DEFAULT '1',
  `kyc_status` varchar(11) NOT NULL DEFAULT 'no',
  `renewal_date` datetime NOT NULL DEFAULT '2020-12-12 23:59:59',
  `left_total` double NOT NULL,
  `right_total` double NOT NULL,
  `left_carry` double NOT NULL,
  `right_carry` double NOT NULL,
  `monthly_fee_status` int NOT NULL DEFAULT '1',
  `personal_points` double NOT NULL,
  `cumulative_points` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_info`
--

INSERT INTO `login_info` (`user_id`, `order_id`, `user_type`, `user_name`, `password`, `user_rank_id`, `user_reward_id`, `status`, `position`, `father_id`, `original_father_id`, `sponsor_id`, `first_pair`, `package_id`, `joining_date`, `user_level`, `sponsor_level`, `payment_type`, `registered_by`, `default_leg`, `left_father`, `right_father`, `left_sponsor`, `right_sponsor`, `secure_pin`, `default_lang`, `kyc_status`, `renewal_date`, `left_total`, `right_total`, `left_carry`, `right_carry`, `monthly_fee_status`, `personal_points`, `cumulative_points`) VALUES
(100, 1, 'admin', 'admin', '$2a$08$iVDGAXehQfhl4WJNfohmIOW5df0M3Dhllb.0U7Kh4NF1WlQUWmxnu', 8, 4, '1', 0, 0, 0, 0, '0', 8, '2021-04-21 09:50:44', 0, 0, 'free_join', 'auto', 1, 1, 2, 1, 2, '12345678', 1, 'no', '2020-12-12 23:59:59', 0, 0, 0, 0, 1, 0, 0),
(101, 0, 'packager', 'packager', '$2a$08$.R2bKlBoUydaMawpuMXeX.U8zZt.3k0rO/hk13PrgmmjcvMQ6fBaO', 0, 0, '1', 1, 0, 0, 0, '0', 0, '2021-04-21 09:53:59', 0, 0, 'NA', 'auto', 1, 0, 0, 0, 0, '', 1, 'no', '2020-12-12 23:59:59', 0, 0, 0, 0, 1, 0, 0),
(102, 0, 'store_keeper', 'storekeeper', '$2a$08$0v0x6ZhOJJSN9OoHzGv.GOwBZih1bAh2R6KEcsCCVdMYhQ.eSUBBi', 0, 0, '1', 1, 0, 0, 0, '0', 0, '2021-04-21 09:54:40', 0, 0, 'NA', 'auto', 1, 0, 0, 0, 0, '', 1, 'no', '2020-12-12 23:59:59', 0, 0, 0, 0, 1, 0, 0),
(103, 0, 'supervisor', 'sanal', '$2a$08$Ff1gwnmWaKJpKZY/b9kS0u85sSfpQYYXB7FzBKVappxW9J4ZEtiJu', 0, 0, '1', 1, 0, 0, 0, '0', 0, '2021-04-21 09:55:48', 0, 0, 'NA', 'auto', 1, 0, 0, 0, 0, '', 1, 'no', '2020-12-12 23:59:59', 0, 0, 0, 0, 1, 0, 0),
(104, 0, 'driver', 'ibrahim', '$2a$08$.TlIAtJumKqYENuM.owzautV/xPz4qnXNBfLdYeKGxhCwALCgr3QG', 0, 0, '1', 1, 0, 0, 0, '0', 0, '2021-04-21 09:56:21', 0, 0, 'NA', 'auto', 1, 0, 0, 0, 0, '', 1, 'no', '2020-12-12 23:59:59', 0, 0, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mail_contents`
--

CREATE TABLE `mail_contents` (
  `id` int NOT NULL,
  `type` varchar(55) NOT NULL DEFAULT 'mail',
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mail_details`
--

CREATE TABLE `mail_details` (
  `mailadid` int NOT NULL,
  `from_user` int NOT NULL DEFAULT '0',
  `to_user` int NOT NULL,
  `subject` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `message` longtext CHARACTER SET utf8 COLLATE utf8_bin,
  `read_status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no',
  `date` datetime NOT NULL DEFAULT '2017-10-14 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mail_settings`
--

CREATE TABLE `mail_settings` (
  `id` int NOT NULL,
  `type` varchar(11) NOT NULL DEFAULT 'mail',
  `authentication` int NOT NULL DEFAULT '0',
  `protocol` varchar(20) NOT NULL,
  `host` varchar(20) NOT NULL,
  `port` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `timeout` varchar(20) NOT NULL,
  `mail_cc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mail_settings`
--

INSERT INTO `mail_settings` (`id`, `type`, `authentication`, `protocol`, `host`, `port`, `username`, `password`, `timeout`, `mail_cc`) VALUES
(1, 'mail', 0, 'none', 'fdsafdsa', '111', 'asdasd', 'asdasd', '111', 'dsadsa@vcas.fdsafsadf');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `en` varchar(99) NOT NULL COMMENT 'English lang: Default',
  `parent_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'clip-home-2',
  `status` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `perm_admin` int NOT NULL DEFAULT '0',
  `perm_store_keeper` int NOT NULL DEFAULT '0',
  `perm_packager` int NOT NULL DEFAULT '0',
  `perm_supervisor` int NOT NULL DEFAULT '0',
  `perm_pre_user` int NOT NULL DEFAULT '0',
  `perm_user` int DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `target` varchar(20) DEFAULT NULL,
  `type` varchar(99) NOT NULL DEFAULT 'site'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES
(1, 'Dashboard', '#', 'dashboard/index', 'dashboard', '1', 1, 1, 1, 1, 0, 1, 0, NULL, 'site'),
(2, 'Logout', '#', 'logout', 'lock', '1', 1, 1, 1, 1, 0, 1, 100, NULL, 'site'),
(3, 'User Master', '#', 'signup/index', 'person_add', '1', 1, 0, 0, 0, 0, 0, 1, NULL, 'site'),
(14, 'Profile', '#', '#', 'person_search', '1', 1, 1, 1, 1, 0, 1, 7, NULL, 'site'),
(15, 'View Users', '3', 'member/all-members', 'dashboard', '1', 1, 0, 0, 0, 0, 0, 4, NULL, 'site'),
(16, 'User Profile', '14', 'member/profile', 'dashboard', '1', 1, 1, 1, 1, 0, 1, 2, NULL, 'site'),
(17, 'Change Credential', '14', 'member/change-credential', 'dashboard', '1', 1, 1, 1, 1, 0, 1, 3, NULL, 'site'),
(29, 'Website Profile', '14', 'settings/website-profile', 'dashboard', '1', 1, 0, 0, 0, 0, 0, 1, NULL, 'site'),
(32, 'Report', '#', '#', 'event_note', '1', 1, 1, 1, 0, 0, 1, 8, NULL, 'site'),
(46, 'My Dashboard', '#', 'dashboard', 'dashboard', '1', 1, 0, 0, 0, 0, 1, 1, NULL, 'support'),
(55, 'Members', '32', 'report/members', 'dashboard', '1', 1, 0, 0, 0, 0, 1, 1, NULL, 'site'),
(64, 'My Packages', '#', 'member/packages', 'backup', '1', 0, 0, 0, 0, 0, 1, 5, 'NULL', 'site'),
(96, 'Signup', '3', 'signup', 'dashboard', '1', 1, 0, 0, 0, 0, 0, 3, NULL, 'site'),
(99, 'Project Master', '#', '', 'aspect_ratio', '1', 1, 0, 0, 0, 0, 1, 1, NULL, 'site'),
(100, 'Create New', '99', 'project/add-project', 'dashboard', '1', 1, 0, 0, 0, 0, 0, 1, NULL, 'site'),
(102, 'Packages', '#', '', 'fact_check', '1', 1, 1, 1, 0, 0, 1, 1, NULL, 'site'),
(103, 'Create New', '102', 'packages/add-package', 'dashboard', '1', 1, 0, 1, 0, 0, 0, 1, NULL, 'site'),
(105, 'Project List', '99', 'project/project-list', 'dashboard', '1', 1, 0, 0, 0, 0, 0, 3, NULL, 'site'),
(106, 'List', '102', 'packages/package-list', 'dashboard', '1', 1, 0, 1, 0, 0, 0, 2, NULL, 'site'),
(107, 'Delivery', '#', '#', 'person_search', '1', 1, 1, 0, 1, 0, 1, 4, NULL, 'site'),
(108, 'Create', '107', 'delivery/create', 'dashboard', '1', 1, 1, 0, 0, 0, 0, 1, NULL, 'site'),
(109, 'List', '107', 'delivery/delivery-list', 'dashboard', '1', 1, 1, 0, 1, 0, 0, 2, NULL, 'site'),
(110, 'Packages', '32', 'packages/reports', 'dashboard', '1', 1, 0, 1, 0, 0, 0, 1, NULL, 'site'),
(111, 'Deliveries', '32', 'delivery/reports', 'dashboard', '1', 1, 1, 0, 0, 0, 0, 1, NULL, 'site'),
(112, 'Read Code', '107', 'delivery/read-delivery-code', 'dashboard', '1', 1, 0, 0, 1, 0, 0, 3, NULL, 'site'),
(113, 'Read QR Code', '102', 'packages/read-package-code', 'dashboard', '1', 1, 1, 1, 0, 0, 0, 3, NULL, 'site');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` int NOT NULL,
  `news_title` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `news_desc` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `sort_order` int NOT NULL,
  `news_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `package_items`
--

CREATE TABLE `package_items` (
  `id` int NOT NULL,
  `package_id` int NOT NULL,
  `name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `qty` int NOT NULL,
  `date_addedd` datetime NOT NULL,
  `serial_no` varchar(99) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `package_items`
--

INSERT INTO `package_items` (`id`, `package_id`, `name`, `qty`, `date_addedd`, `serial_no`, `status`) VALUES
(1, 1, 'mask', 5, '2021-04-21 10:11:30', '1', 'active'),
(2, 1, 'sanitizer', 6, '2021-04-21 10:11:30', '02', 'active'),
(3, 1, 'glouse', 3, '2021-04-21 10:11:30', '3', 'active'),
(4, 2, 'mobile', 5, '2021-04-21 11:55:07', '01', 'active'),
(5, 2, 'tech pad', 5, '2021-04-21 11:55:07', '02', 'active'),
(6, 2, 'screen card', 5, '2021-04-21 11:55:07', '03', 'active'),
(7, 3, 'server', 5, '2021-04-21 11:56:19', '1', 'active'),
(8, 3, 'domain', 5, '2021-04-21 11:56:19', '02', 'active'),
(9, 3, 'godaday', 8, '2021-04-21 11:56:19', '3', 'active'),
(10, 4, 'ssl', 1, '2021-04-21 12:12:41', '01', 'active'),
(11, 4, 'data', 3, '2021-04-21 12:12:41', '2', 'active'),
(12, 4, 'gfdfgd', 5, '2021-04-21 12:12:41', '3', 'active'),
(13, 5, 'long', 1, '2021-04-21 12:19:46', '1', 'active'),
(14, 5, 'journey', 8, '2021-04-21 12:19:46', '02', 'active'),
(15, 5, 'grand', 8, '2021-04-21 12:19:46', '03', 'active'),
(16, 5, 'offer', 5, '2021-04-21 12:21:04', '6', 'active'),
(17, 5, 'big offer', 8, '2021-04-21 12:21:40', '6', 'active'),
(18, 5, 'fan', 1, '2021-04-21 12:24:28', '7', 'active'),
(19, 5, 'cealing', 5, '2021-04-21 12:24:28', '02', 'active'),
(20, 5, 'newone', 5, '2021-04-21 12:24:28', '3', 'active'),
(21, 5, 'arrival', 2, '2021-04-21 12:24:28', '4', 'active'),
(22, 6, 'rahul01', 1, '2021-04-21 14:46:42', '1', 'active'),
(23, 6, 'sanal', 3, '2021-04-21 14:46:42', '2', 'active'),
(24, 6, 'niyas', 3, '2021-04-21 14:46:42', '3', 'active'),
(25, 6, 'ibru', 4, '2021-04-21 14:46:42', '4', 'active'),
(26, 7, 'ring', 10, '2021-04-21 14:50:06', '1', 'active'),
(27, 7, 'wing', 6, '2021-04-21 14:50:06', '2', 'active'),
(28, 7, 'grand', 9, '2021-04-21 14:50:06', '3', 'active'),
(29, 8, '254', 23, '2021-04-21 14:51:25', '005', 'active'),
(30, 8, 'fds', 52, '2021-04-21 14:51:25', '055', 'active'),
(31, 9, 'fdsa', 2, '2021-04-21 14:55:41', '0565', 'active'),
(32, 9, '0csda', 26, '2021-04-21 14:55:41', 's05', 'active'),
(33, 10, 'kumbam', 5, '2021-04-21 15:06:23', '1', 'active'),
(34, 10, 'mela', 5, '2021-04-21 15:06:23', '2', 'active'),
(35, 10, 'pilgrims', 5, '2021-04-21 15:06:23', '3', 'active'),
(36, 10, 'budasen', 5, '2021-04-21 15:06:23', '04', 'active'),
(37, 10, 'monk', 8, '2021-04-21 15:06:23', '5', 'active'),
(38, 11, 'covid', 19, '2021-04-21 15:07:13', '1', 'active'),
(39, 11, 'dangerous', 8, '2021-04-21 15:07:13', '2', 'active'),
(40, 12, 'iruvar', 5, '2021-04-21 15:20:33', '1', 'active'),
(41, 12, 'kannukalkku', 5, '2021-04-21 15:20:33', '2', 'active'),
(42, 12, 'sweet', 15, '2021-04-21 15:20:33', '3', 'active'),
(43, 13, 'vinnil', 5, '2021-04-21 15:21:03', '1', 'active'),
(44, 13, 'data', 5, '2021-04-21 15:21:03', '2', 'active'),
(45, 13, 'thak', 3, '2021-04-21 15:21:03', '3', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_table`
--

CREATE TABLE `password_reset_table` (
  `password_reset_id` int NOT NULL,
  `keyword` bigint NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `reset_status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `location` varchar(250) NOT NULL,
  `map` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `status` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `user_id`, `project_name`, `customer_name`, `mobile`, `location`, `map`, `email`, `date`, `status`) VALUES
(1, 100, 'time set', 'rahul', '9600212024', '(9.178927189951702, 76.4987699660488)', 'http://maps.google.com/?q=(9.178927189951702, 76.4987699660488)', 'rahul@gmail.com', '2021-04-21 10:04:16', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `project_packages`
--

CREATE TABLE `project_packages` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `project_id` int NOT NULL,
  `code` bigint NOT NULL,
  `name` varchar(250) NOT NULL,
  `total` double NOT NULL,
  `date_created` datetime NOT NULL,
  `updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL COMMENT 'pending_delivery, delivered, pending',
  `image` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project_packages`
--

INSERT INTO `project_packages` (`id`, `user_id`, `project_id`, `code`, `name`, `total`, `date_created`, `updated_date`, `status`, `image`) VALUES
(1, 100, 1, 10001, 'sanju', 0, '2021-04-21 10:10:27', '2021-04-21 14:26:58', 'send_to_delivery', 'no-image.png'),
(2, 101, 1, 10002, 'mobile', 0, '2021-04-21 11:54:28', '2021-04-21 11:54:28', 'deleted', 'no-image.png'),
(3, 101, 1, 10003, 'server', 0, '2021-04-21 11:55:41', '2021-04-21 14:26:58', 'send_to_delivery', 'no-image.png'),
(4, 101, 1, 10004, 'niyas', 0, '2021-04-21 12:12:22', '2021-04-21 12:12:22', 'deleted', 'no-image.png'),
(5, 101, 1, 10005, 'travel', 0, '2021-04-21 12:18:48', '2021-04-21 00:00:00', 'deleted', 'no-image.png'),
(6, 100, 1, 10006, 'rahul', 0, '2021-04-21 14:46:09', '2021-04-21 14:46:09', 'deleted', 'no-image.png'),
(7, 101, 1, 10007, 'sanal', 0, '2021-04-21 14:49:38', '2021-04-21 14:49:38', 'deleted', 'no-image.png'),
(8, 100, 1, 10008, 'Package-01', 0, '2021-04-21 14:51:18', '2021-04-21 14:58:01', 'delivered', 'no-image.png'),
(9, 100, 1, 10009, 'Package-02', 0, '2021-04-21 14:55:30', '2021-04-21 14:56:54', 'send_to_delivery', 'no-image.png'),
(10, 100, 1, 10010, 'saniyasi', 0, '2021-04-21 15:05:00', '2021-04-21 15:08:48', 'delivered', 'no-image.png'),
(11, 100, 1, 10011, 'ghfgh', 0, '2021-04-21 15:06:48', '2021-04-21 15:08:48', 'delivered', 'no-image.png'),
(12, 100, 1, 10012, 'oruvar', 0, '2021-04-21 15:20:03', '2021-04-21 15:22:26', 'send_to_delivery', 'no-image.png'),
(13, 100, 1, 10013, 'vinoodu', 0, '2021-04-21 15:20:41', '2021-04-21 15:23:23', 'deleted', 'no-image.png');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int NOT NULL,
  `code` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `code`, `key`, `value`, `data`) VALUES
(5, 'config', 'payment', '1', ''),
(6, 'config', 'mlm_plan', 'Unilevel', ''),
(7, 'config', 'lang_id', '1', ''),
(8, 'config', 'lang_name', 'english', ''),
(9, 'config', 'register_amount', '10', ''),
(40, 'module', 'e_mail_alert', '1', '{\"icon\":\"fa-envelope-o\",\"link\":\"settings\\/sub_modules\"}'),
(20, 'theme', 'admin_theme_folder', 'material', ''),
(21, 'theme', 'investor_theme_folder', 'material', ''),
(25, 'user_name_config', 'user_name_max_length', '10', ''),
(26, 'config', 'password_length', '6', ''),
(27, 'config', 'phone_number_length', '6', ''),
(28, 'config', 'reg_order_prefix', 'MINDS', ''),
(29, 'module', 'rank_status', '1', '{\"icon\":\"fa-line-chart\",\"link\":\"settings\\/rank_settings\"}'),
(30, 'config', 'referal_status', 'yes', ''),
(31, 'config', 'upline_commission_status', 'yes', ''),
(32, 'module', 'social_login', '0', '{\"icon\":\"fa-google-plus\",\"link\":\"settings\\/sub_modules\"}\n'),
(33, 'social_login', 'google_login', '0', ''),
(34, 'config', 'payout_transation_fee', '10', ''),
(35, 'social_login', 'twitter_login', '0', ''),
(36, 'module', 'g_recaptcha', '0', '{\"icon\":\"fa-qrcode\",\"link\":\"settings\\/sub_modules\"}\n'),
(37, 'g_recaptcha', 'recaptcha_login', '0', ''),
(38, 'g_recaptcha', 'recaptcha_payout', '0', ''),
(39, 'module', 'currency', '1', '{\"icon\":\"fa-usd\",\"link\":\"currency\\/currency_management\"}'),
(45, 'theme', 'support_theme_folder', 'support', ''),
(41, 'module', 'user_name_config', '1', '{\"icon\":\"fa-edit\",\"link\":\"settings\\/user_name_config\"}'),
(42, 'user_name_config', 'user_name_min_length', '6', ''),
(43, 'user_name_config', 'user_name_prefix', 'GP', ''),
(44, 'user_name_config', 'user_name_postfix', '', ''),
(47, 'plan', 'pair_value', '1', ''),
(48, 'config', 'service_charge', '0', ''),
(49, 'e_mail_alert', 'register', '1', ''),
(50, 'e_mail_alert', 'payout_release', '1', ''),
(51, 'e_mail_alert', 'payout_request_deleted', '1', ''),
(52, 'e_mail_alert', 'upgrade_package', '1', ''),
(54, 'plan', 'investment_percentage', '500', ''),
(55, 'config', 'generation_status', 'yes', ''),
(56, 'plan', 'generation_level', '5', ''),
(57, 'plan', 'generation_bonus', '10', ''),
(58, 'config', 'donation_status', 'no', ''),
(59, 'social_login', 'facebook_login', '0', ''),
(60, 'module', '2fa', '0', '{\"icon\":\"fa-edit\"}'),
(61, 'module', 'language', '1', '{\"icon\":\"fa-globe\",\"link\":\"settings\\/multi_language\"}'),
(62, 'module', 'lead capture', '0', '{\"icon\":\"fa-globe\"}'),
(63, 'wallet_config', 'min_investment', '500', ''),
(64, 'plan', 'roi_bonus', '1', ''),
(65, 'wallet_config', 'withdraw_bank_fee', '10', ''),
(66, 'wallet_config', 'withdraw_btc_fee', '10', ''),
(67, 'wallet_config', 'tranfer_fees_below_100', '1', ''),
(68, 'wallet_config', 'tranfer_fees_above_100', '.5', ''),
(69, 'plan', 'max_roi_bonus', '250', ''),
(70, 'system', 'system_status', '1', ''),
(71, 'config', 'royalty_pair_value', '1500', ''),
(72, 'config', 'royalty_bonus', '5', ''),
(73, 'config', 'ticket_prefix', 'MINDS', ''),
(74, 'config', 'qualification_fee', '99.99', ''),
(75, 'config', 'special_bonus', '5', ''),
(76, 'config', 'roi_board1', '3.4', ''),
(77, 'config', 'roi_board2', '17', ''),
(78, 'config', 'roi_board3', '34', ''),
(79, 'config', 'roi_board4', '138', ''),
(80, 'config', 'cutdown_bonus', '10', '');

-- --------------------------------------------------------

--
-- Table structure for table `site_info`
--

CREATE TABLE `site_info` (
  `id` int NOT NULL,
  `name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `email` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `phone` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `favicon` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `address` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `default_lang` int NOT NULL DEFAULT '1',
  `login_lang` int NOT NULL,
  `facebook` varchar(100) NOT NULL DEFAULT 'https://facebook.com/',
  `gplus` varchar(99) NOT NULL DEFAULT 'https://plus.google.com/',
  `linkedin` varchar(99) NOT NULL DEFAULT 'https://www.linkedin.com/',
  `country_id` int NOT NULL,
  `currency_id` int NOT NULL,
  `maintenance_mode` int NOT NULL DEFAULT '0',
  `maintenance_mode_text` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_info`
--

INSERT INTO `site_info` (`id`, `name`, `email`, `phone`, `logo`, `favicon`, `address`, `default_lang`, `login_lang`, `facebook`, `gplus`, `linkedin`, `country_id`, `currency_id`, `maintenance_mode`, `maintenance_mode_text`) VALUES
(1, 'Pine Tree Lane Furniture Manufacturing LLC', 'info@Cyath.org', '+91 1234567890', 'logo.png', 'favicon.png', 'Cyath Contracting LLC\r\nDubai Investment Park -1', 1, 1, 'https://facebook.com/', 'https://plus.google.com/', 'https://www.linkedin.com/', 16, 2, 0, 'Site Unbder Maiantance. Please check after some time later');

-- --------------------------------------------------------

--
-- Table structure for table `site_maintenance`
--

CREATE TABLE `site_maintenance` (
  `id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL,
  `description` longtext NOT NULL,
  `date_of_availability` date NOT NULL DEFAULT '2017-10-09',
  `block_login` int NOT NULL DEFAULT '0',
  `block_register` int NOT NULL DEFAULT '0',
  `block_ecommerce` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_maintenance`
--

INSERT INTO `site_maintenance` (`id`, `status`, `title`, `description`, `date_of_availability`, `block_login`, `block_register`, `block_ecommerce`) VALUES
(1, 0, 'Site is Under Maintenance', '<p>Site is Under Maintenance&nbsp;</p>', '2017-10-09', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `username_change_history`
--

CREATE TABLE `username_change_history` (
  `id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `old_username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `new_username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `modified_date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `first_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `second_name` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `address` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `country` int NOT NULL DEFAULT '209',
  `state` int NOT NULL,
  `city` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `zip_code` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NA',
  `mobile` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `registration_type` varchar(100) NOT NULL DEFAULT 'free',
  `dob` date NOT NULL DEFAULT '0001-01-01',
  `gender` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Male',
  `account_number` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `accholder` varchar(100) NOT NULL,
  `ifsc` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `bank_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `branch_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `bank_address` varchar(250) NOT NULL,
  `user_photo` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'nophoto.png',
  `user_thump` varchar(50) NOT NULL DEFAULT 'thump_image.png',
  `bitcoin_address` text NOT NULL,
  `facebook` varchar(99) NOT NULL DEFAULT 'https://facebook.com/',
  `instagram` varchar(99) NOT NULL DEFAULT 'https://instagram.com/',
  `twitter` varchar(99) NOT NULL DEFAULT 'https://www.linkedin.com/',
  `linkedin` varchar(99) NOT NULL DEFAULT 'https://twitter.com/',
  `payeer_id` text NOT NULL,
  `skrill` text NOT NULL,
  `aadhaar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `first_name`, `second_name`, `address`, `country`, `state`, `city`, `zip_code`, `mobile`, `email`, `registration_type`, `dob`, `gender`, `account_number`, `accholder`, `ifsc`, `bank_name`, `branch_name`, `bank_address`, `user_photo`, `user_thump`, `bitcoin_address`, `facebook`, `instagram`, `twitter`, `linkedin`, `payeer_id`, `skrill`, `aadhaar`) VALUES
(1, 100, 'test', '', NULL, 0, 10, '', 'NA', '123211', 'admin@123.com', 'free', '2015-08-04', 'Male', '', '', '', '', '', '', 'nophoto.png', 'thump_image.png', '', 'https://facebook.com/', 'https://instagram.com/', 'https://www.linkedin.com/', 'https://twitter.com/', '', '', ''),
(2, 101, 'pack', 'NA', NULL, 209, 0, 'NA', 'NA', '9488483196', 'packager@gmail.com', 'free', '0001-01-01', 'Male', '', '', '', '', '', '', 'nophoto.png', 'thump_image.png', '', 'https://facebook.com/', 'https://instagram.com/', 'https://www.linkedin.com/', 'https://twitter.com/', '', '', ''),
(3, 102, 'store', 'NA', NULL, 209, 0, 'NA', 'NA', '9600212024', 'storekeeper@gmail.com', 'free', '0001-01-01', 'Male', '', '', '', '', '', '', 'nophoto.png', 'thump_image.png', '', 'https://facebook.com/', 'https://instagram.com/', 'https://www.linkedin.com/', 'https://twitter.com/', '', '', ''),
(4, 103, 'ghfghfgh', 'NA', NULL, 209, 0, 'NA', 'NA', '9488483198', 'sanal@gmail.com', 'free', '0001-01-01', 'Male', '', '', '', '', '', '', 'nophoto.png', 'thump_image.png', '', 'https://facebook.com/', 'https://instagram.com/', 'https://www.linkedin.com/', 'https://twitter.com/', '', '', ''),
(5, 104, 'ghfghfgh', 'NA', NULL, 209, 0, 'NA', 'NA', '45678912', 'ibrahim@gmail.com', 'free', '0001-01-01', 'Male', '', '', '', '', '', '', 'nophoto.png', 'thump_image.png', '', 'https://facebook.com/', 'https://instagram.com/', 'https://www.linkedin.com/', 'https://twitter.com/', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activate_inactivate_history`
--
ALTER TABLE `activate_inactivate_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `delivery_notes`
--
ALTER TABLE `delivery_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_packages`
--
ALTER TABLE `delivery_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_info`
--
ALTER TABLE `employee_info`
  ADD PRIMARY KEY (`user_detail_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `internal_mail_details`
--
ALTER TABLE `internal_mail_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `login_info`
--
ALTER TABLE `login_info`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `first` (`user_name`);

--
-- Indexes for table `mail_contents`
--
ALTER TABLE `mail_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_details`
--
ALTER TABLE `mail_details`
  ADD PRIMARY KEY (`mailadid`);

--
-- Indexes for table `mail_settings`
--
ALTER TABLE `mail_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `package_items`
--
ALTER TABLE `package_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_table`
--
ALTER TABLE `password_reset_table`
  ADD PRIMARY KEY (`password_reset_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_packages`
--
ALTER TABLE `project_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `site_info`
--
ALTER TABLE `site_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_maintenance`
--
ALTER TABLE `site_maintenance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `username_change_history`
--
ALTER TABLE `username_change_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activate_inactivate_history`
--
ALTER TABLE `activate_inactivate_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `currency_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery_notes`
--
ALTER TABLE `delivery_notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `delivery_packages`
--
ALTER TABLE `delivery_packages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_info`
--
ALTER TABLE `employee_info`
  MODIFY `user_detail_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internal_mail_details`
--
ALTER TABLE `internal_mail_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `language_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_info`
--
ALTER TABLE `login_info`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `mail_contents`
--
ALTER TABLE `mail_contents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_details`
--
ALTER TABLE `mail_details`
  MODIFY `mailadid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_settings`
--
ALTER TABLE `mail_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_items`
--
ALTER TABLE `package_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `password_reset_table`
--
ALTER TABLE `password_reset_table`
  MODIFY `password_reset_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_packages`
--
ALTER TABLE `project_packages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `site_info`
--
ALTER TABLE `site_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_maintenance`
--
ALTER TABLE `site_maintenance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `username_change_history`
--
ALTER TABLE `username_change_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
