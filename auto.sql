-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Час створення: Сер 17 2017 р., 13:55
-- Версія сервера: 10.1.13-MariaDB
-- Версія PHP: 5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `auto`
--

-- --------------------------------------------------------

--
-- Структура таблиці `brands`
--

CREATE TABLE `brands` (
  `id` int(8) NOT NULL,
  `data` varchar(200) NOT NULL,
  `name` varchar(14) NOT NULL,
  `partner_code` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `brands`
--

INSERT INTO `brands` (`id`, `data`, `name`, `partner_code`) VALUES
(1, '1213', 'ACURA', 1),
(2, '502', 'ALFA ROMEO', 1),
(3, '504', 'AUDI', 1),
(4, '511', 'BMW', 1),
(5, '824', 'BUGATTI', 1),
(6, '849', 'BUICK', 1),
(7, '852', 'CADILLAC', 1),
(8, '602', 'CHEVROLET', 1),
(9, '513', 'CHRYSLER', 1),
(10, '514', 'CITROEN', 1),
(11, '603', 'DACIA', 1),
(12, '649', 'DAEWOO', 1),
(13, '516', 'DAF', 1),
(14, '517', 'DAIHATSU', 1),
(15, '518', 'DAIMLER', 1),
(16, '521', 'DODGE', 1),
(17, '524', 'FIAT', 1),
(18, '525', 'FORD', 1),
(19, '814', 'FORD USA', 1),
(20, '10091', 'GEELY', 1),
(21, '527', 'GMC', 1),
(22, '533', 'HONDA', 1),
(23, '1214', 'HUMMER', 1),
(24, '647', 'HYUNDAI', 1),
(25, '1234', 'INFINITI', 1),
(26, '538', 'ISUZU', 1),
(27, '539', 'IVECO', 1),
(28, '540', 'JAGUAR', 1),
(29, '910', 'JEEP', 1),
(30, '648', 'KIA', 1),
(31, '545', 'LADA', 1),
(32, '746', 'LAMBORGHINI', 1),
(33, '546', 'LANCIA', 1),
(34, '1292', 'LAND ROVER', 1),
(35, '874', 'LEXUS', 1),
(36, '1152', 'LINCOLN', 1),
(37, '809', 'MASERATI', 1),
(38, '222', 'MAYBACH', 1),
(39, '552', 'MAZDA', 1),
(40, '1226', 'MCLAREN', 1),
(41, '553', 'MERCEDES-BENZ', 1),
(42, '1231', 'MINI', 1),
(43, '555', 'MITSUBISHI', 1),
(44, '846', 'MOSKVICH', 1),
(45, '558', 'NISSAN', 1),
(46, '1151', 'OLDSMOBILE', 1),
(47, '561', 'OPEL', 1),
(48, '563', 'PEUGEOT', 1),
(49, '812', 'PONTIAC', 1),
(50, '565', 'PORSCHE', 1),
(51, '566', 'RENAULT', 1),
(52, '748', 'ROLLS-ROYCE', 1),
(53, '568', 'ROVER', 1),
(54, '569', 'SAAB', 1),
(55, '573', 'SEAT', 1),
(56, '575', 'SKODA', 1),
(57, '1149', 'SMART', 1),
(58, '639', 'SSANGYONG', 1),
(59, '576', 'SUBARU', 1),
(60, '577', 'SUZUKI', 1),
(61, '579', 'TOYOTA', 1),
(62, '586', 'VOLVO', 1),
(63, '587', 'VW', 1),
(64, 'https://detali.zp.ua/catalog/10000019-Daewoo/', 'DAEWOO', 2),
(65, 'https://detali.zp.ua/catalog/10000009-azlk/', 'АЗЛК', 2),
(66, 'https://detali.zp.ua/catalog/10000003-vaz/', 'ВАЗ', 2),
(67, 'https://detali.zp.ua/catalog/10000004-gaz/', 'ГАЗ', 2),
(68, 'https://detali.zp.ua/catalog/10000006-zaz/', 'ЗАЗ', 2),
(69, 'https://detali.zp.ua/catalog/10000010-zil/', 'ЗИЛ', 2),
(70, 'https://detali.zp.ua/catalog/10000007-izh/', 'ИЖ', 2),
(71, 'https://detali.zp.ua/catalog/10000008-kamaz/', 'КАМАЗ', 2),
(72, 'https://detali.zp.ua/catalog/10000017-kraz/', 'КРАЗ', 2),
(73, 'https://detali.zp.ua/catalog/10000011-maz/', 'МАЗ', 2),
(74, 'https://detali.zp.ua/catalog/10000005-uaz/', 'УАЗ', 2);

-- --------------------------------------------------------

--
-- Структура таблиці `partners`
--

CREATE TABLE `partners` (
  `id` int(8) NOT NULL,
  `code` int(8) NOT NULL,
  `name` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `partners`
--

INSERT INTO `partners` (`id`, `code`, `name`) VALUES
(1, 1, 'ad'),
(2, 2, 'zp');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data` (`data`),
  ADD KEY `partner_code_fk` (`partner_code`);

--
-- Індекси таблиці `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT для таблиці `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `partner_code_fk` FOREIGN KEY (`partner_code`) REFERENCES `partners` (`code`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
