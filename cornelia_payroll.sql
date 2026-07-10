-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2026 at 02:56 PM
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
-- Database: `cornelia_payroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deduction_settings`
--

CREATE TABLE `deduction_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Front of House', 'Cashiers, servers, customer-facing staff', '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(2, 'Kitchen', 'Cooks, prep staff, dishwashers', '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(3, 'Bar', 'Baristas and beverage staff', '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(4, 'Management', 'Supervisors and managers', '2026-03-26 13:14:59', '2026-03-26 13:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_no` varchar(20) NOT NULL,
  `biometric_pin` varchar(6) DEFAULT NULL,
  `biometric_id` varchar(50) DEFAULT NULL,
  `webauthn_credential_id` text DEFAULT NULL,
  `webauthn_public_key` text DEFAULT NULL,
  `biometric_enrolled` tinyint(1) NOT NULL DEFAULT 0,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `middle_name` varchar(80) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `civil_status` enum('single','married','widowed','separated') DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `employment_type` enum('full_time','part_time','contractual') NOT NULL DEFAULT 'full_time',
  `hire_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive','terminated') NOT NULL DEFAULT 'active',
  `sss_no` varchar(20) DEFAULT NULL,
  `philhealth_no` varchar(20) DEFAULT NULL,
  `pagibig_no` varchar(20) DEFAULT NULL,
  `tin_no` varchar(20) DEFAULT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_no`, `biometric_pin`, `biometric_id`, `webauthn_credential_id`, `webauthn_public_key`, `biometric_enrolled`, `first_name`, `last_name`, `middle_name`, `suffix`, `date_of_birth`, `gender`, `civil_status`, `email`, `phone`, `address`, `emergency_contact_name`, `emergency_contact_number`, `position_id`, `employment_type`, `hire_date`, `end_date`, `status`, `sss_no`, `philhealth_no`, `pagibig_no`, `tin_no`, `daily_rate`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CSB-001', NULL, NULL, NULL, NULL, 0, 'Admin', 'User', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 'full_time', '2024-01-01', NULL, 'active', NULL, NULL, NULL, NULL, 1100.00, '2026-03-26 13:15:01', '2026-06-02 08:56:56', '2026-06-02 08:56:56'),
(2, 'CSB-96857', NULL, NULL, NULL, NULL, 0, 'Kevin Gales', 'Satur', 'paayas', NULL, NULL, NULL, NULL, NULL, '09660813500', NULL, NULL, NULL, 8, 'contractual', '2026-03-26', NULL, 'active', NULL, NULL, NULL, NULL, 1923.08, '2026-03-26 13:17:14', '2026-05-14 14:09:12', '2026-05-14 14:09:12'),
(3, 'CSB-0980908', NULL, NULL, NULL, NULL, 0, 'DANE', 'SATUR', 'PAAYAS', NULL, NULL, NULL, NULL, NULL, '09660813500', NULL, NULL, NULL, 9, 'full_time', '2026-05-14', NULL, 'active', '123', '123', '123', '123', 1538.46, '2026-05-14 14:10:12', '2026-06-02 08:56:50', '2026-06-02 08:56:50'),
(4, 'CSB-', NULL, NULL, NULL, NULL, 0, 'kevin', 'SATUR', 'PAAYAS', NULL, NULL, NULL, NULL, NULL, '09660813500', NULL, NULL, NULL, 1, 'full_time', '2026-01-14', NULL, 'active', '123', '123', '123', '123', 884.62, '2026-05-14 14:13:35', '2026-06-02 08:56:52', '2026-06-02 08:56:52'),
(5, 'CSB-efasd', NULL, 'CSB--FP', 'System.Byte[]', NULL, 1, 'fingerprint', 'scanning', 'yes', NULL, NULL, NULL, NULL, NULL, '213124123', NULL, NULL, NULL, 1, 'full_time', '2026-05-31', NULL, 'active', NULL, NULL, NULL, NULL, 1153.85, '2026-05-31 08:29:02', '2026-06-02 08:56:55', '2026-06-02 08:56:55'),
(6, 'CSB-234234', '123457', 'CSB--FP', 'System.Byte[]', NULL, 1, 'KEVINSADAS', 'SATURRRR', 'ASDASD', NULL, NULL, NULL, NULL, NULL, '097765890856', NULL, NULL, NULL, 1, 'full_time', '2026-05-31', NULL, 'active', NULL, NULL, NULL, NULL, 1573.08, '2026-05-31 10:10:05', '2026-06-02 08:56:53', '2026-06-02 08:56:53'),
(7, 'CSB-423423423', '3204', '423423423-FP', '4a155353323100000356590408050709ced000002f57760100004783fb1d7b56d2004364ab0034003832ad00b00027640a00d756323e980025018c5b6e562901cd5d8100b500063277003e01cf526f0065560f642f0065002c457656c0001c6479003a0049326d00a3000564b7009756756455009a00376480567d000c64aa00f8014908b7003c014552b7004956fa648e0068010b33a056c4002f64ae007b00a2324e00bc00e764450026574e5cc0001a01f841b95687008f47820087015216900067007d6444006457523df9297efbf717fd5972002b0895886cfa29cde796b3f8df047cfe5d5693f9ad81e5021f7cb7ad6889ce92c083a40682d5f092a281ea0b7afb88a5b2779dd91a182f3671a8837ff60b4d90c816b1be5c70e56b868458ee85d6d70d2c0b4d92089b3751df00b2fab8ffcf09d5a8b7faa683bbf1f17f65285e003893210a4ff74b21987cfa7262f5bbe6a198ac85310a818357f999a0bbf6a281426b4a15bfd1ec07d6847179c4725e243187f205e6871ef3fc4121310101d817ed0903d12d77786ac00ac59c3bd6c0c1c2c15ac2c3007711f5fdc0fdc006c56d4e2763c10600754e3838fc5b01b85d835a75af860e56a56689c0907e4ec208568c6880847cc1be0303fd6b10ff060084abfd2da90a007c707a79b46a08561ac5d64441fff40503d275033b050086bc09c3060600847e0c4af50d030197edfefc3efff6540b56509e6069c064c3006cf7fc28fd0300676067c25b016fa7002aff05fcfca9570700adb31e38c0fd0c060052bee4ffdeff0b564ac157ffc1c1044b085676c30f1bfd58fe0b03f1c6272f4a5409c5a2ca66c1ffc03cc115c5ccccffc1c2c3c07ec507c28834c0c0c00c00761470c697c0c15355140013bfaad0767ac2c3c26ab60d0328d93aff38ff503a4a0e5676d94c5654ff8cc00f5679e240fe5146043e0556aaf537fe4f03d579061ac10610c01d3afbc11046821ec666c2c301c8af3d5aff1510991f06c0c2a99ac3c4c697c0046450531195274cc057c310997142fec2fdc11cd5222786c1ff5d40fefd3efefdaafc38ffc1ffff3ac1fca80410a63f4942c010af6947ff460510b4fa495b5311b93f403604d57f4005c1c20510744495fffc9305108444493fcf103006d6c25062ff0ac56ceb066aff45ff5242c51240570001007800e48f0003444452000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', NULL, 1, 'Kevin Gale.', 'Satur', 'Paayas', NULL, NULL, NULL, NULL, NULL, '0966081350', NULL, NULL, NULL, 1, 'full_time', '2026-06-02', NULL, 'active', NULL, NULL, NULL, NULL, 769.23, '2026-06-02 09:34:42', '2026-06-02 09:34:42', NULL),
(8, 'CSB-54252', '123456', '-FP', '4a2b53533231000003686a0408050709ced000002f69760100005f83951ac868d7003a64e3007600270cb9009700f9647800fa6851648400b9002164b3687e006c640f0134003525ed002f01435bad006068e64fd400a700d564c168e6004564b70035004b0cf800e9003a642500026942648200df001864ed6820014264d6008d00000cc3003900f9640300e1684164ac00e1009d64d0688f007764b4003a00d30c90009200ea64cb01d668ac4e16010b017d2595684f00eb6498ff5cf5a19cf8150eaeb6eff88e7eef9fe8d80b9883380de1626bfebf8e4976bb85259ed8fe8183098bc000f468228e8bfdbfff6e7f8e85309a0618180664fc8a9d61fb1d04817890062ae7abf91e096e010ff56592a787b39685002b0128e4180efb1bd79b64faf478d28f9cfe100b64f952793416291b496ab883996699837988858f47819d9ae70a117b3577caf67eef6f83377887f4c205c0ea1a84d903202fc4020d73ac0900b4246dbdc0656e01a323696bc1cf00cd4c706ac2c0ff53cd00e8428189710500c6fcf73b6e01d847fafd41c900d3237cc2715cffc33b0603b04c06c0301301ed7aa3aac0c1c16f837099c00a6905428c8866c3c300913b685bc20300aeba6bc26f01cf8d809980d6012217a1c2c269c2c0067273a9550b00d78f063afdfd58ffc1c00d009551ed5d5d283207008e97a7c1fc0c0400b59b6d78c800bef3fbfffffdfefd3bc1ffa8c1c015012b98628380ebc0c1c26ac0c0b406038a9e1329fe0e0018a180eb88c076590a0010a70094293efe0e00cd6c80c1fb7660c0ff1401e39ea1e9c387c4ffc18897650968e7b21cfefeff84760868e4b724fec040a5c0056881be5ec0c05cc900c5ba81c8ff905b39c800cdb231fd37ff3e47d30087b6db3efefdfefb3bfdfd97ffffc1ff6d36cf00ae8a56c0c2c04441d30121aab18cc09dc2c1065cc10cc1c1c00d00cd2140665ac1ffff530c0003e5432754ffff470b0072e059ab50c135c00c0004eb40a82ec1405a08003ceb342d420a00b3f34f93c02c970b00bcfb50c005c1fda92e0a10e007403a43c3973a1510ee1bc00574c1aac2c5c3c4c2c00470506f11ec234c6a37c310f24b3cc0401b1066e0dcc2a8c04440fbfcfb38fcc296c0fffeffc1c03affc36e11ec3240ff3ede106b5bdf7cffc0c0ffebf9f995fcc0fdc2fec005ffc397feff524200128601026901c000ea4c00c512463a000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', NULL, 1, 'rox', 'cach', 'lub', NULL, NULL, NULL, NULL, NULL, '0912312312', NULL, NULL, NULL, 3, 'full_time', '2026-06-02', NULL, 'active', NULL, NULL, NULL, NULL, 769.23, '2026-06-02 09:43:42', '2026-06-14 14:43:22', '2026-06-14 14:43:22'),
(11, 'CSB-010101231', '000000', '-FP', '4a69535332310000032a290408050709ced000002f2b760100004e83d719b42acb009264c1002600254ea0000b0133642e00ef2aa6649c0038018f64d62a69009564c70090013b00bf004800965856002e2a8245b300df005964c32abb002164e9000600254ecc008f0098646f003d2b3c64bc006100d364ae2a5601385d8a008b007e67a700c7001e647f00ad2a9164b8001001ea64552ab80066648500ff01c36379004401bc2559005a2b4d4003015f00614e765ec47135f9e58cc897fd20540692eb1f985f8261a9660e490cb58ebf007aa9930236fec50323fdc5c52c82120283158f0914c7226ff178ad0d54830fb9b491cd079a822307d4bf4309b206367b10f4822c9bf02d8275835a80c6c604fe4503cefdc6e91043688c8d8751ffd97336294f74d9fe1b085af4206122d72bcf2504787ac5afa8fcba76da73f414093af70457105f93860fd40bef00202e010119172b2d01c7211aff46070402283b29c0c009001b2227eb42510400992fca37052a7d3803fffec03a09034747f728fe5703c5874daac30b00c44e1c055047740a00c0621cc18356563e015b62f0fec005fc30d569c155c06013c55f69de3a354062c0ff900a037c74f430384013c5587cde3436feff57530552102a538df1ff3147f3fe5e6015004c9cf0c0f4ffc303c1c0fefec1c00560052b15feabc16b0cc41a848dc1c2c047c2c1a30f03b6251038ff4557074e092abbab9790c1c2076a092ab9b1938b935ccf00c3991635c0fe7605c5c4bf08c1550b00b6c55fc392a26e0a004ae9de3a412fea0b00bbcd22643bc0c34a15004acfe2c138fe33d5fd2fc0c0545ece00b1f4a3c2c3c4c4c004c3c3ea0800c4e320c0386bc3e80800c0e629c09e670b2a87fe171efe3dc3107d2e5bc3c2c2c308d59e0588c5c7c6c28d12c554faf031fefcfdfdc139fffce8fec0ffc207104e0a2ed7fefe2a0b10b6ceadc1e9c0c4c6c1c38bc110852636390810a40deefe414705109f0e3143c210bb39355c5d0710bdd629417d1210550ad3ffeffef8d7ffffffffc0c005ff8029118438baff04d57a156a6d0f105f22c93bfffed7fdf9fffefdc03a64073a9a3c492a041062423e69534200124301c40103a0001a3c00001280520000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', NULL, 1, 'ROXAINNE JOY', 'CACHERO', 'LUBINTO', NULL, NULL, NULL, NULL, NULL, '0902027119', NULL, NULL, NULL, 4, 'full_time', '2026-06-02', NULL, 'active', NULL, NULL, NULL, NULL, 1538.46, '2026-06-08 14:42:30', '2026-06-14 14:43:08', '2026-06-14 14:43:08');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `holiday_date` date NOT NULL,
  `type` enum('regular','special_non_working','special_working') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `holiday_date`, `type`, `created_at`) VALUES
(1, 'New Year\'s Day', '2025-01-01', 'regular', '2026-03-26 13:15:00'),
(2, 'People Power Anniversary', '2025-02-25', 'special_non_working', '2026-03-26 13:15:00'),
(3, 'Araw ng Kagitingan', '2025-04-09', 'regular', '2026-03-26 13:15:00'),
(4, 'Maundy Thursday', '2025-04-17', 'regular', '2026-03-26 13:15:00'),
(5, 'Good Friday', '2025-04-18', 'regular', '2026-03-26 13:15:00'),
(6, 'Labor Day', '2025-05-01', 'regular', '2026-03-26 13:15:00'),
(7, 'Independence Day', '2025-06-12', 'regular', '2026-03-26 13:15:00'),
(8, 'Ninoy Aquino Day', '2025-08-21', 'special_non_working', '2026-03-26 13:15:01'),
(9, 'National Heroes Day', '2025-08-25', 'regular', '2026-03-26 13:15:01'),
(10, 'All Saints Day', '2025-11-01', 'special_non_working', '2026-03-26 13:15:01'),
(11, 'Bonifacio Day', '2025-11-30', 'regular', '2026-03-26 13:15:01'),
(12, 'Immaculate Conception', '2025-12-08', 'special_non_working', '2026-03-26 13:15:01'),
(13, 'Christmas Day', '2025-12-25', 'regular', '2026-03-26 13:15:01'),
(14, 'Rizal Day', '2025-12-30', 'regular', '2026-03-26 13:15:01'),
(15, 'New Year\'s Eve', '2025-12-31', 'special_non_working', '2026-03-26 13:15:01');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `total_days` decimal(4,1) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `is_paid`, `created_at`) VALUES
(1, 'Sick Leave', 1, '2026-03-26 13:15:00'),
(2, 'Vacation Leave', 1, '2026-03-26 13:15:00'),
(3, 'Emergency Leave', 1, '2026-03-26 13:15:00'),
(4, 'Unpaid Leave', 0, '2026-03-26 13:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000001_create_departments_table', 1),
(2, '2024_01_01_000002_create_positions_table', 1),
(3, '2024_01_01_000003_create_employees_table', 1),
(4, '2024_01_01_000004_create_shifts_table', 1),
(5, '2024_01_01_000005_create_shift_assignments_table', 1),
(6, '2024_01_01_000006_create_time_logs_table', 1),
(7, '2024_01_01_000007_create_leave_types_table', 1),
(8, '2024_01_01_000008_create_leave_requests_table', 1),
(9, '2024_01_01_000009_create_payroll_periods_table', 1),
(10, '2024_01_01_000010_create_payroll_records_table', 1),
(11, '2024_01_01_000011_create_holidays_table', 1),
(12, '2024_01_01_000012_create_users_table', 1),
(13, '2024_01_01_000013_add_username_to_users', 1),
(14, '2024_01_01_000014_create_password_change_requests_table', 1),
(15, '2024_01_01_000015_add_soft_deletes_to_employees', 1),
(16, '2024_01_01_000016_create_deduction_settings_table', 1),
(17, '2024_01_01_000017_add_biometric_pin_to_employees', 1),
(18, '2026_03_25_015126_create_sessions_table', 1),
(19, '2026_03_25_015739_create_cache_table', 1),
(20, '2026_05_25_000001_add_biometrics_to_employees', 2),
(21, '2026_06_08_000001_add_personal_fields_to_employees (1)', 3),
(22, '2026_06_08_000001_add_personal_fields_to_employees', 5),
(23, '2026_06_08_000002_add_employee_role_to_users', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_change_requests`
--

CREATE TABLE `password_change_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `new_password_hash` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_periods`
--

CREATE TABLE `payroll_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `pay_date` date NOT NULL,
  `status` enum('open','processing','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_periods`
--

INSERT INTO `payroll_periods` (`id`, `period_start`, `period_end`, `pay_date`, `status`, `created_at`, `updated_at`) VALUES
(1, '2026-05-01', '2026-05-15', '2026-05-15', 'closed', '2026-05-09 10:15:26', '2026-05-14 14:11:12'),
(2, '2026-05-16', '2026-05-31', '2026-05-31', 'closed', '2026-05-09 10:15:26', '2026-05-14 14:10:55'),
(3, '2026-05-01', '2026-05-31', '2026-05-31', 'closed', '2026-05-14 14:12:19', '2026-05-14 14:12:27'),
(4, '2026-04-14', '2026-05-13', '2026-05-14', 'closed', '2026-05-14 14:13:15', '2026-05-14 14:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_records`
--

CREATE TABLE `payroll_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_period_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `days_worked` decimal(5,2) NOT NULL DEFAULT 0.00,
  `basic_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `overtime_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `holiday_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `night_diff_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `allowances` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gross_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sss_contribution` decimal(10,2) NOT NULL DEFAULT 0.00,
  `philhealth` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pagibig` decimal(10,2) NOT NULL DEFAULT 0.00,
  `withholding_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `late_deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `absent_deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','released') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_records`
--

INSERT INTO `payroll_records` (`id`, `payroll_period_id`, `employee_id`, `days_worked`, `basic_pay`, `overtime_pay`, `holiday_pay`, `night_diff_pay`, `allowances`, `gross_pay`, `sss_contribution`, `philhealth`, `pagibig`, `withholding_tax`, `late_deduction`, `absent_deduction`, `other_deductions`, `total_deductions`, `net_pay`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:10:54', '2026-05-14 14:10:54'),
(2, 2, 3, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:10:54', '2026-05-14 14:10:54'),
(3, 1, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:11:12', '2026-05-14 14:11:12'),
(4, 1, 3, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:11:12', '2026-05-14 14:11:12'),
(5, 3, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:12:27', '2026-05-14 14:12:27'),
(6, 3, 3, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:12:27', '2026-05-14 14:12:27'),
(7, 4, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:13:51', '2026-05-14 14:13:51'),
(8, 4, 3, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:13:51', '2026-05-14 14:13:51'),
(9, 4, 4, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, '2026-05-14 14:13:51', '2026-05-14 14:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `base_daily_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `department_id`, `title`, `base_daily_rate`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cashier', 610.00, '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(2, 1, 'Server', 610.00, '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(3, 2, 'Line Cook', 650.00, '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(4, 2, 'Head Chef', 900.00, '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(5, 2, 'Dishwasher', 575.00, '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(6, 3, 'Barista', 635.00, '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(7, 3, 'Senior Barista', 700.00, '2026-03-26 13:14:59', '2026-03-26 13:14:59'),
(8, 4, 'Supervisor', 850.00, '2026-03-26 13:15:00', '2026-03-26 13:15:00'),
(9, 4, 'Manager', 1100.00, '2026-03-26 13:15:00', '2026-03-26 13:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `break_minutes` int(10) UNSIGNED NOT NULL DEFAULT 60,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `name`, `start_time`, `end_time`, `break_minutes`, `created_at`, `updated_at`) VALUES
(1, 'Opening Shift', '06:00:00', '14:00:00', 60, '2026-03-26 13:15:00', '2026-03-26 13:15:00'),
(2, 'Mid Shift', '10:00:00', '18:00:00', 60, '2026-03-26 13:15:00', '2026-03-26 13:15:00'),
(3, 'Closing Shift', '14:00:00', '22:00:00', 60, '2026-03-26 13:15:00', '2026-03-26 13:15:00'),
(4, 'Full Day', '08:00:00', '17:00:00', 60, '2026-03-26 13:15:00', '2026-03-26 13:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `shift_assignments`
--

CREATE TABLE `shift_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `work_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_logs`
--

CREATE TABLE `time_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `log_date` date NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `break_out` datetime DEFAULT NULL,
  `break_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `break2_out` datetime DEFAULT NULL,
  `break2_in` datetime DEFAULT NULL,
  `total_hours_worked` decimal(5,2) NOT NULL DEFAULT 0.00,
  `overtime_hours` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_late` tinyint(1) NOT NULL DEFAULT 0,
  `late_minutes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_logs`
--

INSERT INTO `time_logs` (`id`, `employee_id`, `log_date`, `time_in`, `break_out`, `break_in`, `time_out`, `break2_out`, `break2_in`, `total_hours_worked`, `overtime_hours`, `is_late`, `late_minutes`, `remarks`, `created_at`, `updated_at`) VALUES
(13, 8, '2026-06-02', '2026-06-02 07:55:14', '2026-06-02 12:02:05', '2026-06-02 13:04:18', '2026-06-02 13:04:32', NULL, NULL, 4.12, 0.00, 0, 0, NULL, '2026-06-02 09:53:32', '2026-06-02 05:04:32'),
(14, 7, '2026-06-02', '2026-06-02 08:00:43', '2026-06-02 12:02:25', '2026-06-02 13:04:01', '2026-06-02 13:04:28', NULL, NULL, 4.04, 0.00, 1, 0, NULL, '2026-06-02 00:00:43', '2026-06-02 05:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','hr','manager','viewer','employee') NOT NULL DEFAULT 'employee',
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `employee_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin', 'admin@corneliastreetbistro.com', '$2y$12$wpOwLMLl6I89IS1.B8rjredHueQiABR8xy2hpVxB2hjrHmQyM9yHG', 'admin', 1, 'I5eFvuqvAwzt1sb6K3ygcAUEO78xoPsNdK7vugRPWY6FkuL6hhL8WFLn55Me', '2026-03-26 13:15:01', '2026-03-26 13:15:01'),
(2, 'ROXAINNE JOY CACHERO', 'littlecookie_plaze', 'littlecookie_plaze@cornelia.local', '$2y$12$X19emOd5d73VSGtCxYMShu.KJakl21N40PifPpqlyVsEiZc42TR7i', 'employee', 11, NULL, '2026-06-08 14:42:31', '2026-06-08 14:42:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `deduction_settings`
--
ALTER TABLE `deduction_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deduction_settings_key_unique` (`key`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_no_unique` (`employee_no`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD UNIQUE KEY `employees_biometric_pin_unique` (`biometric_pin`),
  ADD KEY `employees_position_id_foreign` (`position_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `holidays_holiday_date_unique` (`holiday_date`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_employee_id_foreign` (`employee_id`),
  ADD KEY `leave_requests_leave_type_id_foreign` (`leave_type_id`),
  ADD KEY `leave_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_change_requests`
--
ALTER TABLE `password_change_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_change_requests_user_id_foreign` (`user_id`),
  ADD KEY `password_change_requests_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_records`
--
ALTER TABLE `payroll_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payroll_records_payroll_period_id_employee_id_unique` (`payroll_period_id`,`employee_id`),
  ADD KEY `payroll_records_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `positions_department_id_foreign` (`department_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_assignments`
--
ALTER TABLE `shift_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shift_assignments_employee_id_work_date_unique` (`employee_id`,`work_date`),
  ADD KEY `shift_assignments_shift_id_foreign` (`shift_id`);

--
-- Indexes for table `time_logs`
--
ALTER TABLE `time_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_logs_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_employee_id_foreign` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deduction_settings`
--
ALTER TABLE `deduction_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `password_change_requests`
--
ALTER TABLE `password_change_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll_records`
--
ALTER TABLE `payroll_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shift_assignments`
--
ALTER TABLE `shift_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_logs`
--
ALTER TABLE `time_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`);

--
-- Constraints for table `password_change_requests`
--
ALTER TABLE `password_change_requests`
  ADD CONSTRAINT `password_change_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `password_change_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_records`
--
ALTER TABLE `payroll_records`
  ADD CONSTRAINT `payroll_records_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `payroll_records_payroll_period_id_foreign` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`);

--
-- Constraints for table `positions`
--
ALTER TABLE `positions`
  ADD CONSTRAINT `positions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `shift_assignments`
--
ALTER TABLE `shift_assignments`
  ADD CONSTRAINT `shift_assignments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shift_assignments_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`);

--
-- Constraints for table `time_logs`
--
ALTER TABLE `time_logs`
  ADD CONSTRAINT `time_logs_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
