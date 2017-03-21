-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2017 at 09:50 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_account`
--

CREATE TABLE `apninv_a3m_account` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(24) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `createdon` datetime NOT NULL,
  `verifiedon` datetime DEFAULT NULL,
  `lastsignedinon` datetime DEFAULT NULL,
  `resetsenton` datetime DEFAULT NULL,
  `deletedon` datetime DEFAULT NULL,
  `suspendedon` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `apninv_a3m_account`
--

INSERT INTO `apninv_a3m_account` (`id`, `username`, `email`, `password`, `createdon`, `verifiedon`, `lastsignedinon`, `resetsenton`, `deletedon`, `suspendedon`) VALUES
(1, 'admin', 'riponmailbox@gmail.com', '$2a$08$aRW.sXIormgRq41LYJGCreZWl0f89I5Tpph5uO3VRavsih.NxiA.a', '2014-11-24 16:17:45', '2014-11-24 16:17:51', '2017-02-14 03:03:06', '2015-08-02 07:00:10', NULL, NULL),
(5, 'ripon', 'zahidul.ripon@dnet.com.bd', '$2a$08$4EDK/zLX/epZrnfZDfP9RO1K4PllvjXO2UV1ZgTU4Hq2xU27m.gMu', '2017-02-08 08:24:43', NULL, '2017-02-14 09:42:15', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_account_details`
--

CREATE TABLE `apninv_a3m_account_details` (
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(160) DEFAULT NULL,
  `firstname` varchar(80) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `postalcode` varchar(40) DEFAULT NULL,
  `country` char(2) DEFAULT NULL,
  `language` char(2) DEFAULT NULL,
  `timezone` varchar(40) DEFAULT NULL,
  `citimezone` varchar(6) DEFAULT NULL,
  `picture` varchar(240) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `apninv_a3m_account_details`
--

INSERT INTO `apninv_a3m_account_details` (`account_id`, `fullname`, `firstname`, `lastname`, `dateofbirth`, `gender`, `postalcode`, `country`, `language`, `timezone`, `citimezone`, `picture`) VALUES
(1, 'Zahidul Hossein Ripon', 'Zahidul', 'Ripon', '1983-08-25', 'm', '1216', 'bd', 'en', 'Asia/Dhaka', NULL, 'pic_c4ca4238a0b923820dcc509a6f75849b.jpg'),
(5, 'Zahidul Hossein Ripon', 'Zahidul', 'Ripon', '1983-08-25', 'm', '1600', 'bd', 'bn', 'Asia/Bishkek', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_account_facebook`
--

CREATE TABLE `apninv_a3m_account_facebook` (
  `account_id` bigint(20) NOT NULL,
  `facebook_id` bigint(20) NOT NULL,
  `linkedon` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_account_openid`
--

CREATE TABLE `apninv_a3m_account_openid` (
  `openid` varchar(240) NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `linkedon` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_account_twitter`
--

CREATE TABLE `apninv_a3m_account_twitter` (
  `account_id` bigint(20) NOT NULL,
  `twitter_id` bigint(20) NOT NULL,
  `oauth_token` varchar(80) NOT NULL,
  `oauth_token_secret` varchar(80) NOT NULL,
  `linkedon` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_acl_permission`
--

CREATE TABLE `apninv_a3m_acl_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `suspendedon` datetime DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `apninv_a3m_acl_permission`
--

INSERT INTO `apninv_a3m_acl_permission` (`id`, `key`, `description`, `suspendedon`, `is_system`) VALUES
(2, 'create_roles', 'Create new roles', NULL, 1),
(3, 'retrieve_roles', 'View existing roles', NULL, 1),
(4, 'update_roles', 'Update existing roles', NULL, 1),
(5, 'delete_roles', 'Delete existing roles', NULL, 1),
(6, 'create_permissions', 'Create new permissions', NULL, 1),
(7, 'retrieve_permissions', 'View existing permissions', NULL, 1),
(8, 'update_permissions', 'Update existing permissions', NULL, 1),
(9, 'delete_permissions', 'Delete existing permissions', NULL, 1),
(10, 'create_users', 'Create new users', NULL, 1),
(11, 'retrieve_users', 'View existing users', NULL, 1),
(12, 'update_users', 'Update existing users', NULL, 1),
(13, 'delete_users', 'Delete existing users', NULL, 1),
(14, 'ban_users', 'Ban and Unban existing users', NULL, 1),
(15, 'cms_add_news', NULL, NULL, 0),
(16, 'cms_news_update', NULL, NULL, 0),
(17, 'cms_news_delete', NULL, NULL, 0),
(18, 'cms_view_page', NULL, NULL, 0),
(19, 'cms_add_page', NULL, NULL, 0),
(20, 'cms_page_update', NULL, NULL, 0),
(21, 'cms_page_delete', NULL, NULL, 0),
(22, 'cms_view_gallery', NULL, NULL, 0),
(23, 'cms_add_gallery', NULL, NULL, 0),
(24, 'cms_gallery_update', NULL, NULL, 0),
(25, 'cms_gallery_delete', NULL, NULL, 0),
(26, 'cms_view_slide', NULL, NULL, 0),
(27, 'cms_add_slide', NULL, NULL, 0),
(28, 'cms_slide_update', NULL, NULL, 0),
(29, 'cms_slide_delete', NULL, NULL, 0),
(30, 'cms_view_news', 'Permission to view news list', NULL, 0),
(31, 'view_aponjon_member', 'can view_aponjon_member', NULL, 0),
(32, 'can_generate_prepaid_card', 'Can generate prepaid card', NULL, 0),
(33, 'can_view_prepaid_card_list', 'can_view_prepaid_card_list', NULL, 0),
(34, 'can_view_prepaid_pin', 'Can view prepaid pin', NULL, 0),
(35, 'can_download_card_info', 'Can download card info for printing', NULL, 0),
(36, 'can_recharge_card', 'Can recharge card on behalf of customer', NULL, 0),
(37, 'can_activate_card', 'can activate deactivate card', NULL, 0),
(38, 'can_unblock_msisdn', 'Can unblock msisdn', NULL, 0),
(39, 'can_view_block_list', 'Can view block mobile list', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_acl_role`
--

CREATE TABLE `apninv_a3m_acl_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `suspendedon` datetime DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `apninv_a3m_acl_role`
--

INSERT INTO `apninv_a3m_acl_role` (`id`, `name`, `description`, `suspendedon`, `is_system`) VALUES
(1, 'Admin', 'Website Administrator', NULL, 1),
(2, 'Manager', 'Aponjon Inventory Manger', NULL, 0),
(3, 'Operator', 'Aponjon Operator (Call Center)', NULL, 0),
(4, 'Distributor', 'Card Distributor', NULL, 0),
(5, 'Delear', 'Aponjon delear', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_rel_account_permission`
--

CREATE TABLE `apninv_a3m_rel_account_permission` (
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_rel_account_role`
--

CREATE TABLE `apninv_a3m_rel_account_role` (
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `apninv_a3m_rel_account_role`
--

INSERT INTO `apninv_a3m_rel_account_role` (`account_id`, `role_id`) VALUES
(1, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `apninv_a3m_rel_role_permission`
--

CREATE TABLE `apninv_a3m_rel_role_permission` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `apninv_a3m_rel_role_permission`
--

INSERT INTO `apninv_a3m_rel_role_permission` (`role_id`, `permission_id`) VALUES
(3, 39),
(2, 39),
(1, 39),
(3, 38),
(2, 38),
(1, 38),
(1, 37),
(3, 36),
(2, 36),
(1, 36),
(1, 35),
(1, 34),
(2, 33),
(1, 33),
(1, 32),
(1, 14),
(1, 13),
(1, 12),
(1, 11),
(1, 10),
(1, 9),
(1, 8),
(1, 7),
(1, 6),
(1, 5),
(1, 4),
(1, 3),
(2, 10),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `apninv_action_log`
--

CREATE TABLE `apninv_action_log` (
  `action_id` int(11) NOT NULL,
  `action_name` varchar(100) NOT NULL,
  `action_perform_by` int(11) NOT NULL,
  `action_date_time` datetime NOT NULL,
  `action_details` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_card_inventory`
--

CREATE TABLE `apninv_card_inventory` (
  `card_id` int(11) NOT NULL,
  `card_serial` int(11) NOT NULL,
  `card_pin` varchar(100) NOT NULL,
  `card_type` int(1) NOT NULL COMMENT '1=100, 2=200, 3=500',
  `active_status` int(1) NOT NULL DEFAULT '0' COMMENT '0=inactive, 1=active, 2=recharge',
  `create_user_id` int(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_user_id` int(10) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_ci_sessions`
--

CREATE TABLE `apninv_ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apninv_ci_sessions`
--

INSERT INTO `apninv_ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('58ef974b7112520558279f9190001cfa', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', 1487065189, 'a:3:{s:9:"site_lang";s:6:"bangla";s:10:"account_id";s:1:"1";s:10:"searchterm";s:54:"SELECT * FROM apninv_recharge_attempt WHERE is_block=1";}'),
('20304dad8f0a71954907fe01b8426e03', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', 1487065166, ''),
('c5f457477feed10170630cd9e2f672f1', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0', 1487065334, 'a:3:{s:9:"user_data";s:0:"";s:9:"site_lang";s:7:"english";s:10:"account_id";s:1:"5";}');

-- --------------------------------------------------------

--
-- Table structure for table `apninv_recharge_attempt`
--

CREATE TABLE `apninv_recharge_attempt` (
  `attempt_id` bigint(20) NOT NULL,
  `msisdn` varchar(11) NOT NULL,
  `attempt_count` int(11) DEFAULT NULL,
  `last_attempt_datetime` datetime DEFAULT NULL,
  `is_block` tinyint(4) DEFAULT '0' COMMENT '0=unblock, 1=block',
  `update_user_id` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `source` varchar(10) DEFAULT NULL,
  `unblock_reason` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_recharge_history`
--

CREATE TABLE `apninv_recharge_history` (
  `recharge_id` int(11) NOT NULL,
  `msisdn` varchar(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `recharge_datetime` datetime NOT NULL,
  `recharge_source` varchar(10) NOT NULL,
  `recharge_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_ref_country`
--

CREATE TABLE `apninv_ref_country` (
  `alpha2` char(2) NOT NULL,
  `alpha3` char(3) NOT NULL,
  `numeric` varchar(3) NOT NULL,
  `country` varchar(80) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `apninv_ref_country`
--

INSERT INTO `apninv_ref_country` (`alpha2`, `alpha3`, `numeric`, `country`) VALUES
('ao', 'ago', '024', 'Angola'),
('ai', 'aia', '660', 'Anguilla'),
('aq', 'ata', '010', 'Antarctica'),
('ag', 'atg', '028', 'Antigua and Barbuda'),
('ar', 'arg', '032', 'Argentina'),
('am', 'arm', '051', 'Armenia'),
('aw', 'abw', '533', 'Aruba'),
('au', 'aus', '036', 'Australia'),
('at', 'aut', '040', 'Austria'),
('az', 'aze', '031', 'Azerbaijan'),
('bs', 'bhs', '044', 'Bahamas'),
('bh', 'bhr', '048', 'Bahrain'),
('bd', 'bgd', '050', 'Bangladesh'),
('bb', 'brb', '052', 'Barbados'),
('by', 'blr', '112', 'Belarus'),
('be', 'bel', '056', 'Belgium'),
('bz', 'blz', '084', 'Belize'),
('bj', 'ben', '204', 'Benin'),
('bm', 'bmu', '060', 'Bermuda'),
('bt', 'btn', '064', 'Bhutan'),
('bo', 'bol', '068', 'Bolivia, Plurinational State of'),
('ba', 'bih', '070', 'Bosnia and Herzegovina'),
('bw', 'bwa', '072', 'Botswana'),
('bv', 'bvt', '074', 'Bouvet Island'),
('br', 'bra', '076', 'Brazil'),
('io', 'iot', '086', 'British Indian Ocean Territory'),
('bn', 'brn', '096', 'Brunei Darussalam'),
('bg', 'bgr', '100', 'Bulgaria'),
('bf', 'bfa', '854', 'Burkina Faso'),
('bi', 'bdi', '108', 'Burundi'),
('kh', 'khm', '116', 'Cambodia'),
('cm', 'cmr', '120', 'Cameroon'),
('ca', 'can', '124', 'Canada'),
('cv', 'cpv', '132', 'Cape Verde'),
('ky', 'cym', '136', 'Cayman Islands'),
('cf', 'caf', '140', 'Central African Republic'),
('td', 'tcd', '148', 'Chad'),
('cl', 'chl', '152', 'Chile'),
('cn', 'chn', '156', 'China'),
('cx', 'cxr', '162', 'Christmas Island'),
('cc', 'cck', '166', 'Cocos (Keeling) Islands'),
('co', 'col', '170', 'Colombia'),
('km', 'com', '174', 'Comoros'),
('cg', 'cog', '178', 'Congo'),
('cd', 'cod', '180', 'Congo, the Democratic Republic of the'),
('ck', 'cok', '184', 'Cook Islands'),
('cr', 'cri', '188', 'Costa Rica'),
('ci', 'civ', '384', 'Côte d\'Ivoire'),
('hr', 'hrv', '191', 'Croatia'),
('cu', 'cub', '192', 'Cuba'),
('cy', 'cyp', '196', 'Cyprus'),
('cz', 'cze', '203', 'Czech Republic'),
('dk', 'dnk', '208', 'Denmark'),
('dj', 'dji', '262', 'Djibouti'),
('dm', 'dma', '212', 'Dominica'),
('do', 'dom', '214', 'Dominican Republic'),
('ec', 'ecu', '218', 'Ecuador'),
('eg', 'egy', '818', 'Egypt'),
('sv', 'slv', '222', 'El Salvador'),
('gq', 'gnq', '226', 'Equatorial Guinea'),
('er', 'eri', '232', 'Eritrea'),
('ee', 'est', '233', 'Estonia'),
('et', 'eth', '231', 'Ethiopia'),
('fk', 'flk', '238', 'Falkland Islands (Malvinas)'),
('fo', 'fro', '234', 'Faroe Islands'),
('fj', 'fji', '242', 'Fiji'),
('fi', 'fin', '246', 'Finland'),
('fr', 'fra', '250', 'France'),
('gf', 'guf', '254', 'French Guiana'),
('pf', 'pyf', '258', 'French Polynesia'),
('tf', 'atf', '260', 'French Southern Territories'),
('ga', 'gab', '266', 'Gabon'),
('gm', 'gmb', '270', 'Gambia'),
('ge', 'geo', '268', 'Georgia'),
('de', 'deu', '276', 'Germany'),
('gh', 'gha', '288', 'Ghana'),
('gi', 'gib', '292', 'Gibraltar'),
('gr', 'grc', '300', 'Greece'),
('gl', 'grl', '304', 'Greenland'),
('gd', 'grd', '308', 'Grenada'),
('gp', 'glp', '312', 'Guadeloupe'),
('gu', 'gum', '316', 'Guam'),
('gt', 'gtm', '320', 'Guatemala'),
('gg', 'ggy', '831', 'Guernsey'),
('gn', 'gin', '324', 'Guinea'),
('gw', 'gnb', '624', 'Guinea-Bissau'),
('gy', 'guy', '328', 'Guyana'),
('ht', 'hti', '332', 'Haiti'),
('hm', 'hmd', '334', 'Heard Island and McDonald Islands'),
('va', 'vat', '336', 'Holy See (Vatican City State)'),
('hn', 'hnd', '340', 'Honduras'),
('hk', 'hkg', '344', 'Hong Kong'),
('hu', 'hun', '348', 'Hungary'),
('is', 'isl', '352', 'Iceland'),
('in', 'ind', '356', 'India'),
('id', 'idn', '360', 'Indonesia'),
('ir', 'irn', '364', 'Iran, Islamic Republic of'),
('iq', 'irq', '368', 'Iraq'),
('ie', 'irl', '372', 'Ireland'),
('im', 'imn', '833', 'Isle of Man'),
('il', 'isr', '376', 'Israel'),
('it', 'ita', '380', 'Italy'),
('jm', 'jam', '388', 'Jamaica'),
('jp', 'jpn', '392', 'Japan'),
('je', 'jey', '832', 'Jersey'),
('jo', 'jor', '400', 'Jordan'),
('kz', 'kaz', '398', 'Kazakhstan'),
('ke', 'ken', '404', 'Kenya'),
('ki', 'kir', '296', 'Kiribati'),
('kp', 'prk', '408', 'Korea, Democratic People\'s Republic of'),
('kr', 'kor', '410', 'Korea, Republic of'),
('kw', 'kwt', '414', 'Kuwait'),
('kg', 'kgz', '417', 'Kyrgyzstan'),
('la', 'lao', '418', 'Lao People\'s Democratic Republic'),
('lv', 'lva', '428', 'Latvia'),
('lb', 'lbn', '422', 'Lebanon'),
('ls', 'lso', '426', 'Lesotho'),
('lr', 'lbr', '430', 'Liberia'),
('ly', 'lby', '434', 'Libyan Arab Jamahiriya'),
('li', 'lie', '438', 'Liechtenstein'),
('lt', 'ltu', '440', 'Lithuania'),
('lu', 'lux', '442', 'Luxembourg'),
('mo', 'mac', '446', 'Macao'),
('mk', 'mkd', '807', 'Macedonia, the former Yugoslav Republic of'),
('mg', 'mdg', '450', 'Madagascar'),
('mw', 'mwi', '454', 'Malawi'),
('my', 'mys', '458', 'Malaysia'),
('mv', 'mdv', '462', 'Maldives'),
('ml', 'mli', '466', 'Mali'),
('mt', 'mlt', '470', 'Malta'),
('mh', 'mhl', '584', 'Marshall Islands'),
('mq', 'mtq', '474', 'Martinique'),
('mr', 'mrt', '478', 'Mauritania'),
('mu', 'mus', '480', 'Mauritius'),
('yt', 'myt', '175', 'Mayotte'),
('mx', 'mex', '484', 'Mexico'),
('fm', 'fsm', '583', 'Micronesia, Federated States of'),
('md', 'mda', '498', 'Moldova, Republic of'),
('mc', 'mco', '492', 'Monaco'),
('mn', 'mng', '496', 'Mongolia'),
('me', 'mne', '499', 'Montenegro'),
('ms', 'msr', '500', 'Montserrat'),
('ma', 'mar', '504', 'Morocco'),
('mz', 'moz', '508', 'Mozambique'),
('mm', 'mmr', '104', 'Myanmar'),
('na', 'nam', '516', 'Namibia'),
('nr', 'nru', '520', 'Nauru'),
('np', 'npl', '524', 'Nepal'),
('nl', 'nld', '528', 'Netherlands'),
('an', 'ant', '530', 'Netherlands Antilles'),
('nc', 'ncl', '540', 'New Caledonia'),
('nz', 'nzl', '554', 'New Zealand'),
('ni', 'nic', '558', 'Nicaragua'),
('ne', 'ner', '562', 'Niger'),
('ng', 'nga', '566', 'Nigeria'),
('nu', 'niu', '570', 'Niue'),
('nf', 'nfk', '574', 'Norfolk Island'),
('mp', 'mnp', '580', 'Northern Mariana Islands'),
('no', 'nor', '578', 'Norway'),
('om', 'omn', '512', 'Oman'),
('pk', 'pak', '586', 'Pakistan'),
('pw', 'plw', '585', 'Palau'),
('ps', 'pse', '275', 'Palestinian Territory, Occupied'),
('pa', 'pan', '591', 'Panama'),
('pg', 'png', '598', 'Papua New Guinea'),
('py', 'pry', '600', 'Paraguay'),
('pe', 'per', '604', 'Peru'),
('ph', 'phl', '608', 'Philippines'),
('pn', 'pcn', '612', 'Pitcairn'),
('pl', 'pol', '616', 'Poland'),
('pt', 'prt', '620', 'Portugal'),
('pr', 'pri', '630', 'Puerto Rico'),
('qa', 'qat', '634', 'Qatar'),
('re', 'reu', '638', 'Réunion'),
('ro', 'rou', '642', 'Romania'),
('ru', 'rus', '643', 'Russian Federation'),
('rw', 'rwa', '646', 'Rwanda'),
('bl', 'blm', '652', 'Saint Barthélemy'),
('sh', 'shn', '654', 'Saint Helena'),
('kn', 'kna', '659', 'Saint Kitts and Nevis'),
('lc', 'lca', '662', 'Saint Lucia'),
('mf', 'maf', '663', 'Saint Martin (French part)'),
('pm', 'spm', '666', 'Saint Pierre and Miquelon'),
('vc', 'vct', '670', 'Saint Vincent and the Grenadines'),
('ws', 'wsm', '882', 'Samoa'),
('sm', 'smr', '674', 'San Marino'),
('st', 'stp', '678', 'Sao Tome and Principe'),
('sa', 'sau', '682', 'Saudi Arabia'),
('sn', 'sen', '686', 'Senegal'),
('rs', 'srb', '688', 'Serbia'),
('sc', 'syc', '690', 'Seychelles'),
('sl', 'sle', '694', 'Sierra Leone'),
('sg', 'sgp', '702', 'Singapore'),
('sk', 'svk', '703', 'Slovakia'),
('si', 'svn', '705', 'Slovenia'),
('sb', 'slb', '090', 'Solomon Islands'),
('so', 'som', '706', 'Somalia'),
('za', 'zaf', '710', 'South Africa'),
('gs', 'sgs', '239', 'South Georgia and the South Sandwich Islands'),
('es', 'esp', '724', 'Spain'),
('lk', 'lka', '144', 'Sri Lanka'),
('sd', 'sdn', '736', 'Sudan'),
('sr', 'sur', '740', 'Suriname'),
('sj', 'sjm', '744', 'Svalbard and Jan Mayen'),
('sz', 'swz', '748', 'Swaziland'),
('se', 'swe', '752', 'Sweden'),
('ch', 'che', '756', 'Switzerland'),
('sy', 'syr', '760', 'Syrian Arab Republic'),
('tw', 'twn', '158', 'Taiwan, Province of China'),
('tj', 'tjk', '762', 'Tajikistan'),
('tz', 'tza', '834', 'Tanzania, United Republic of'),
('th', 'tha', '764', 'Thailand'),
('tl', 'tls', '626', 'Timor-Leste'),
('tg', 'tgo', '768', 'Togo'),
('tk', 'tkl', '772', 'Tokelau'),
('to', 'ton', '776', 'Tonga'),
('tt', 'tto', '780', 'Trinidad and Tobago'),
('tn', 'tun', '788', 'Tunisia'),
('tr', 'tur', '792', 'Turkey'),
('tm', 'tkm', '795', 'Turkmenistan'),
('tc', 'tca', '796', 'Turks and Caicos Islands'),
('tv', 'tuv', '798', 'Tuvalu'),
('ug', 'uga', '800', 'Uganda'),
('ua', 'ukr', '804', 'Ukraine'),
('ae', 'are', '784', 'United Arab Emirates'),
('gb', 'gbr', '826', 'United Kingdom'),
('us', 'usa', '840', 'United States'),
('um', 'umi', '581', 'United States Minor Outlying Islands'),
('uy', 'ury', '858', 'Uruguay'),
('uz', 'uzb', '860', 'Uzbekistan'),
('vu', 'vut', '548', 'Vanuatu'),
('ve', 'ven', '862', 'Venezuela, Bolivarian Republic of'),
('vn', 'vnm', '704', 'Viet Nam'),
('vg', 'vgb', '092', 'Virgin Islands, British'),
('vi', 'vir', '850', 'Virgin Islands, U.S.'),
('wf', 'wlf', '876', 'Wallis and Futuna'),
('eh', 'esh', '732', 'Western Sahara'),
('ye', 'yem', '887', 'Yemen'),
('zm', 'zmb', '894', 'Zambia'),
('zw', 'zwe', '716', 'Zimbabwe'),
('af', 'afg', '004', 'Afghanistan'),
('ax', 'ala', '248', 'Åland Islands'),
('al', 'alb', '008', 'Albania'),
('dz', 'dza', '012', 'Algeria'),
('as', 'asm', '016', 'American Samoa'),
('ad', 'and', '020', 'Andorra');

-- --------------------------------------------------------

--
-- Table structure for table `apninv_ref_currency`
--

CREATE TABLE `apninv_ref_currency` (
  `alpha` char(3) NOT NULL,
  `numeric` varchar(3) DEFAULT NULL,
  `currency` varchar(80) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `apninv_ref_currency`
--

INSERT INTO `apninv_ref_currency` (`alpha`, `numeric`, `currency`) VALUES
('AFN', '971', 'Afghani'),
('DZD', '12', 'Algerian Dinar'),
('ARS', '32', 'Argentine Peso'),
('AMD', '51', 'Armenian Dram'),
('AWG', '533', 'Aruban Guilder'),
('AUD', '36', 'Australian Dollar'),
('AZN', '944', 'Azerbaijanian Manat'),
('BSD', '44', 'Bahamian Dollar'),
('BHD', '48', 'Bahraini Dinar'),
('THB', '764', 'Baht'),
('PAB', '590', 'Balboa'),
('BBD', '52', 'Barbados Dollar'),
('BYR', '974', 'Belarussian Ruble'),
('BZD', '84', 'Belize Dollar'),
('BMD', '60', 'Bermudian Dollar (customarily known as Bermuda Dollar)'),
('VEF', '937', 'Bolivar Fuerte'),
('BOB', '68', 'Boliviano'),
('XBA', '955', 'Bond Markets Units European Composite Unit (EURCO)'),
('BRL', '986', 'Brazilian Real'),
('BND', '96', 'Brunei Dollar'),
('BGN', '975', 'Bulgarian Lev'),
('BIF', '108', 'Burundi Franc'),
('CAD', '124', 'Canadian Dollar'),
('CVE', '132', 'Cape Verde Escudo'),
('KYD', '136', 'Cayman Islands Dollar'),
('GHS', '936', 'Cedi'),
('XOF', '952', 'CFA Franc BCEAO'),
('XAF', '950', 'CFA Franc BEAC'),
('XPF', '953', 'CFP Franc'),
('CLP', '152', 'Chilean Peso'),
('XTS', '963', 'Codes specifically reserved for testing purposes'),
('COP', '170', 'Colombian Peso'),
('KMF', '174', 'Comoro Franc'),
('CDF', '976', 'Congolese Franc'),
('BAM', '977', 'Convertible Marks'),
('NIO', '558', 'Cordoba Oro'),
('CRC', '188', 'Costa Rican Colon'),
('HRK', '191', 'Croatian Kuna'),
('CUP', '192', 'Cuban Peso'),
('CZK', '203', 'Czech Koruna'),
('GMD', '270', 'Dalasi'),
('DKK', '208', 'Danish Krone'),
('MKD', '807', 'Denar'),
('DJF', '262', 'Djibouti Franc'),
('STD', '678', 'Dobra'),
('DOP', '214', 'Dominican Peso'),
('VND', '704', 'Dong'),
('XCD', '951', 'East Caribbean Dollar'),
('EGP', '818', 'Egyptian Pound'),
('SVC', '222', 'El Salvador Colon'),
('ETB', '230', 'Ethiopian Birr'),
('EUR', '978', 'Euro'),
('XBB', '956', 'European Monetary Unit (E.M.U.-6)'),
('XBD', '958', 'European Unit of Account 17 (E.U.A.-17)'),
('XBC', '957', 'European Unit of Account 9 (E.U.A.-9)'),
('FKP', '238', 'Falkland Islands Pound'),
('FJD', '242', 'Fiji Dollar'),
('HUF', '348', 'Forint'),
('GIP', '292', 'Gibraltar Pound'),
('XAU', '959', 'Gold'),
('HTG', '332', 'Gourde'),
('PYG', '600', 'Guarani'),
('GNF', '324', 'Guinea Franc'),
('GWP', '624', 'Guinea-Bissau Peso'),
('GYD', '328', 'Guyana Dollar'),
('HKD', '344', 'Hong Kong Dollar'),
('UAH', '980', 'Hryvnia'),
('ISK', '352', 'Iceland Krona'),
('INR', '356', 'Indian Rupee'),
('IRR', '364', 'Iranian Rial'),
('IQD', '368', 'Iraqi Dinar'),
('JMD', '388', 'Jamaican Dollar'),
('JOD', '400', 'Jordanian Dinar'),
('KES', '404', 'Kenyan Shilling'),
('PGK', '598', 'Kina'),
('LAK', '418', 'Kip'),
('EEK', '233', 'Kroon'),
('KWD', '414', 'Kuwaiti Dinar'),
('MWK', '454', 'Kwacha'),
('AOA', '973', 'Kwanza'),
('MMK', '104', 'Kyat'),
('GEL', '981', 'Lari'),
('LVL', '428', 'Latvian Lats'),
('LBP', '422', 'Lebanese Pound'),
('ALL', '8', 'Lek'),
('HNL', '340', 'Lempira'),
('SLL', '694', 'Leone'),
('LRD', '430', 'Liberian Dollar'),
('LYD', '434', 'Libyan Dinar'),
('SZL', '748', 'Lilangeni'),
('LTL', '440', 'Lithuanian Litas'),
('LSL', '426', 'Loti'),
('MGA', '969', 'Malagasy Ariary'),
('MYR', '458', 'Malaysian Ringgit'),
('TMT', '934', 'Manat'),
('MUR', '480', 'Mauritius Rupee'),
('MZN', '943', 'Metical'),
('MXN', '484', 'Mexican Peso'),
('MXV', '979', 'Mexican Unidad de Inversion (UDI)'),
('MDL', '498', 'Moldovan Leu'),
('MAD', '504', 'Moroccan Dirham'),
('BOV', '984', 'Mvdol'),
('NGN', '566', 'Naira'),
('ERN', '232', 'Nakfa'),
('NAD', '516', 'Namibia Dollar'),
('NPR', '524', 'Nepalese Rupee'),
('ANG', '532', 'Netherlands Antillian Guilder'),
('ILS', '376', 'New Israeli Sheqel'),
('RON', '946', 'New Leu'),
('TWD', '901', 'New Taiwan Dollar'),
('NZD', '554', 'New Zealand Dollar'),
('BTN', '64', 'Ngultrum'),
('KPW', '408', 'North Korean Won'),
('NOK', '578', 'Norwegian Krone'),
('PEN', '604', 'Nuevo Sol'),
('MRO', '478', 'Ouguiya'),
('TOP', '776', 'Pa\'anga'),
('PKR', '586', 'Pakistan Rupee'),
('XPD', '964', 'Palladium'),
('MOP', '446', 'Pataca'),
('CUC', '931', 'Peso Convertible'),
('UYU', '858', 'Peso Uruguayo'),
('PHP', '608', 'Philippine Peso'),
('XPT', '962', 'Platinum'),
('GBP', '826', 'Pound Sterling'),
('BWP', '72', 'Pula'),
('QAR', '634', 'Qatari Rial'),
('GTQ', '320', 'Quetzal'),
('ZAR', '710', 'Rand'),
('OMR', '512', 'Rial Omani'),
('KHR', '116', 'Riel'),
('MVR', '462', 'Rufiyaa'),
('IDR', '360', 'Rupiah'),
('RUB', '643', 'Russian Ruble'),
('RWF', '646', 'Rwanda Franc'),
('SHP', '654', 'Saint Helena Pound'),
('SAR', '682', 'Saudi Riyal'),
('XDR', '960', 'SDR'),
('RSD', '941', 'Serbian Dinar'),
('SCR', '690', 'Seychelles Rupee'),
('XAG', '961', 'Silver'),
('SGD', '702', 'Singapore Dollar'),
('SBD', '90', 'Solomon Islands Dollar'),
('KGS', '417', 'Som'),
('SOS', '706', 'Somali Shilling'),
('TJS', '972', 'Somoni'),
('LKR', '144', 'Sri Lanka Rupee'),
('SDG', '938', 'Sudanese Pound'),
('SRD', '968', 'Surinam Dollar'),
('SEK', '752', 'Swedish Krona'),
('CHF', '756', 'Swiss Franc'),
('SYP', '760', 'Syrian Pound'),
('BDT', '50', 'Taka'),
('WST', '882', 'Tala'),
('TZS', '834', 'Tanzanian Shilling'),
('KZT', '398', 'Tenge'),
('XXX', '999', 'Codes assigned for transactions where no currency is involved'),
('TTD', '780', 'Trinidad and Tobago Dollar'),
('MNT', '496', 'Tugrik'),
('TND', '788', 'Tunisian Dinar'),
('TRY', '949', 'Turkish Lira'),
('AED', '784', 'UAE Dirham'),
('UGX', '800', 'Uganda Shilling'),
('XFU', NULL, 'UIC-Franc'),
('COU', '970', 'Unidad de Valor Real'),
('CLF', '990', 'Unidades de fomento'),
('UYI', '940', 'Uruguay Peso en Unidades Indexadas'),
('USD', '840', 'US Dollar'),
('USN', '997', 'US Dollar (Next day)'),
('USS', '998', 'US Dollar (Same day)'),
('UZS', '860', 'Uzbekistan Sum'),
('VUV', '548', 'Vatu'),
('CHE', '947', 'WIR Euro'),
('CHW', '948', 'WIR Franc'),
('KRW', '410', 'Won'),
('YER', '886', 'Yemeni Rial'),
('JPY', '392', 'Yen'),
('CNY', '156', 'Yuan Renminbi'),
('ZMK', '894', 'Zambian Kwacha'),
('ZWL', '932', 'Zimbabwe Dollar'),
('PLN', '985', 'Zloty');

-- --------------------------------------------------------

--
-- Table structure for table `apninv_ref_iptocountry`
--

CREATE TABLE `apninv_ref_iptocountry` (
  `ip_from` int(10) UNSIGNED NOT NULL,
  `ip_to` int(10) UNSIGNED NOT NULL,
  `country_code` char(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `apninv_ref_language`
--

CREATE TABLE `apninv_ref_language` (
  `one` char(2) NOT NULL,
  `two` char(3) NOT NULL,
  `language` varchar(120) NOT NULL,
  `native` varchar(80) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `apninv_ref_language`
--

INSERT INTO `apninv_ref_language` (`one`, `two`, `language`, `native`) VALUES
('ab', 'abk', 'Abkhazian', 'Аҧсуа'),
('aa', 'aar', 'Afar', 'Afaraf'),
('af', 'afr', 'Afrikaans', 'Afrikaans'),
('ak', 'aka', 'Akan', 'Akan'),
('sq', 'sqi', 'Albanian', 'Shqip'),
('am', 'amh', 'Amharic', NULL),
('ar', 'ara', 'Arabic', 'العربية'),
('an', 'arg', 'Aragonese', 'Aragonés'),
('hy', 'hye', 'Armenian', 'Հայերեն'),
('as', 'asm', 'Assamese', NULL),
('av', 'ava', 'Avaric', 'авар мацӀ, магӀарул мацӀ'),
('ae', 'ave', 'Avestan', 'avesta'),
('ay', 'aym', 'Aymara', 'aymar aru'),
('az', 'aze', 'Azerbaijani', 'azərbaycan dili'),
('bm', 'bam', 'Bambara', 'bamanankan'),
('ba', 'bak', 'Bashkir', 'башҡорт теле'),
('eu', 'eus', 'Basque', 'euskara, euskera'),
('be', 'bel', 'Belarusian', 'Беларуская'),
('bn', 'ben', 'Bengali', NULL),
('bh', 'bih', 'Bihari', 'भोजपुरी'),
('bi', 'bis', 'Bislama', 'Bislama'),
('bs', 'bos', 'Bosnian', 'bosanski jezik'),
('br', 'bre', 'Breton', 'brezhoneg'),
('bg', 'bul', 'Bulgarian', 'български език'),
('my', 'mya', 'Burmese', NULL),
('ca', 'cat', 'Catalan, Valencian', 'Català'),
('km', 'khm', 'Central Khmer', NULL),
('ch', 'cha', 'Chamorro', 'Chamoru'),
('ce', 'che', 'Chechen', 'нохчийн мотт'),
('ny', 'nya', 'Chichewa, Chewa, Nyanja', 'chiCheŵa, chinyanja'),
('zh', 'zho', 'Chinese', '中文 (Zhōngwén), 汉语, 漢語'),
('cu', 'chu', 'Old Church Slavonic, Old Bulgarian', 'ѩзыкъ словѣньскъ'),
('cv', 'chv', 'Chuvash', 'чӑваш чӗлхи'),
('kw', 'cor', 'Cornish', 'Kernewek'),
('co', 'cos', 'Corsican', 'corsu, lingua corsa'),
('cr', 'cre', 'Cree', NULL),
('hr', 'hrv', 'Croatian', 'hrvatski'),
('cs', 'ces', 'Czech', 'česky, čeština'),
('da', 'dan', 'Danish', 'dansk'),
('dv', 'div', 'Divehi, Dhivehi, Maldivian', 'ދިވެހި'),
('nl', 'nld', 'Dutch, Flemish', 'Nederlands, Vlaams'),
('dz', 'dzo', 'Dzongkha', NULL),
('en', 'eng', 'English', 'English'),
('eo', 'epo', 'Esperanto', 'Esperanto'),
('et', 'est', 'Estonian', 'eesti, eesti keel'),
('ee', 'ewe', 'Ewe', 'Eʋegbe'),
('fo', 'fao', 'Faroese', 'føroyskt'),
('fj', 'fij', 'Fijian', 'vosa Vakaviti'),
('fi', 'fin', 'Finnish', 'suomi, suomen kieli'),
('fr', 'fra', 'French', 'français, langue française'),
('ff', 'ful', 'Fulah', 'Fulfulde, Pulaar, Pular'),
('gd', 'gla', 'Gaelic, Scottish Gaelic', 'Gàidhlig'),
('gl', 'glg', 'Galician', 'Galego'),
('lg', 'lug', 'Ganda', 'Luganda'),
('ka', 'kat', 'Georgian', 'ქართული'),
('de', 'deu', 'German', 'Deutsch'),
('gn', 'grn', 'Guaraní', 'Avañe\'ẽ'),
('gu', 'guj', 'Gujarati', 'ગુજરાતી'),
('ht', 'hat', 'Haitian, Haitian Creole', 'Kreyòl ayisyen'),
('ha', 'hau', 'Hausa', 'Hausa, هَوُسَ'),
('hz', 'her', 'Herero', 'Otjiherero'),
('hi', 'hin', 'Hindi', 'हिन्दी, हिंदी'),
('ho', 'hmo', 'Hiri Motu', 'Hiri Motu'),
('hu', 'hun', 'Hungarian', 'Magyar'),
('is', 'isl', 'Icelandic', 'Íslenska'),
('io', 'ido', 'Ido', 'Ido'),
('ig', 'ibo', 'Igbo', 'Igbo'),
('id', 'ind', 'Indonesian', 'Bahasa Indonesia'),
('ia', 'ina', 'Interlingua (IALA)', 'Interlingua'),
('ie', 'ile', 'Interlingue, Occidental', 'Interlingue'),
('iu', 'iku', 'Inuktitut', NULL),
('ik', 'ipk', 'Inupiaq', 'Iñupiaq, Iñupiatun'),
('ga', 'gle', 'Irish', 'Gaeilge'),
('it', 'ita', 'Italian', 'Italiano'),
('ja', 'jpn', 'Japanese', '日本語 (にほんご／にっぽんご)'),
('jv', 'jav', 'Javanese', 'basa Jawa'),
('kl', 'kal', 'Kalaallisut, Greenlandic', 'kalaallisut, kalaallit oqaasii'),
('kn', 'kan', 'Kannada', 'ಕನ್ನಡ'),
('kr', 'kau', 'Kanuri', 'Kanuri'),
('ks', 'kas', 'Kashmiri', 'कश्मीरी, كشميري‎'),
('kk', 'kaz', 'Kazakh', 'Қазақ тілі'),
('ki', 'kik', 'Kikuyu, Gikuyu', 'Gĩkũyũ'),
('rw', 'kin', 'Kinyarwanda', 'Ikinyarwanda'),
('ky', 'kir', 'Kirghiz, Kyrgyz', 'кыргыз тили'),
('kv', 'kom', 'Komi', 'коми кыв'),
('kg', 'kon', 'Kongo', 'KiKongo'),
('ko', 'kor', 'Korean', '한국어 (韓國語), 조선말 (朝鮮語)'),
('ku', 'kur', 'Kurdish', 'Kurdî, كوردی‎'),
('kj', 'kua', 'Kwanyama, Kuanyama', 'Kuanyama'),
('lo', 'lao', 'Lao', NULL),
('la', 'lat', 'Latin', 'latine, lingua latina'),
('lv', 'lav', 'Latvian', 'latviešu valoda'),
('li', 'lim', 'Limburgish, Limburgan, Limburger', 'Limburgs'),
('ln', 'lin', 'Lingala', 'Lingála'),
('lt', 'lit', 'Lithuanian', 'lietuvių kalba'),
('lu', 'lub', 'Luba-Katanga', NULL),
('lb', 'ltz', 'Luxembourgish, Letzeburgesch', 'Lëtzebuergesch'),
('mk', 'mkd', 'Macedonian', 'македонски јазик'),
('mg', 'mlg', 'Malagasy', 'Malagasy fiteny'),
('ms', 'msa', 'Malay', 'bahasa Melayu, بهاس ملايو‎'),
('ml', 'mal', 'Malayalam', NULL),
('mt', 'mlt', 'Maltese', 'Malti'),
('gv', 'glv', 'Manx', 'Gaelg, Gailck'),
('mi', 'mri', 'Māori', 'te reo Māori'),
('mr', 'mar', 'Marathi', 'मराठी'),
('mh', 'mah', 'Marshallese', 'Kajin M̧ajeļ'),
('el', 'ell', 'Modern Greek', 'Ελληνικά'),
('he', 'heb', 'Modern Hebrew', 'עברית'),
('mn', 'mon', 'Mongolian', 'Монгол'),
('na', 'nau', 'Nauru', 'Ekakairũ Naoero'),
('nv', 'nav', 'Navajo, Navaho', 'Diné bizaad, Dinékʼehǰí'),
('ng', 'ndo', 'Ndonga', 'Owambo'),
('ne', 'nep', 'Nepali', 'नेपाली'),
('nd', 'nde', 'North Ndebele', 'isiNdebele'),
('se', 'sme', 'Northern Sami', 'Davvisámegiella'),
('no', 'nor', 'Norwegian', 'Norsk'),
('nb', 'nob', 'Norwegian Bokmål', 'Norsk bokmål'),
('nn', 'nno', 'Norwegian Nynorsk', 'Norsk nynorsk'),
('oc', 'oci', 'Occitan (after 1500)', 'Occitan'),
('oj', 'oji', 'Ojibwa', NULL),
('or', 'ori', 'Oriya', NULL),
('om', 'orm', 'Oromo', 'Afaan Oromoo'),
('os', 'oss', 'Ossetian, Ossetic', 'Ирон æвзаг'),
('pi', 'pli', 'Pāli', 'पाऴि'),
('pa', 'pan', 'Panjabi, Punjabi', 'ਪੰਜਾਬੀ, پنجابی‎'),
('ps', 'pus', 'Pashto, Pushto', 'پښتو'),
('fa', 'fas', 'Persian', 'فارسی'),
('pl', 'pol', 'Polish', 'polski'),
('pt', 'por', 'Portuguese', 'Português'),
('qu', 'que', 'Quechua', 'Runa Simi, Kichwa'),
('ro', 'ron', 'Romanian, Moldavian, Moldovan', 'română'),
('rm', 'roh', 'Romansh', 'rumantsch grischun'),
('rn', 'run', 'Rundi', 'kiRundi'),
('ru', 'rus', 'Russian', 'Русский язык'),
('sm', 'smo', 'Samoan', 'gagana fa\'a Samoa'),
('sg', 'sag', 'Sango', 'yângâ tî sängö'),
('sa', 'san', 'Sanskrit', 'संस्कृतम्'),
('sc', 'srd', 'Sardinian', 'sardu'),
('sr', 'srp', 'Serbian', 'српски језик'),
('sn', 'sna', 'Shona', 'chiShona'),
('ii', 'iii', 'Sichuan Yi, Nuosu', NULL),
('sd', 'snd', 'Sindhi', 'सिन्धी, سنڌي، سندھی‎'),
('si', 'sin', 'Sinhala, Sinhalese', NULL),
('sk', 'slk', 'Slovak', 'slovenčina'),
('sl', 'slv', 'Slovene', 'slovenščina'),
('so', 'som', 'Somali', 'Soomaaliga, af Soomaali'),
('nr', 'nbl', 'South Ndebele', 'isiNdebele'),
('st', 'sot', 'Southern Sotho', 'Sesotho'),
('es', 'spa', 'Spanish, Castilian', 'español, castellano'),
('su', 'sun', 'Sundanese', 'Basa Sunda'),
('sw', 'swa', 'Swahili', 'Kiswahili'),
('ss', 'ssw', 'Swati', 'SiSwati'),
('sv', 'swe', 'Swedish', 'svenska'),
('tl', 'tgl', 'Tagalog', 'Wikang Tagalog'),
('ty', 'tah', 'Tahitian', 'Reo Mā`ohi'),
('tg', 'tgk', 'Tajik', 'тоҷикӣ, toğikī, تاجیکی‎'),
('ta', 'tam', 'Tamil', 'தமிழ்'),
('tt', 'tat', 'Tatar', 'татарча, tatarça, تاتارچا‎'),
('te', 'tel', 'Telugu', 'తెలుగు'),
('th', 'tha', 'Thai', 'ไทย'),
('bo', 'bod', 'Tibetan', NULL),
('ti', 'tir', 'Tigrinya', NULL),
('to', 'ton', 'Tonga (Tonga Islands)', 'faka Tonga'),
('ts', 'tso', 'Tsonga', 'Xitsonga'),
('tn', 'tsn', 'Tswana', 'Setswana'),
('tr', 'tur', 'Turkish', 'Türkçe'),
('tk', 'tuk', 'Turkmen', 'Türkmen, Түркмен'),
('tw', 'twi', 'Twi', 'Twi'),
('ug', 'uig', 'Uighur, Uyghur', 'Uyƣurqə, ئۇيغۇرچە‎'),
('uk', 'ukr', 'Ukrainian', 'Українська'),
('ur', 'urd', 'Urdu', 'اردو'),
('uz', 'uzb', 'Uzbek', 'O\'zbek, Ўзбек, أۇزبېك‎'),
('ve', 'ven', 'Venda', 'Tshivenḓa'),
('vi', 'vie', 'Vietnamese', 'Tiếng Việt'),
('vo', 'vol', 'Volapük', 'Volapük'),
('wa', 'wln', 'Walloon', 'Walon'),
('cy', 'cym', 'Welsh', 'Cymraeg'),
('fy', 'fry', 'Western Frisian', 'Frysk'),
('wo', 'wol', 'Wolof', 'Wollof'),
('xh', 'xho', 'Xhosa', 'isiXhosa'),
('yi', 'yid', 'Yiddish', 'ייִדיש'),
('yo', 'yor', 'Yoruba', 'Yorùbá'),
('za', 'zha', 'Zhuang, Chuang', 'Saɯ cueŋƅ, Saw cuengh'),
('zu', 'zul', 'Zulu', 'isiZulu');

-- --------------------------------------------------------

--
-- Table structure for table `apninv_ref_zoneinfo`
--

CREATE TABLE `apninv_ref_zoneinfo` (
  `zoneinfo` varchar(40) NOT NULL,
  `offset` varchar(16) DEFAULT NULL,
  `summer` varchar(16) DEFAULT NULL,
  `country` char(2) NOT NULL,
  `cicode` varchar(6) NOT NULL,
  `cicodesummer` varchar(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `apninv_ref_zoneinfo`
--

INSERT INTO `apninv_ref_zoneinfo` (`zoneinfo`, `offset`, `summer`, `country`, `cicode`, `cicodesummer`) VALUES
('Europe/Andorra', 'UTC+1', 'UTC+2', 'ad', 'UP1', 'UP2'),
('Asia/Dubai', 'UTC+4', NULL, 'ae', 'UP4', NULL),
('Asia/Kabul', 'UTC+4:30', NULL, 'af', 'UP45', NULL),
('America/Antigua', 'UTC-4', NULL, 'ag', 'UM4', NULL),
('America/Anguilla', 'UTC-4', NULL, 'ai', 'UM4', NULL),
('Europe/Tirane', 'UTC+1', 'UTC+2', 'al', 'UP1', 'UP2'),
('Asia/Yerevan', 'UTC+4', 'UTC+5', 'am', 'UP4', 'UP5'),
('America/Curacao', 'UTC-4', NULL, 'an', 'UM4', NULL),
('Africa/Luanda', 'UTC+1', NULL, 'ao', 'UP1', NULL),
('Antarctica/McMurdo', 'UTC+12', 'UTC+13', 'aq', 'UP12', 'UP13'),
('Antarctica/South_Pole', 'UTC+12', 'UTC+13', 'aq', 'UP12', 'UP13'),
('Antarctica/Rothera', 'UTC-3', NULL, 'aq', 'UM3', NULL),
('Antarctica/Palmer', 'UTC-4', 'UTC-3', 'aq', 'UM4', 'UM3'),
('Antarctica/Mawson', 'UTC+6', NULL, 'aq', 'UP6', NULL),
('Antarctica/Davis', 'UTC+7', NULL, 'aq', 'UP7', NULL),
('Antarctica/Casey', 'UTC+8', NULL, 'aq', 'UP8', NULL),
('Antarctica/Vostok', NULL, NULL, 'aq', 'UTC', NULL),
('Antarctica/DumontDUrville', 'UTC+10', NULL, 'aq', 'UP10', NULL),
('Antarctica/Syowa', 'UTC+3', NULL, 'aq', 'UP3', NULL),
('America/Argentina/Buenos_Aires', 'UTC-3', 'UTC-2', 'ar', 'UM3', 'UM2'),
('America/Argentina/Cordoba', 'UTC-3', 'UTC-2', 'ar', 'UM3', 'UM2'),
('America/Argentina/Salta', 'UTC-3', NULL, 'ar', 'UM3', NULL),
('America/Argentina/Jujuy', 'UTC-3', NULL, 'ar', 'UM3', NULL),
('America/Argentina/Tucuman', 'UTC-3', 'UTC-2', 'ar', 'UM3', NULL),
('America/Argentina/Catamarca', 'UTC-3', NULL, 'ar', 'UM3', NULL),
('America/Argentina/La_Rioja', 'UTC-3', NULL, 'ar', 'UM3', NULL),
('America/Argentina/San_Juan', 'UTC-3', NULL, 'ar', 'UM3', NULL),
('America/Argentina/Mendoza', 'UTC-3', NULL, 'ar', 'UM3', NULL),
('America/Argentina/San_Luis', 'UTC-4', 'UTC-3', 'ar', 'UM4', 'UM3'),
('America/Argentina/Rio_Gallegos', 'UTC-3', NULL, 'ar', 'UM3', NULL),
('America/Argentina/Ushuaia', 'UTC-3', NULL, 'ar', 'UM3', NULL),
('Pacific/Pago_Pago', 'UTC-11', NULL, 'as', 'UM11', NULL),
('Europe/Vienna', 'UTC+1', 'UTC+2', 'at', 'UP1', 'UP2'),
('Australia/Lord_Howe', 'UTC+10:30', 'UTC+11', 'au', 'UP105', 'UP11'),
('Australia/Hobart', 'UTC+10', 'UTC+11', 'au', 'UP10', 'UP11'),
('Australia/Currie', 'UTC+10', 'UTC+11', 'au', 'UP10', 'UP11'),
('Australia/Melbourne', 'UTC+10', 'UTC+11', 'au', 'UP10', 'UP11'),
('Australia/Sydney', 'UTC+10', 'UTC+11', 'au', 'UP10', 'UP11'),
('Australia/Broken_Hill', 'UTC+9:30', 'UTC+10:30', 'au', 'UP95', 'UP105'),
('Australia/Brisbane', 'UTC+10', NULL, 'au', 'UP10', NULL),
('Australia/Lindeman', 'UTC+10', NULL, 'au', 'UP10', NULL),
('Australia/Adelaide', 'UTC+9:30', 'UTC+10:30', 'au', 'UP95', 'UP105'),
('Australia/Darwin', 'UTC+9:30', NULL, 'au', 'UP95', NULL),
('Australia/Perth', 'UTC+8', NULL, 'au', 'UP8', NULL),
('Australia/Eucla', 'UTC+8:45', 'UTC+9:45', 'au', 'UP875', 'UP975'),
('America/Aruba', 'UTC-4', NULL, 'aw', 'UM4', NULL),
('Europe/Mariehamn', 'UTC+2', 'UTC+3', 'ax', 'UP2', 'UP3'),
('Asia/Baku', 'UTC+4', 'UTC+5', 'az', 'UP4', 'UP5'),
('Europe/Sarajevo', 'UTC+1', 'UTC+2', 'ba', 'UP1', 'UP2'),
('America/Barbados', 'UTC-4', NULL, 'bb', 'UP4', NULL),
('Asia/Dhaka', 'UTC+6', NULL, 'bd', 'UP6', NULL),
('Europe/Brussels', 'UTC+1', 'UTC+2', 'be', 'UP1', 'UP2'),
('Africa/Ouagadougou', 'UTC', NULL, 'bf', 'UTC', NULL),
('Europe/Sofia', 'UTC+2', 'UTC+3', 'bg', 'UP2', NULL),
('Asia/Bahrain', 'UTC+3', NULL, 'bh', 'UP3', NULL),
('Africa/Bujumbura', 'UTC+2', NULL, 'bi', 'UP2', NULL),
('Africa/Porto-Novo', 'UTC+1', NULL, 'bj', 'UP1', NULL),
('America/St_Barthelemy', 'UTC-4', NULL, 'bl', 'UM4', NULL),
('Atlantic/Bermuda', 'UTC-4', NULL, 'bm', 'UM4', NULL),
('Asia/Brunei', 'UTC+8', NULL, 'bn', 'UP8', NULL),
('America/La_Paz', 'UTC-4', NULL, 'bo', 'UM4', NULL),
('America/Noronha', 'UTC-2', NULL, 'br', 'UM2', NULL),
('America/Belem', 'UTC-3', NULL, 'br', 'UM3', NULL),
('America/Fortaleza', 'UTC-3', NULL, 'br', 'UM3', NULL),
('America/Recife', 'UTC-3', NULL, 'br', 'UM3', NULL),
('America/Araguaina', 'UTC-3', NULL, 'br', 'UM3', NULL),
('America/Maceio', 'UTC-3', NULL, 'br', 'UM3', NULL),
('America/Bahia', 'UTC-3', NULL, 'br', 'UM3', NULL),
('America/Sao_Paulo', 'UTC-3', 'UTC-2', 'br', 'UM3', 'UM2'),
('America/Campo_Grande', 'UTC-4', 'UTC-3', 'br', 'UM4', 'UM3'),
('America/Cuiaba', 'UTC-4', 'UTC-3', 'br', 'UM4', 'UM3'),
('America/Santarem', 'UTC-3', NULL, 'br', 'UM3', NULL),
('America/Porto_Velho', 'UTC-4', NULL, 'br', 'UM4', NULL),
('America/Boa_Vista', 'UTC-4', NULL, 'br', 'UM4', NULL),
('America/Manaus', 'UTC-4', NULL, 'br', 'UM4', NULL),
('America/Eirunepe', 'UTC-4', NULL, 'br', 'UM4', NULL),
('America/Rio_Branco', 'UTC-4', NULL, 'br', 'UM4', NULL),
('America/Nassau', 'UTC-4', 'UTC-3', 'bs', 'UM4', 'UM3'),
('Asia/Thimphu', 'UTC+6', NULL, 'bt', 'UP6', NULL),
('Africa/Gaborone', 'UTC+2', NULL, 'bw', 'UP2', NULL),
('Europe/Minsk', 'UTC+2', 'UTC+3', 'by', 'UP2', 'UP3'),
('America/Belize', 'UTC-6', NULL, 'bz', 'UM6', NULL),
('America/St_Johns', 'UTC-3:30', 'UTC-2:30', 'ca', 'UM35', 'UM25'),
('America/Halifax', 'UTC-4', 'UTC-3', 'ca', 'UM4', 'UM3'),
('America/Glace_Bay', 'UTC-4', 'UTC-3', 'ca', 'UM4', 'UM3'),
('America/Moncton', 'UTC-4', 'UTC-3', 'ca', 'UM4', 'UM3'),
('America/Goose_Bay', 'UTC-4', 'UTC-3', 'ca', 'UM4', 'UM3'),
('America/Blanc-Sablon', 'UTC-4', NULL, 'ca', 'UM4', NULL),
('America/Montreal', 'UTC-5', 'UTC-4', 'ca', 'UM5', 'UM4'),
('America/Toronto', 'UTC-5', 'UTC-4', 'ca', 'UM5', 'UM4'),
('America/Nipigon', 'UTC-5', 'UTC-4', 'ca', 'UM5', 'UM4'),
('America/Thunder_Bay', 'UTC-5', 'UTC-4', 'ca', 'UM5', 'UM4'),
('America/Iqaluit', 'UTC-5', 'UTC-4', 'ca', 'UM5', 'UM4'),
('America/Pangnirtung', 'UTC-5', 'UTC-4', 'ca', 'UM5', 'UM4'),
('America/Resolute', 'UTC-5', 'UTC-4', 'ca', 'UM5', 'UM4'),
('America/Atikokan', 'UTC-5', NULL, 'ca', 'UM5', NULL),
('America/Rankin_Inlet', 'UTC-6', 'UTC-5', 'ca', 'UM6', 'UM5'),
('America/Winnipeg', 'UTC-6', 'UTC-5', 'ca', 'UM6', 'UM5'),
('America/Rainy_River', 'UTC-6', 'UTC-5', 'ca', 'UM6', 'UM5'),
('America/Regina', 'UTC-6', NULL, 'ca', 'UM6', NULL),
('America/Swift_Current', 'UTC-6', NULL, 'ca', 'UM6', NULL),
('America/Edmonton', 'UTC-7', 'UTC-6', 'ca', 'UM7', 'UM6'),
('America/Cambridge_Bay', 'UTC-7', 'UTC-6', 'ca', 'UM7', 'UM6'),
('America/Yellowknife', 'UTC-7', 'UTC-6', 'ca', 'UM7', 'UM6'),
('America/Inuvik', 'UTC-7', 'UTC-6', 'ca', 'UM7', 'UM6'),
('America/Dawson_Creek', 'UTC-7', NULL, 'ca', 'UM7', NULL),
('America/Vancouver', 'UTC-8', 'UTC-7', 'ca', 'UM8', 'UM7'),
('America/Whitehorse', 'UTC-8', 'UTC-7', 'ca', 'UM8', 'UM7'),
('America/Dawson', 'UTC-8', 'UTC-7', 'ca', 'UM8', 'UM7'),
('Indian/Cocos', 'UTC+6:30', NULL, 'cc', 'UP65', NULL),
('Africa/Kinshasa', 'UTC+1', NULL, 'cd', 'UP1', NULL),
('Africa/Lubumbashi', 'UTC+2', NULL, 'cd', 'UP2', NULL),
('Africa/Bangui', 'UTC+1', NULL, 'cf', 'UP1', NULL),
('Africa/Brazzaville', 'UTC+1', NULL, 'cg', 'UP1', NULL),
('Europe/Zurich', 'UTC+1', 'UTC+2', 'ch', 'UP1', 'UP2'),
('Africa/Abidjan', 'UTC', NULL, 'ci', 'UTC', NULL),
('Pacific/Rarotonga', 'UTC-10', NULL, 'ck', 'UM10', NULL),
('America/Santiago', 'UTC-4', 'UTC-3', 'cl', 'UM4', 'UM3'),
('Pacific/Easter', 'UTC-6', 'UTC-5', 'cl', 'UM6', 'UM5'),
('Africa/Douala', 'UTC+1', NULL, 'cm', 'UP1', NULL),
('Asia/Shanghai', 'UTC+8', NULL, 'cn', 'UP8', NULL),
('Asia/Harbin', 'UTC+8', NULL, 'cn', 'UP8', NULL),
('Asia/Chongqing', 'UTC+8', NULL, 'cn', 'UP8', NULL),
('Asia/Urumqi', 'UTC+8', NULL, 'cn', 'UP8', NULL),
('Asia/Kashgar', 'UTC+8', NULL, 'cn', 'UP8', NULL),
('America/Bogota', 'UTC-5', NULL, 'co', 'UM5', NULL),
('America/Costa_Rica', 'UTC-6', NULL, 'cr', 'UM6', NULL),
('America/Havana', 'UTC-5', 'UTC-4', 'cu', 'UM5', 'UM4'),
('Atlantic/Cape_Verde', 'UTC-1', NULL, 'cv', 'UM1', NULL),
('Indian/Christmas', 'UTC+7', NULL, 'cx', 'UP7', NULL),
('Asia/Nicosia', 'UTC+2', 'UTC+3', 'cy', 'UP2', 'UP3'),
('Europe/Prague', 'UTC+1', 'UTC+2', 'cz', 'UP1', 'UP2'),
('Europe/Berlin', 'UTC+1', 'UTC+2', 'de', 'UP1', 'UP2'),
('Africa/Djibouti', 'UTC+3', NULL, 'dj', 'UP3', NULL),
('Europe/Copenhagen', 'UTC+1', 'UTC+2', 'dk', 'UP1', 'UP2'),
('America/Dominica', 'UTC-4', NULL, 'dm', 'UM4', NULL),
('America/Santo_Domingo', 'UTC-4', NULL, 'do', 'UM4', NULL),
('Africa/Algiers', 'UTC+1', NULL, 'dz', 'UP1', NULL),
('America/Guayaquil', 'UTC-5', NULL, 'ec', 'UM5', NULL),
('Pacific/Galapagos', 'UTC-6', NULL, 'ec', 'UM6', NULL),
('Europe/Tallinn', 'UTC+2', 'UTC+3', 'ee', 'UP2', 'UP3'),
('Africa/Cairo', 'UTC+2', NULL, 'eg', 'UP2', NULL),
('Africa/El_Aaiun', 'UTC', NULL, 'eh', 'UTC', NULL),
('Africa/Asmara', 'UTC+3', NULL, 'er', 'UP3', NULL),
('Europe/Madrid', 'UTC+1', 'UTC+2', 'es', 'UP1', 'UP2'),
('Africa/Ceuta', 'UTC+1', 'UTC+2', 'es', 'UP1', 'UP2'),
('Atlantic/Canary', 'UTC', 'UTC+1', 'es', 'UTC', 'UP1'),
('Africa/Addis_Ababa', 'UTC+3', NULL, 'et', 'UP3', NULL),
('Europe/Helsinki', 'UTC+2', 'UTC+3', 'fi', 'UP2', 'UP3'),
('Pacific/Fiji', 'UTC+12', NULL, 'fj', 'UP12', NULL),
('Atlantic/Stanley', 'UTC-4', 'UTC-3', 'fk', 'UM4', 'UM3'),
('Pacific/Truk', 'UTC+10', NULL, 'fm', 'UP10', NULL),
('Pacific/Ponape', 'UTC+11', NULL, 'fm', 'UP11', NULL),
('Pacific/Kosrae', 'UTC+11', NULL, 'fm', 'UP11', NULL),
('Atlantic/Faroe', 'UTC', 'UTC+1', 'fo', 'UTC', 'UP1'),
('Europe/Paris', 'UTC+1', 'UTC+2', 'fr', 'UP1', 'UP2'),
('Africa/Libreville', 'UTC+1', NULL, 'ga', 'UP1', NULL),
('Europe/London', 'UTC', 'UTC+1', 'gb', 'UP1', NULL),
('America/Grenada', 'UTC-4', NULL, 'gd', 'UM4', NULL),
('Asia/Tbilisi', 'UTC+4', NULL, 'ge', 'UP4', NULL),
('America/Cayenne', 'UTC-3', NULL, 'gf', 'UM3', NULL),
('Europe/Guernsey', 'UTC', 'UTC+1', 'gg', 'UTC', 'UP1'),
('Africa/Accra', 'UTC', NULL, 'gh', 'UTC', NULL),
('Europe/Gibraltar', 'UTC+1', 'UTC+2', 'gi', 'UP1', 'UP2'),
('America/Godthab', 'UTC-3', 'UTC-2', 'gl', 'UM3', 'UM2'),
('America/Danmarkshavn', 'UTC', NULL, 'gl', 'UTC', NULL),
('America/Scoresbysund', 'UTC-1', 'UTC', 'gl', 'UM1', 'UTC'),
('America/Thule', 'UTC-4', 'UTC-3', 'gl', 'UM4', 'UM3'),
('Africa/Banjul', 'UTC', NULL, 'gm', 'UTC', NULL),
('Africa/Conakry', 'UTC', NULL, 'gn', 'UTC', NULL),
('America/Guadeloupe', 'UTC-4', NULL, 'gp', 'UM4', NULL),
('Africa/Malabo', 'UTC+1', NULL, 'gq', 'UP1', NULL),
('Europe/Athens', 'UTC+2', 'UTC+3', 'gr', 'UP2', 'UP3'),
('Atlantic/South_Georgia', 'UTC-2', NULL, 'gs', 'UM2', NULL),
('America/Guatemala', 'UTC-6', NULL, 'gt', 'UM6', NULL),
('Pacific/Guam', 'UTC+10', NULL, 'gu', 'UP10', NULL),
('Africa/Bissau', 'UTC', NULL, 'gw', 'UTC', NULL),
('America/Guyana', 'UTC-4', NULL, 'gy', 'UM4', NULL),
('Asia/Hong_Kong', 'UTC+8', NULL, 'hk', 'UP8', NULL),
('America/Tegucigalpa', 'UTC-6', NULL, 'hn', 'UM6', NULL),
('Europe/Zagreb', 'UTC+1', 'UTC+2', 'hr', 'UP1', 'UP2'),
('America/Port-au-Prince', 'UTC-5', NULL, 'ht', 'UM5', NULL),
('Europe/Budapest', 'UTC+1', 'UTC+2', 'hu', 'UP1', 'UP2'),
('Asia/Jakarta', 'UTC+7', NULL, 'id', 'UP7', NULL),
('Asia/Pontianak', 'UTC+7', NULL, 'id', 'UP7', NULL),
('Asia/Makassar', 'UTC+8', NULL, 'id', 'UP8', NULL),
('Asia/Jayapura', 'UTC+9', NULL, 'id', 'UP9', NULL),
('Europe/Dublin', 'UTC', 'UTC+1', 'ie', 'UTC', 'UP1'),
('Asia/Jerusalem', 'UTC+2', 'UTC+3', 'il', 'UP2', 'UP3'),
('Europe/Isle_of_Man', 'UTC', 'UTC+1', 'im', 'UTC', 'UP1'),
('Asia/Kolkata', 'UTC+5:30', NULL, 'in', 'UP55', NULL),
('Indian/Chagos', 'UTC+6', NULL, 'io', 'UP6', NULL),
('Asia/Baghdad', 'UTC+3', NULL, 'iq', 'UP3', NULL),
('Asia/Tehran', 'UTC+3:30', 'UTC+4:30', 'ir', 'UP35', 'UP45'),
('Atlantic/Reykjavik', 'UTC', NULL, 'is', 'UTC', NULL),
('Europe/Rome', 'UTC+1', 'UTC+2', 'it', 'UP1', 'UP2'),
('Europe/Jersey', 'UTC', 'UTC+1', 'je', 'UTC', 'UP1'),
('America/Jamaica', 'UTC-5', NULL, 'jm', 'UM5', NULL),
('Asia/Amman', 'UTC+2', 'UTC+3', 'jo', 'UP2', 'UP3'),
('Asia/Tokyo', 'UTC+9', NULL, 'jp', 'UP9', NULL),
('Africa/Nairobi', 'UTC+3', NULL, 'ke', 'UP3', NULL),
('Asia/Bishkek', 'UTC+6', NULL, 'kg', 'UP6', NULL),
('Asia/Phnom_Penh', 'UTC+7', NULL, 'kh', 'UP7', NULL),
('Pacific/Tarawa', 'UTC+12', NULL, 'ki', 'UP12', NULL),
('Pacific/Enderbury', 'UTC+13', NULL, 'ki', 'UP13', NULL),
('Pacific/Kiritimati', 'UTC+14', NULL, 'ki', 'UP13', NULL),
('Indian/Comoro', 'UTC+3', NULL, 'km', 'UP3', NULL),
('America/St_Kitts', 'UTC-4', NULL, 'kn', 'UM4', NULL),
('Asia/Pyongyang', 'UTC+9', NULL, 'kp', 'UP9', NULL),
('Asia/Seoul', 'UTC+9', NULL, 'kr', 'UP9', NULL),
('Asia/Kuwait', 'UTC+3', NULL, 'kw', 'UP3', NULL),
('America/Cayman', 'UTC-5', NULL, 'ky', 'UM5', NULL),
('Asia/Almaty', 'UTC+6', NULL, 'kz', 'UP6', NULL),
('Asia/Qyzylorda', 'UTC+6', NULL, 'kz', 'UP6', NULL),
('Asia/Aqtobe', 'UTC+5', NULL, 'kz', 'UP5', NULL),
('Asia/Aqtau', 'UTC+5', NULL, 'kz', 'UP5', NULL),
('Asia/Oral', 'UTC+5', NULL, 'kz', 'UP5', NULL),
('Asia/Vientiane', 'UTC+7', NULL, 'la', 'UP7', NULL),
('Asia/Beirut', 'UTC+2', 'UTC+3', 'lb', 'UP2', 'UP3'),
('America/St_Lucia', 'UTC-4', NULL, 'lc', 'UM4', NULL),
('Europe/Vaduz', 'UTC+1', 'UTC+2', 'li', 'UP1', 'UP2'),
('Asia/Colombo', 'UTC+5:30', NULL, 'lk', 'UP55', NULL),
('Africa/Monrovia', 'UTC', NULL, 'lr', 'UTC', NULL),
('Africa/Maseru', 'UTC+2', NULL, 'ls', 'UP2', NULL),
('Europe/Vilnius', 'UTC+2', 'UTC+3', 'lt', 'UP2', 'UP3'),
('Europe/Luxembourg', 'UTC+1', 'UTC+2', 'lu', 'UP1', 'UP2'),
('Europe/Riga', 'UTC+2', 'UTC+3', 'lv', 'UP2', 'UP3'),
('Africa/Tripoli', 'UTC+2', NULL, 'ly', 'UP2', NULL),
('Africa/Casablanca', 'UTC', NULL, 'ma', 'UTC', NULL),
('Europe/Monaco', 'UTC+1', 'UTC+2', 'mc', 'UP1', 'UP2'),
('Europe/Chisinau', 'UTC+2', 'UTC+3', 'md', 'UP2', 'UP3'),
('Europe/Podgorica', 'UTC+1', 'UTC+2', 'me', 'UP1', 'UP2'),
('America/Marigot', 'UTC-4', NULL, 'mf', 'UM4', NULL),
('Indian/Antananarivo', 'UTC+3', NULL, 'mg', 'UP3', NULL),
('Pacific/Majuro', 'UTC+12', NULL, 'mh', 'UP12', NULL),
('Pacific/Kwajalein', 'UTC+12', NULL, 'mh', 'UP12', NULL),
('Europe/Skopje', 'UTC+1', 'UTC+2', 'mk', 'UP1', 'UP2'),
('Africa/Bamako', 'UTC', NULL, 'ml', 'UTC', NULL),
('Asia/Rangoon', 'UTC+6:30', NULL, 'mm', 'UP65', NULL),
('Asia/Ulaanbaatar', 'UTC+8', NULL, 'mn', 'UP8', NULL),
('Asia/Hovd', 'UTC+7', NULL, 'mn', 'UP7', NULL),
('Asia/Choibalsan', 'UTC+8', NULL, 'mn', 'UP8', NULL),
('Asia/Macau', 'UTC+8', NULL, 'mo', 'UP8', NULL),
('Pacific/Saipan', 'UTC+10', NULL, 'mp', 'UP10', NULL),
('America/Martinique', 'UTC-4', NULL, 'mq', 'UM4', NULL),
('Africa/Nouakchott', 'UTC', NULL, 'mr', 'UTC', NULL),
('America/Montserrat', 'UTC-4', NULL, 'ms', 'UM4', NULL),
('Europe/Malta', 'UTC+1', 'UTC+2', 'mt', 'UP1', 'UP2'),
('Indian/Mauritius', 'UTC+4', NULL, 'mu', 'UP4', NULL),
('Indian/Maldives', 'UTC+5', NULL, 'mv', 'UP5', NULL),
('Africa/Blantyre', 'UTC+2', NULL, 'mw', 'UP2', NULL),
('America/Mexico_City', 'UTC-6', 'UTC-5', 'mx', 'UM6', 'UM5'),
('America/Cancun', 'UTC-6', 'UTC-5', 'mx', 'UM6', 'UM5'),
('America/Merida', 'UTC-6', 'UTC-5', 'mx', 'UM6', 'UM5'),
('America/Monterrey', 'UTC-6', 'UTC-5', 'mx', 'UM6', 'UM5'),
('America/Mazatlan', 'UTC-7', 'UTC-6', 'mx', 'UM7', 'UM6'),
('America/Chihuahua', 'UTC-7', 'UTC-6', 'mx', 'UM7', 'UM6'),
('America/Hermosillo', 'UTC-7', NULL, 'mx', 'UM7', NULL),
('America/Tijuana', 'UTC-8', 'UTC-7', 'mx', 'UM8', 'UM7'),
('Asia/Kuala_Lumpur', 'UTC+8', NULL, 'my', 'UP8', NULL),
('Asia/Kuching', 'UTC+8', NULL, 'my', 'UP8', NULL),
('Africa/Maputo', 'UTC+2', NULL, 'mz', 'UP2', NULL),
('Africa/Windhoek', 'UTC+1', 'UTC+2', 'na', 'UP1', 'UP2'),
('Pacific/Noumea', 'UTC+11', NULL, 'nc', 'UP11', NULL),
('Africa/Niamey', 'UTC+1', NULL, 'ne', 'UP1', NULL),
('Pacific/Norfolk', 'UTC+11:30', NULL, 'nf', 'UP115', NULL),
('Africa/Lagos', 'UTC+1', NULL, 'ng', 'UP1', NULL),
('America/Managua', 'UTC-6', NULL, 'ni', 'UM6', NULL),
('Europe/Amsterdam', 'UTC+1', NULL, 'nl', 'UP1', NULL),
('Europe/Oslo', 'UTC+1', 'UTC+2', 'no', 'UP1', 'UP2'),
('Asia/Katmandu', 'UTC+5:45', NULL, 'np', 'UP575', NULL),
('Pacific/Nauru', 'UTC+12', NULL, 'nr', 'UP12', NULL),
('Pacific/Niue', 'UTC-11', NULL, 'nu', 'UM11', NULL),
('Pacific/Auckland', 'UTC+12', 'UTC+13', 'nz', 'UP12', 'UP13'),
('Pacific/Chatham', 'UTC+12:45', 'UTC+13:45', 'nz', 'UP1275', 'UP1375'),
('Asia/Muscat', 'UTC+4', NULL, 'om', 'UP4', NULL),
('America/Panama', 'UTC-5', NULL, 'pa', 'UM5', NULL),
('America/Lima', 'UTC-5', NULL, 'pe', 'UM5', NULL),
('Pacific/Tahiti', 'UTC-10', NULL, 'pf', 'UM10', NULL),
('Pacific/Marquesas', 'UTC+9:30', NULL, 'pf', 'UP95', NULL),
('Pacific/Gambier', 'UTC-9', NULL, 'pf', 'UM9', NULL),
('Pacific/Port_Moresby', 'UTC+10', NULL, 'pg', 'UP10', NULL),
('Asia/Manila', 'UTC+8', NULL, 'ph', 'UP8', NULL),
('Asia/Karachi', 'UTC+6', NULL, 'pk', 'UP6', NULL),
('Europe/Warsaw', 'UTC+1', 'UTC+2', 'pl', 'UP1', 'UP2'),
('America/Miquelon', 'UTC-3', 'UTC-2', 'pm', 'UM3', 'UM2'),
('Pacific/Pitcairn', 'UTC-8', NULL, 'pn', 'UM8', NULL),
('America/Puerto_Rico', 'UTC-4', NULL, 'pr', 'UM4', NULL),
('Asia/Gaza', 'UTC+2', 'UTC+3', 'ps', 'UP2', 'UP3'),
('Europe/Lisbon', 'UTC', 'UTC+1', 'pt', 'UTC', 'UP1'),
('Atlantic/Madeira', 'UTC', 'UTC+1', 'pt', 'UTC', 'UP1'),
('Atlantic/Azores', 'UTC-1', 'UTC', 'pt', 'UM1', 'UTC'),
('Pacific/Palau', 'UTC+9', NULL, 'pw', 'UP9', NULL),
('America/Asuncion', 'UTC-4', 'UTC-3', 'py', 'UM4', 'UM3'),
('Asia/Qatar', 'UTC+3', NULL, 'qa', 'UP3', NULL),
('Indian/Reunion', 'UTC+4', NULL, 're', 'UP4', NULL),
('Europe/Bucharest', 'UTC+2', 'UTC+3', 'ro', 'UP2', 'UP3'),
('Europe/Belgrade', 'UTC+1', 'UTC+2', 'rs', 'UP1', 'UP2'),
('Europe/Kaliningrad', 'UTC+2', 'UTC+3', 'ru', 'UP2', 'UP3'),
('Europe/Moscow', 'UTC+3', 'UTC+4', 'ru', 'UP3', 'UP4'),
('Europe/Volgograd', 'UTC+3', 'UTC+4', 'ru', 'UP3', 'UP4'),
('Europe/Samara', 'UTC+4', 'UTC+5', 'ru', 'UP4', 'UP5'),
('Asia/Yekaterinburg', 'UTC+5', 'UTC+6', 'ru', 'UP5', 'UP6'),
('Asia/Omsk', 'UTC+6', 'UTC+7', 'ru', 'UP6', 'UP7'),
('Asia/Novosibirsk', 'UTC+6', 'UTC+7', 'ru', 'UP6', 'UP7'),
('Asia/Krasnoyarsk', 'UTC+7', 'UTC+8', 'ru', 'UP7', 'UP8'),
('Asia/Irkutsk', 'UTC+8', 'UTC+9', 'ru', 'UP8', 'UP9'),
('Asia/Yakutsk', 'UTC+9', 'UTC+10', 'ru', 'UP9', 'UP10'),
('Asia/Vladivostok', 'UTC+10', 'UTC+11', 'ru', 'UP10', 'UP11'),
('Asia/Sakhalin', 'UTC+10', 'UTC+11', 'ru', 'UP10', 'UP11'),
('Asia/Magadan', 'UTC+11', 'UTC+12', 'ru', 'UP11', 'UP12'),
('Asia/Kamchatka', 'UTC+12', 'UTC+13', 'ru', 'UP12', 'UP13'),
('Asia/Anadyr', 'UTC+12', 'UTC+13', 'ru', 'UP12', 'UP13'),
('Africa/Kigali', 'UTC+2', NULL, 'rw', 'UP2', NULL),
('Asia/Riyadh', 'UTC+3', NULL, 'sa', 'UP3', NULL),
('Pacific/Guadalcanal', 'UTC+11', NULL, 'sb', 'UP11', NULL),
('Indian/Mahe', 'UTC+4', NULL, 'sc', 'UP4', NULL),
('Africa/Khartoum', 'UTC+3', NULL, 'sd', 'UP3', NULL),
('Europe/Stockholm', 'UTC+1', 'UTC+2', 'se', 'UP1', 'UP2'),
('Asia/Singapore', 'UTC+8', NULL, 'sg', 'UP8', NULL),
('Atlantic/St_Helena', 'UTC', NULL, 'sh', 'UTC', NULL),
('Europe/Ljubljana', 'UTC+1', 'UTC+2', 'si', 'UP1', 'UP2'),
('Arctic/Longyearbyen', 'UTC+1', 'UTC+2', 'sj', 'UP1', 'UP2'),
('Europe/Bratislava', 'UTC+1', 'UTC+2', 'sk', 'UP1', 'UP2'),
('Africa/Freetown', 'UTC', NULL, 'sl', 'UTC', NULL),
('Europe/San_Marino', 'UTC+1', 'UTC+2', 'sm', 'UP1', 'UP2'),
('Africa/Dakar', 'UTC', NULL, 'sn', 'UTC', NULL),
('Africa/Mogadishu', 'UTC+3', NULL, 'so', 'UTC3', NULL),
('America/Paramaribo', 'UTC-3', NULL, 'sr', 'UM3', NULL),
('Africa/Sao_Tome', 'UTC', NULL, 'st', 'UTC', NULL),
('America/El_Salvador', 'UTC-6', NULL, 'sv', 'UM6', NULL),
('Asia/Damascus', 'UTC+2', 'UTC+3', 'sy', 'UP2', 'UP3'),
('Africa/Mbabane', 'UTC+2', NULL, 'sz', 'UP2', NULL),
('America/Grand_Turk', 'UTC-5', 'UTC-4', 'tc', 'UM5', 'UM4'),
('Africa/Ndjamena', 'UTC+1', NULL, 'td', 'UP1', NULL),
('Indian/Kerguelen', 'UTC+5', NULL, 'tf', 'UP5', NULL),
('Africa/Lome', 'UTC', NULL, 'tg', 'UTC', NULL),
('Asia/Bangkok', 'UTC+7', NULL, 'th', 'UP7', NULL),
('Asia/Dushanbe', 'UTC+5', NULL, 'tj', 'UP5', NULL),
('Pacific/Fakaofo', 'UTC-10', NULL, 'tk', 'UM10', NULL),
('Asia/Dili', 'UTC+9', NULL, 'tl', 'UP9', NULL),
('Asia/Ashgabat', 'UTC+5', NULL, 'tm', 'UP5', NULL),
('Africa/Tunis', 'UTC+1', 'UTC+2', 'tn', 'UP1', 'UP2'),
('Pacific/Tongatapu', 'UTC+13', NULL, 'to', 'UP13', NULL),
('Europe/Istanbul', 'UTC+2', 'UTC+3', 'tr', 'UP2', 'UP3'),
('America/Port_of_Spain', 'UTC-4', NULL, 'tt', 'UM4', NULL),
('Pacific/Funafuti', 'UTC+12', NULL, 'tv', 'UP12', NULL),
('Asia/Taipei', 'UTC+8', NULL, 'tw', 'UP8', NULL),
('Africa/Dar_es_Salaam', 'UTC+3', NULL, 'tz', 'UP3', NULL),
('Europe/Kiev', 'UTC+2', 'UTC+3', 'ua', 'UP2', 'UP3'),
('Europe/Uzhgorod', 'UTC+2', 'UTC+3', 'ua', 'UP2', 'UP3'),
('Europe/Zaporozhye', 'UTC+2', 'UTC+3', 'ua', 'UP2', 'UP3'),
('Europe/Simferopol', 'UTC+2', 'UTC+3', 'ua', 'UP2', 'UP3'),
('Africa/Kampala', 'UTC+3', NULL, 'ug', 'UP3', NULL),
('Pacific/Johnston', 'UTC-10', NULL, 'um', 'UM10', NULL),
('Pacific/Midway', 'UTC-11', NULL, 'um', 'UM11', NULL),
('Pacific/Wake', 'UTC+12', NULL, 'um', 'UP12', NULL),
('America/New_York', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Detroit', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Kentucky/Louisville', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Kentucky/Monticello', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Indiana/Indianapolis', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Indiana/Vincennes', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Indiana/Winamac', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Indiana/Marengo', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Indiana/Petersburg', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Indiana/Vevay', 'UTC-5', 'UTC-4', 'us', 'UM5', 'UM4'),
('America/Chicago', 'UTC-6', 'UTC-5', 'us', 'UM6', 'UM5'),
('America/Indiana/Tell_City', 'UTC-6', 'UTC-5', 'us', 'UM6', 'UM5'),
('America/Indiana/Knox', 'UTC-6', 'UTC-5', 'us', 'UM6', 'UM5'),
('America/Menominee', 'UTC-6', 'UTC-5', 'us', 'UM6', 'UM5'),
('America/North_Dakota/Center', 'UTC-6', 'UTC-5', 'us', 'UM6', 'UM5'),
('America/North_Dakota/New_Salem', 'UTC-6', 'UTC-5', 'us', 'UM6', 'UM5'),
('America/Denver', 'UTC-7', 'UTC-6', 'us', 'UM7', 'UM6'),
('America/Boise', 'UTC-7', 'UTC-6', 'us', 'UM7', 'UM6'),
('America/Shiprock', 'UTC-7', 'UTC-6', 'us', 'UM7', 'UM6'),
('America/Phoenix', 'UTC-7', NULL, 'us', 'UM7', NULL),
('America/Los_Angeles', 'UTC-8', 'UTC-7', 'us', 'UM8', 'UM7'),
('America/Anchorage', 'UTC-9', 'UTC-8', 'us', 'UM9', 'UM8'),
('America/Juneau', 'UTC-9', 'UTC-8', 'us', 'UM9', 'UM8'),
('America/Yakutat', 'UTC-9', 'UTC-8', 'us', 'UM9', 'UM8'),
('America/Nome', 'UTC-9', 'UTC-8', 'us', 'UM9', 'UM8'),
('America/Adak', 'UTC-10', 'UTC-9', 'us', 'UM10', 'UM9'),
('Pacific/Honolulu', 'UTC-10', NULL, 'us', 'UM10', NULL),
('America/Montevideo', 'UTC-3', 'UTC-2', 'uy', 'UM3', 'UM2'),
('Asia/Samarkand', 'UTC+5', NULL, 'uz', 'UP5', NULL),
('Asia/Tashkent', 'UTC+5', NULL, 'uz', 'UP5', NULL),
('Europe/Vatican', 'UTC+1', 'UTC+2', 'va', 'UP1', 'UP2'),
('America/St_Vincent', 'UTC-4', NULL, 'vc', 'UM4', NULL),
('America/Caracas', 'UTC-4:30', NULL, 've', 'UM45', NULL),
('America/Tortola', 'UTC-4', NULL, 'vg', 'UM4', NULL),
('America/St_Thomas', 'UTC-4', NULL, 'vi', 'UM4', NULL),
('Asia/Ho_Chi_Minh', 'UTC+7', NULL, 'vn', 'UP7', NULL),
('Pacific/Efate', 'UTC+11', NULL, 'vu', 'UP11', NULL),
('Pacific/Wallis', 'UTC+12', NULL, 'wf', 'UP12', NULL),
('Pacific/Apia', 'UTC-11', NULL, 'ws', 'UM11', NULL),
('Asia/Aden', 'UTC+3', NULL, 'ye', 'UP3', NULL),
('Indian/Mayotte', 'UTC+3', NULL, 'yt', 'UP3', NULL),
('Africa/Johannesburg', 'UTC+2', NULL, 'za', 'UP2', NULL),
('Africa/Lusaka', 'UTC+2', NULL, 'zm', 'UP2', NULL),
('Africa/Harare', 'UTC+2', NULL, 'zw', 'UP2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aponjon_member`
--

CREATE TABLE `aponjon_member` (
  `member_id` int(11) NOT NULL,
  `subscriber_type` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cell_no` varchar(11) NOT NULL,
  `services_model` varchar(5) DEFAULT NULL,
  `gatekeeper_cell_no` varchar(11) DEFAULT NULL,
  `relationship_with_gatekeeper` varchar(100) DEFAULT NULL,
  `create_date_time` datetime NOT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aponjon_member`
--

INSERT INTO `aponjon_member` (`member_id`, `subscriber_type`, `name`, `cell_no`, `services_model`, `gatekeeper_cell_no`, `relationship_with_gatekeeper`, `create_date_time`, `update_user_id`, `update_time`) VALUES
(1, 'Pregnant Women', 'Zahidul Ripon', '01675794194', 'SMS', '01675794199', 'Friend', '2016-11-20 12:19:11', NULL, NULL),
(2, 'Pregnant Women', 'Zahidul Ripon', '01675794194', 'SMS', '01675794199', 'Father', '2016-11-20 12:32:21', NULL, NULL),
(3, 'Pregnant Women', 'Tanjina anwer', '01688568699', 'Voice', '01675794199', 'Hosband', '2016-11-20 12:56:29', NULL, NULL),
(4, 'Pregnant Women', 'Faria Tina', '01675794194', 'SMS', '01675794199', 'Friend', '2016-11-20 13:02:33', NULL, NULL),
(5, 'Pregnant Women', 'Chamon Ara Begum', '01884566999', 'SMS', '01675794199', 'Friend', '2016-11-20 13:05:24', NULL, NULL),
(6, 'Pregnant Women', 'Mafuza Akter', '01675794198', 'Voice', '01675794188', 'Father', '2016-11-20 13:11:18', NULL, NULL),
(7, 'New Mother', 'xxxxxxxxxxx', '01711082536', 'SMS', '', 'Mother-in-Law', '2016-11-20 13:56:24', NULL, NULL),
(8, 'Pregnant Women', 'Mamuda Khatun', '01675794194', 'SMS', '01688458699', 'Mother', '2016-11-20 15:49:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `eh_news`
--

CREATE TABLE `eh_news` (
  `news_id` int(11) NOT NULL,
  `news_title_en` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `news_title_bn` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `news_details_en` text CHARACTER SET utf8 COLLATE utf8_bin,
  `news_details_bn` text CHARACTER SET utf8 COLLATE utf8_bin,
  `news_publish_date` date DEFAULT NULL,
  `news_image` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `thumbnail` varchar(100) DEFAULT NULL,
  `create_user_id` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_user_id` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `enable` int(2) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eh_news`
--

INSERT INTO `eh_news` (`news_id`, `news_title_en`, `news_title_bn`, `news_details_en`, `news_details_bn`, `news_publish_date`, `news_image`, `thumbnail`, `create_user_id`, `create_date`, `update_user_id`, `update_date`, `enable`) VALUES
(6, 'Future Startup conducted an interview with Dnet CEO Dr. Ananya Raihan', 'ভবিষ্যত প্রারম্ভ Dnet সিইও ডঃ অনন্য রায়হান সঙ্গে একটি সাক্ষাত্কারে পরিচালিত', '<p style="text-align: justify;"><img src="/dproject/resource/img/news/ediror/Dr-Ananya-Raihan-02.jpg" alt="" width="678" height="452" />&nbsp;</p>\r\n<p>Social entrepreneur, Economist, and co-founder of&nbsp;<a href="http://dnet.org.bd/details/Article/359/">Dnet</a>,&nbsp;<a href="http://dnet.org.bd/details/Article/359/Content.php?DtailType=Staff&amp;DtailId=33">Dr. Ananya Raihan</a>, reflects on his early life, professional choice of economics and development, serendipitous journey into the world of technology, the birth and early days of Dnet, struggles and pains of working on a new idea, building Dnet as an innovation hub, and Dnet&rsquo;s approach to innovation, measuring impact, incubating ideas and launching spin-off ventures.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p><strong>Future Startup</strong></p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>I want to dive in at the beginning of your story. Where did you grow up? How was your early life?</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p><strong>Dr. Ananya Raihan</strong></p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>I grew up in a middle-class family. Most of the family members including my father were in teaching. My father was a Lecturer of Bangla at a Government College. He started teaching in college and subsequently joined Jahangirnagar University. Because of the nature of his job he used to transfer a lot and go from one college to another, one place to another place and we traveled with him and saw many parts of our beautiful country at that time. I was lucky to study in eight different schools in twelve years of my pre-tertiary education!</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>It certainly had some silver linings. I made a lot of friends all over the country. I learned to cope with and adjust to the changing surroundings. Once I managed to have a couple of friends at one place, I had to leave to another new place and start everything over. I think it helped me with my later life as well. Probably, my traveler gene settled within me from my boyhood.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>However, the feeling at that time was mixed &ndash; leaving friends in one place, and, the excitement of getting new friends in a new place. It taught me the transient nature of life.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>Towards the end of my high school life started settling down. We moved to Jahangirnagar University Campus and stayed there for the next four years. My father completed his Ph. D in Bangla Literature from there and joined as a Professor. I finished my high-school and college from Jahangirnagar University School and College.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>The culture at our home was very liberal. The life within and outside the school was vibrant, with lots of activities &ndash; books, music, literature, gardening, traveling, organizing events with school mates. We used to read a lot and we, five siblings, together built a library on our own.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p><strong>Future Startup</strong></p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>You went to BUET after your Grade XII, although you did not want to. What happened then?</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p><strong>Dr. Ananya Raihan</strong></p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>In our time there were three routes to professional life: be an engineer, doctor or a government official. Having leftist political influence within the family, I read a lot on Marxism and started believing that to change the world first we need to change the economy so that people have equal opportunity.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>When I was finishing Grade X, I believed that I have to study economics. But since I was very good at Mathematics, my parents wanted me to go to BUET and become an Engineer.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>After Grade XII, despite my unwillingness, I sat for admission tests for both BUET and Jahangirnagar University. I got selected in BUET and became first in written admission Test in Jahangirnagar. I went for the interview, however, Professor Akhlakur Rahman, one of my idols in Economics, who was the then Chair of the Economics Department, rejected me as he knew that I was selected in BUET and my parents wanted me to study there. Finally, I had to enroll in BUET, but this was against my will.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<blockquote>\r\n<p>"I applied for the scholarship and eventually went to the Soviet Union to be an Economist. It was 1984. Interestingly, my class at BUET was scheduled to start on September 11, 1984. I started my class in the Soviet Union on the same date. I have chosen my path in life."</p>\r\n</blockquote>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>The year 1984 witnessed lots of political turmoil and between my HSC Exam and beginning of class in university, there was almost a year time.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>One good thing of getting into BUET was that I got the opportunity to spend time with a lot of really passionate and great people. I used to spend most of my time with my seniors. There was a science magazine at that time called Onu (Atom), by Prof. A R Khan of Dhaka University. Swapan Biswas, a senior student of BUET, was the editor of the magazine. The purpose was to popularize science in Bangla language.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>Once I came across the magazine, I immediately got interested. I started to work with the magazine team. I took over significant responsibility within a short period of time. I used to write the article for the magazine, collect write-ups from other writers, and look after composition and printing in press in Ram Krishna Mission Road. After getting the magazine printed, I had to distribute them in the bookshops in New Market and collect the sales proceed.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>I was very active in anti-military regime movement and worked with the Communist Party and Bangladesh Students&rsquo; Union. I participated in processions, chanting slogans, writing graffiti on the walls, publishing little magazines, delivering secret documents from one place to other.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>I was also very active with Children Cultural Movement though &lsquo;Khelaghor&rsquo;. It was an exciting time.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>I already left home and was staying at Ahsan Ullah Hall and doing all these. I was supposed to start my class and stay in Shaheed Smrity Hall (BUET dorm).</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p>Soon my parents realized that they didn&rsquo;t do a very good job by forcing me to study engineering. Later they found out a scholarship offer for me to study Economics in the Soviet Union (the country, which does not exist anymore, I was placed in Ukraine, one of the states of the USSR).</p>\r\n<p style="text-align: justify;"><span style="font-family: Vrinda, sans-serif; font-size: 12pt; letter-spacing: -0.4pt; text-align: justify;">&nbsp;</span></p>', '<p>Social entrepreneur, Economist, and co-founder of&nbsp;<a href="http://dnet.org.bd/details/Article/359/">Dnet</a>,&nbsp;<a href="http://dnet.org.bd/details/Article/359/Content.php?DtailType=Staff&amp;DtailId=33">Dr. Ananya Raihan</a>, reflects on his early life, professional choice of economics and development, serendipitous journey into the world of technology, the birth and early days of Dnet, struggles and pains of working on a new idea, building Dnet as an innovation hub, and Dnet&rsquo;s approach to innovation, measuring impact, incubating ideas and launching spin-off ventures.</p>\r\n<p><strong>Future Startup</strong></p>\r\n<p>I want to dive in at the beginning of your story. Where did you grow up? How was your early life?</p>\r\n<p><strong>Dr. Ananya Raihan</strong></p>\r\n<p>I grew up in a middle-class family. Most of the family members including my father were in teaching. My father was a Lecturer of Bangla at a Government College. He started teaching in college and subsequently joined Jahangirnagar University. Because of the nature of his job he used to transfer a lot and go from one college to another, one place to another place and we traveled with him and saw many parts of our beautiful country at that time. I was lucky to study in eight different schools in twelve years of my pre-tertiary education!</p>\r\n<p>It certainly had some silver linings. I made a lot of friends all over the country. I learned to cope with and adjust to the changing surroundings. Once I managed to have a couple of friends at one place, I had to leave to another new place and start everything over. I think it helped me with my later life as well. Probably, my traveler gene settled within me from my boyhood.</p>\r\n<p>However, the feeling at that time was mixed &ndash; leaving friends in one place, and, the excitement of getting new friends in a new place. It taught me the transient nature of life.</p>\r\n<p>Towards the end of my high school life started settling down. We moved to Jahangirnagar University Campus and stayed there for the next four years. My father completed his Ph. D in Bangla Literature from there and joined as a Professor. I finished my high-school and college from Jahangirnagar University School and College.</p>\r\n<p>The culture at our home was very liberal. The life within and outside the school was vibrant, with lots of activities &ndash; books, music, literature, gardening, traveling, organizing events with school mates. We used to read a lot and we, five siblings, together built a library on our own.</p>\r\n<p><strong>Future Startup</strong></p>\r\n<p>You went to BUET after your Grade XII, although you did not want to. What happened then?</p>\r\n<p><strong>Dr. Ananya Raihan</strong></p>\r\n<p>In our time there were three routes to professional life: be an engineer, doctor or a government official. Having leftist political influence within the family, I read a lot on Marxism and started believing that to change the world first we need to change the economy so that people have equal opportunity.</p>\r\n<p>When I was finishing Grade X, I believed that I have to study economics. But since I was very good at Mathematics, my parents wanted me to go to BUET and become an Engineer.</p>\r\n<p>After Grade XII, despite my unwillingness, I sat for admission tests for both BUET and Jahangirnagar University. I got selected in BUET and became first in written admission Test in Jahangirnagar. I went for the interview, however, Professor Akhlakur Rahman, one of my idols in Economics, who was the then Chair of the Economics Department, rejected me as he knew that I was selected in BUET and my parents wanted me to study there. Finally, I had to enroll in BUET, but this was against my will.</p>\r\n<blockquote>\r\n<p>"I applied for the scholarship and eventually went to the Soviet Union to be an Economist. It was 1984. Interestingly, my class at BUET was scheduled to start on September 11, 1984. I started my class in the Soviet Union on the same date. I have chosen my path in life."</p>\r\n</blockquote>\r\n<p>The year 1984 witnessed lots of political turmoil and between my HSC Exam and beginning of class in university, there was almost a year time.</p>\r\n<p>One good thing of getting into BUET was that I got the opportunity to spend time with a lot of really passionate and great people. I used to spend most of my time with my seniors. There was a science magazine at that time called Onu (Atom), by Prof. A R Khan of Dhaka University. Swapan Biswas, a senior student of BUET, was the editor of the magazine. The purpose was to popularize science in Bangla language.</p>\r\n<p>Once I came across the magazine, I immediately got interested. I started to work with the magazine team. I took over significant responsibility within a short period of time. I used to write the article for the magazine, collect write-ups from other writers, and look after composition and printing in press in Ram Krishna Mission Road. After getting the magazine printed, I had to distribute them in the bookshops in New Market and collect the sales proceed.</p>\r\n<p>I was very active in anti-military regime movement and worked with the Communist Party and Bangladesh Students&rsquo; Union. I participated in processions, chanting slogans, writing graffiti on the walls, publishing little magazines, delivering secret documents from one place to other.</p>\r\n<p>I was also very active with Children Cultural Movement though &lsquo;Khelaghor&rsquo;. It was an exciting time.</p>\r\n<p>I already left home and was staying at Ahsan Ullah Hall and doing all these. I was supposed to start my class and stay in Shaheed Smrity Hall (BUET dorm).</p>\r\n<p>Soon my parents realized that they didn&rsquo;t do a very good job by forcing me to study engineering. Later they found out a scholarship offer for me to study Economics in the Soviet Union (the country, which does not exist anymore, I was placed in Ukraine, one of the states of the USSR).</p>', NULL, 'b6db119eaefb9a8b95d8367c00058e72.jpg', 'b6db119eaefb9a8b95d8367c00058e72_thumb.jpg', 'admin', '2015-12-26 01:30:14', 'admin', '2016-11-19 04:16:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL,
  `gallery_name_en` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `gallery_name_bn` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `gallery_image` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `thumbnail` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `create_user_id` varchar(50) COLLATE utf8_bin NOT NULL,
  `create_date` datetime NOT NULL,
  `update_user_id` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `enable` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `gallery_name_en`, `gallery_name_bn`, `gallery_image`, `thumbnail`, `create_user_id`, `create_date`, `update_user_id`, `update_date`, `enable`) VALUES
(4, 'dNet Team', 'dNet Team', '70cfb6a2a2cc73fa503c8fafb2c06aaa.png', '70cfb6a2a2cc73fa503c8fafb2c06aaa_thumb.png', 'admin', '2015-01-13 01:07:19', 'admin', '2016-11-19 04:32:20', 1),
(5, 'dNet in Digital Fair', 'dNet in Digital Fair', '700bb23316917fcaddb293a5a73b8dc0.jpg', '700bb23316917fcaddb293a5a73b8dc0_thumb.jpg', 'admin', '2015-12-26 01:42:31', 'admin', '2016-11-19 04:30:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_image`
--

CREATE TABLE `gallery_image` (
  `image_id` int(11) NOT NULL,
  `image_caption_en` varchar(200) COLLATE utf8_bin NOT NULL,
  `image_caption_bn` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `image_file` varchar(100) COLLATE utf8_bin NOT NULL,
  `image_thumb` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `create_user_id` varchar(50) COLLATE utf8_bin NOT NULL,
  `create_date` datetime NOT NULL,
  `update_user_id` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `enable` int(1) NOT NULL DEFAULT '1',
  `gallery_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `gallery_image`
--

INSERT INTO `gallery_image` (`image_id`, `image_caption_en`, `image_caption_bn`, `image_file`, `image_thumb`, `create_user_id`, `create_date`, `update_user_id`, `update_date`, `enable`, `gallery_id`) VALUES
(3, 'dNet Team', 'dNet Team', '4c0e1c9f0a3f91c45907ba7a18520024.png', '4c0e1c9f0a3f91c45907ba7a18520024_thumb.png', 'admin', '2015-12-26 01:37:51', 'admin', '2016-11-19 04:35:59', 1, 4),
(4, 'dNet Team', 'dNet Team', 'c8e11bfb0adeb0b9602836cd4fce7387.jpg', 'c8e11bfb0adeb0b9602836cd4fce7387_thumb.jpg', 'admin', '2015-12-26 01:38:07', 'admin', '2016-11-19 04:36:29', 1, 4),
(5, 'Digital Fair', 'Digital Fair', '150c556b2e7b0179cc51c47370b36639.jpg', '150c556b2e7b0179cc51c47370b36639_thumb.jpg', 'admin', '2015-12-26 01:43:40', 'admin', '2016-11-19 04:34:20', 1, 5),
(6, 'Digital Fair', 'Digital Fair', '3331957bbacb18fa961857061764e2b3.jpg', '3331957bbacb18fa961857061764e2b3_thumb.jpg', 'admin', '2015-12-26 01:44:05', 'admin', '2016-11-19 04:34:56', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL,
  `page_title_en` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `page_title_bn` varchar(120) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `slug` varchar(50) DEFAULT NULL,
  `page_details_en` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `page_details_bn` text CHARACTER SET utf8 COLLATE utf8_bin,
  `page_image` varchar(200) DEFAULT NULL,
  `thumbnail` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `create_user_id` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_user_id` varchar(50) NOT NULL,
  `update_date` datetime NOT NULL,
  `enable` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page_id`, `page_title_en`, `page_title_bn`, `slug`, `page_details_en`, `page_details_bn`, `page_image`, `thumbnail`, `create_user_id`, `create_date`, `update_user_id`, `update_date`, `enable`) VALUES
(1, 'WELCOME TO DNET', 'ডি নেটে স্বাগতম', 'overview', '<p style="text-align: justify;">Dnet is a not for profit social enterprise. It was established in 2001, with a vision for a society where information and knowledge facilitates all stake holders participation in generation of wealth and its equitable distribution for poverty alleviation. Dnet pioneered in research on using ICT as a primary means for alleviating poverty, empowerment through minimizing the digital divide and access to information, civic participation, capacity development and employment generation. These endeavors have helped us to transcend the national boundary and become visible in the global context. Dnet fosters fusion of social and technological innovations for improving the lives of marginalized people in Bangladesh.<br /><br />Initiating activities in many areas of development, Dnet has consolidated its endeavors in healthcare, education, livelihood and social accountability. A number of initiatives launched by Dnet have been scaled up and replicated by the government and partner institutions, for example, Dnet Pallytathya Kendras are replicated by Bangladesh government in the form of Union Information Service Center (UISC) . Dnet has also invested in building new institutions with ideas initially nurtured within Dnet and has taken a pro-active role in building 4 institutions, who have successfully established their reputation in their working arena.<br /><br />Dnet believes that when there is no other way, charity may work. However, for sustained improvement of the lives of marginalized people one needs to create opportunities for them.&nbsp;<br />Mutual respect, integrity, accountability and conscious spending are core values of Dnet and are pro-actively practiced within the institution. Dnet, by mandate, has been very active in the promotion of Right to Information(RTI), through its programs and projects, and also by joining the ongoing movement in the country. Dnet is working to establish women\'s rights within the organization and society. To that end, Dnet has developed and implemented it\'s institutional gender policy and sexual harassment prevention policy.<br /><br />Dnet has a young team, and we believe in the power of youth to create positive change in our society. Our multilateral collaborations with GO&rsquo;s, NGO&rsquo;s, development partners, donor agencies has strengthen our ability to undertake large scale programs and ensure smooth execution.<br /><br />Core activities:&nbsp;<br />&bull; Conducting extensive empirical and social research to develop sustainable models for social change, improvement of livelihood and empowerment of targeted population.&nbsp;<br />&bull; Develop livelihood, educational and knowledge products and contents.<br />&bull; Campaign and build mass awareness on social problems and issues of importance<br />&bull; Improving livelihood of woman through empowerment, better health, access to information etc.<br />&bull; Ensuring citizens right through developing easy access to information&nbsp;<br />&bull; Using ICT as tool of empowerment of mass population and access to livelihood information</p>', '<p>Dnet is a not for profit social enterprise. It was established in 2001, with a vision for a society where information and knowledge facilitates all stake holders participation in generation of wealth and its equitable distribution for poverty alleviation. Dnet pioneered in research on using ICT as a primary means for alleviating poverty, empowerment through minimizing the digital divide and access to information, civic participation, capacity development and employment generation. These endeavors have helped us to transcend the national boundary and become visible in the global context. Dnet fosters fusion of social and technological innovations for improving the lives of marginalized people in Bangladesh.<br /><br />Initiating activities in many areas of development, Dnet has consolidated its endeavors in healthcare, education, livelihood and social accountability. A number of initiatives launched by Dnet have been scaled up and replicated by the government and partner institutions, for example, Dnet Pallytathya Kendras are replicated by Bangladesh government in the form of Union Information Service Center (UISC) . Dnet has also invested in building new institutions with ideas initially nurtured within Dnet and has taken a pro-active role in building 4 institutions, who have successfully established their reputation in their working arena.<br /><br />Dnet believes that when there is no other way, charity may work. However, for sustained improvement of the lives of marginalized people one needs to create opportunities for them.&nbsp;<br />Mutual respect, integrity, accountability and conscious spending are core values of Dnet and are pro-actively practiced within the institution. Dnet, by mandate, has been very active in the promotion of Right to Information(RTI), through its programs and projects, and also by joining the ongoing movement in the country. Dnet is working to establish women\'s rights within the organization and society. To that end, Dnet has developed and implemented it\'s institutional gender policy and sexual harassment prevention policy.<br /><br />Dnet has a young team, and we believe in the power of youth to create positive change in our society. Our multilateral collaborations with GO&rsquo;s, NGO&rsquo;s, development partners, donor agencies has strengthen our ability to undertake large scale programs and ensure smooth execution.<br /><br />Core activities:&nbsp;<br />&bull; Conducting extensive empirical and social research to develop sustainable models for social change, improvement of livelihood and empowerment of targeted population.&nbsp;<br />&bull; Develop livelihood, educational and knowledge products and contents.<br />&bull; Campaign and build mass awareness on social problems and issues of importance<br />&bull; Improving livelihood of woman through empowerment, better health, access to information etc.<br />&bull; Ensuring citizens right through developing easy access to information&nbsp;<br />&bull; Using ICT as tool of empowerment of mass population and access to livelihood information</p>', NULL, NULL, 'admin', '2015-01-12 19:21:48', 'admin', '2016-11-18 04:21:05', 1),
(2, 'About Us', 'আমাদের সম্পর্কে', 'about_us', '<p><strong>The Identity and Working Philosophy</strong></p>\r\n<p><strong>Identity:</strong></p>\r\n<p>Dnet is a non-profit Social Enterprise fostering innovations for empowerment of marginalized communities with special emphasis on women and children focusing on technology and access to information and knowledge.<br />As a non-profit social enterprise, Dnet thrives to apply commercial strategies, where applicable, to maximize improvements in human and environmental well-being, rather than maximizing profits for external shareholders.&nbsp;<br />Dnet, through its activities, envisages to contribute towards building a social economy.<br /><br /><strong>Working Philosophy:</strong></p>\r\n<p>Whatever goods, services and social values we create, Dnet does ethical review including their creation processes. Dnet always looks for social purpose(s), and evidences of social impact. Dnet works for democratization of management and governance by inclusion of our human, social and financial capital to our primary stakeholders (producers, employees, customers, and service users).<br /><br /><strong>The Journey</strong>&nbsp;<br />On January 13 2001, a group of young professionals met to discuss how information and communication technology (ICT) can be integrated into the economic development process of Bangladesh and beyond her geographic boundary. The outcome of the meeting was a decision to build a dedicated institution for research on, interalia, mainstreaming ICT in poverty alleviation and economic development.<br /><br />Since then Dnet has evolved into a social enterprise for fostering fusion of social and technological innovations for improving the lives of marginalized people in Bangladesh.</p>\r\n<p><br /><strong>Incorporation</strong>&nbsp;<br />Dnet is registered (Reg.No. S-2601(14)/2000) as a research institution under the Societies Act XXI of 1860 with the Registrar of Joint Stock Companies &amp; Firms and NGO Affairs Bureau (Reg. 1918, dated 07thApril, 2004) for receiving foreign donations and grants</p>', '<p><strong>The Identity and Working Philosophy</strong></p>\r\n<p><strong>Identity:</strong></p>\r\n<p>Dnet is a non-profit Social Enterprise fostering innovations for empowerment of marginalized communities with special emphasis on women and children focusing on technology and access to information and knowledge.<br />As a non-profit social enterprise, Dnet thrives to apply commercial strategies, where applicable, to maximize improvements in human and environmental well-being, rather than maximizing profits for external shareholders.&nbsp;<br />Dnet, through its activities, envisages to contribute towards building a social economy.<br /><br /><strong>Working Philosophy:</strong></p>\r\n<p>Whatever goods, services and social values we create, Dnet does ethical review including their creation processes. Dnet always looks for social purpose(s), and evidences of social impact. Dnet works for democratization of management and governance by inclusion of our human, social and financial capital to our primary stakeholders (producers, employees, customers, and service users).<br /><br /><strong>The Journey</strong>&nbsp;<br />On January 13 2001, a group of young professionals met to discuss how information and communication technology (ICT) can be integrated into the economic development process of Bangladesh and beyond her geographic boundary. The outcome of the meeting was a decision to build a dedicated institution for research on, interalia, mainstreaming ICT in poverty alleviation and economic development.<br /><br />Since then Dnet has evolved into a social enterprise for fostering fusion of social and technological innovations for improving the lives of marginalized people in Bangladesh.</p>\r\n<p><br /><strong>Incorporation</strong>&nbsp;<br />Dnet is registered (Reg.No. S-2601(14)/2000) as a research institution under the Societies Act XXI of 1860 with the Registrar of Joint Stock Companies &amp; Firms and NGO Affairs Bureau (Reg. 1918, dated 07thApril, 2004) for receiving foreign donations and grants</p>', NULL, NULL, 'admin', '2015-01-12 23:17:48', 'admin', '2016-11-19 04:17:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `slide_id` int(11) NOT NULL,
  `image_caption_en` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `image_caption_bn` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `slide_image` varchar(100) DEFAULT NULL,
  `slide_thumb` varchar(100) DEFAULT NULL,
  `create_user_id` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_user_id` varchar(50) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `enable` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`slide_id`, `image_caption_en`, `image_caption_bn`, `slide_image`, `slide_thumb`, `create_user_id`, `create_date`, `update_user_id`, `update_date`, `enable`) VALUES
(10, 'dNet', 'dNet', '02a7ba3b14fb4763c9237d9c46c10102.png', '02a7ba3b14fb4763c9237d9c46c10102_thumb.png', 'admin', '2015-01-29 22:12:18', 'admin', '2016-11-18 04:25:02', 1),
(11, 'dNet', 'dNet', '3815de5cc9ccce0d261f88313ae725ed.png', '3815de5cc9ccce0d261f88313ae725ed_thumb.png', 'admin', '2016-11-18 04:27:17', 'admin', '2016-11-18 04:29:47', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apninv_a3m_account`
--
ALTER TABLE `apninv_a3m_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apninv_a3m_account_details`
--
ALTER TABLE `apninv_a3m_account_details`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `apninv_a3m_acl_permission`
--
ALTER TABLE `apninv_a3m_acl_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apninv_action_log`
--
ALTER TABLE `apninv_action_log`
  ADD PRIMARY KEY (`action_id`);

--
-- Indexes for table `apninv_card_inventory`
--
ALTER TABLE `apninv_card_inventory`
  ADD PRIMARY KEY (`card_id`);

--
-- Indexes for table `apninv_recharge_attempt`
--
ALTER TABLE `apninv_recharge_attempt`
  ADD PRIMARY KEY (`attempt_id`);

--
-- Indexes for table `apninv_recharge_history`
--
ALTER TABLE `apninv_recharge_history`
  ADD PRIMARY KEY (`recharge_id`);

--
-- Indexes for table `apninv_ref_country`
--
ALTER TABLE `apninv_ref_country`
  ADD PRIMARY KEY (`alpha2`),
  ADD UNIQUE KEY `alpha3` (`alpha3`);

--
-- Indexes for table `apninv_ref_currency`
--
ALTER TABLE `apninv_ref_currency`
  ADD PRIMARY KEY (`alpha`),
  ADD KEY `numeric` (`numeric`);

--
-- Indexes for table `apninv_ref_iptocountry`
--
ALTER TABLE `apninv_ref_iptocountry`
  ADD KEY `country_code` (`country_code`),
  ADD KEY `ip_to` (`ip_to`),
  ADD KEY `ip_from` (`ip_from`);

--
-- Indexes for table `apninv_ref_language`
--
ALTER TABLE `apninv_ref_language`
  ADD PRIMARY KEY (`one`),
  ADD KEY `two` (`two`);

--
-- Indexes for table `apninv_ref_zoneinfo`
--
ALTER TABLE `apninv_ref_zoneinfo`
  ADD PRIMARY KEY (`zoneinfo`),
  ADD KEY `country` (`country`);

--
-- Indexes for table `aponjon_member`
--
ALTER TABLE `aponjon_member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`slide_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apninv_a3m_account`
--
ALTER TABLE `apninv_a3m_account`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `apninv_a3m_acl_permission`
--
ALTER TABLE `apninv_a3m_acl_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `apninv_action_log`
--
ALTER TABLE `apninv_action_log`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `apninv_card_inventory`
--
ALTER TABLE `apninv_card_inventory`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `apninv_recharge_attempt`
--
ALTER TABLE `apninv_recharge_attempt`
  MODIFY `attempt_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `apninv_recharge_history`
--
ALTER TABLE `apninv_recharge_history`
  MODIFY `recharge_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aponjon_member`
--
ALTER TABLE `aponjon_member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `slide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
