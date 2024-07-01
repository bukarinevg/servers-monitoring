-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: mysql
-- Время создания: Июл 01 2024 г., 19:07
-- Версия сервера: 5.7.41
-- Версия PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `monitoring`
--

-- --------------------------------------------------------

--
-- Структура таблицы `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20240628200238, 'CreateWebServerTable', '2024-07-01 21:48:00', '2024-07-01 21:48:00', 0),
(20240628200239, 'CreateWebServerWorkTable', '2024-07-01 21:48:00', '2024-07-01 21:48:00', 0),
(20240629005335, 'AddColumnsToWebServerWork', '2024-07-01 21:48:00', '2024-07-01 21:48:00', 0),
(20240629034452, 'AddCountFieldToWebServer', '2024-07-01 21:48:00', '2024-07-01 21:48:00', 0),
(20240629034843, 'AddStatusMessageToWebServer', '2024-07-01 21:48:00', '2024-07-01 21:48:01', 0),
(20240701110359, 'AddTypeToWebServerTable', '2024-07-01 21:48:01', '2024-07-01 21:48:01', 0),
(20240701134639, 'AddUserNamePasswordConnection', '2024-07-01 21:48:01', '2024-07-01 21:48:01', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `web_server`
--

CREATE TABLE `web_server` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `status_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int(1) DEFAULT '0',
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `web_server`
--

INSERT INTO `web_server` (`id`, `name`, `path`, `port`, `status`, `created_at`, `updated_at`, `count`, `status_message`, `type`, `username`, `password`) VALUES
(1, 'check mine server', 'evgbukar.com', NULL, 1, 1719859774, 1719860066, 5, 'Healthy.', 0, NULL, NULL),
(3, 'check ftp server', 'ftp.dlptest.com', 21, 1, 1719859819, 1719860549, 5, 'Healthy.', 1, 'dlpuser', 'rNrKYTX9g7z3RgJRmxWuGHbeu'),
(4, 'check ftp server', 'ftp.dlptestwrong.com', 22, 0, 1719859843, 1719860072, 2, 'Unhealthy.', 1, 'dlpuser', 'rNrKYTX9g7z3RgJRmxWuGHbeu'),
(5, 'check disabled server', 'http://evgbuasdkar.com/', NULL, 0, 1719859867, 1719860009, 2, 'Unhealthy.', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `web_server_work`
--

CREATE TABLE `web_server_work` (
  `id` int(11) UNSIGNED NOT NULL,
  `web_server_id` int(11) UNSIGNED NOT NULL,
  `workload` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `status_code` int(11) DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `web_server_work`
--

INSERT INTO `web_server_work` (`id`, `web_server_id`, `workload`, `status`, `created_at`, `updated_at`, `status_code`, `message`) VALUES
(2, 1, NULL, 1, 1719859827, NULL, 200, 'OK'),
(4, 1, NULL, 1, 1719859886, NULL, 200, 'OK'),
(6, 5, NULL, 0, 1719859886, NULL, 504, 'Gateway Timeout.'),
(7, 3, NULL, 0, 1719859887, NULL, 500, 'Failure'),
(8, 1, NULL, 1, 1719859947, NULL, 200, 'OK'),
(10, 5, NULL, 0, 1719859947, NULL, 504, 'Gateway Timeout.'),
(11, 3, NULL, 0, 1719859947, NULL, 500, 'Failure'),
(12, 4, NULL, 0, 1719859947, NULL, 500, 'Failure'),
(13, 1, NULL, 1, 1719860006, NULL, 200, 'OK'),
(15, 5, NULL, 0, 1719860006, NULL, 504, 'Gateway Timeout.'),
(16, 3, NULL, 0, 1719860008, NULL, 500, 'Failure'),
(17, 4, NULL, 0, 1719860010, NULL, 500, 'Failure'),
(18, 1, NULL, 1, 1719860066, NULL, 200, 'OK'),
(20, 5, NULL, 0, 1719860066, NULL, 504, 'Gateway Timeout.'),
(21, 3, NULL, 0, 1719860069, NULL, 500, 'Failure'),
(22, 4, NULL, 0, 1719860069, NULL, 500, 'Failure'),
(23, 1, NULL, 1, 1719860123, NULL, 200, 'OK'),
(25, 3, NULL, 0, 1719860126, NULL, 500, 'Failure'),
(26, 4, NULL, 0, 1719860126, NULL, 500, 'Failure'),
(27, 1, NULL, 1, 1719860190, NULL, 200, 'OK'),
(28, 5, NULL, 0, 1719860190, NULL, 504, 'Gateway Timeout.'),
(29, 1, NULL, 1, 1719860248, NULL, 200, 'OK'),
(30, 5, NULL, 0, 1719860248, NULL, 504, 'Gateway Timeout.'),
(31, 3, NULL, 1, 1719860249, NULL, 200, 'Success'),
(32, 4, NULL, 0, 1719860249, NULL, 500, 'Failure'),
(33, 3, NULL, 0, 1719860251, NULL, 500, 'Failure'),
(34, 4, NULL, 0, 1719860251, NULL, 500, 'Failure'),
(35, 1, NULL, 1, 1719860310, NULL, 200, 'OK'),
(36, 5, NULL, 0, 1719860310, NULL, 504, 'Gateway Timeout.'),
(37, 3, NULL, 1, 1719860311, NULL, 200, 'Success'),
(38, 4, NULL, 0, 1719860311, NULL, 500, 'Failure'),
(39, 1, NULL, 1, 1719860369, NULL, 200, 'OK'),
(40, 5, NULL, 0, 1719860369, NULL, 504, 'Gateway Timeout.'),
(41, 3, NULL, 1, 1719860370, NULL, 200, 'Success'),
(42, 4, NULL, 0, 1719860370, NULL, 500, 'Failure'),
(43, 1, NULL, 1, 1719860431, NULL, 200, 'OK'),
(44, 5, NULL, 0, 1719860431, NULL, 504, 'Gateway Timeout.'),
(45, 3, NULL, 1, 1719860432, NULL, 200, 'Success'),
(46, 4, NULL, 0, 1719860432, NULL, 500, 'Failure'),
(47, 1, NULL, 1, 1719860488, NULL, 200, 'OK'),
(48, 5, NULL, 0, 1719860489, NULL, 504, 'Gateway Timeout.'),
(49, 3, NULL, 1, 1719860489, NULL, 200, 'Success'),
(50, 4, NULL, 0, 1719860489, NULL, 500, 'Failure'),
(51, 1, NULL, 1, 1719860548, NULL, 200, 'OK'),
(52, 5, NULL, 0, 1719860549, NULL, 504, 'Gateway Timeout.'),
(53, 3, NULL, 1, 1719860549, NULL, 200, 'Success'),
(54, 4, NULL, 0, 1719860549, NULL, 500, 'Failure'),
(55, 1, NULL, 1, 1719860610, NULL, 200, 'OK'),
(56, 5, NULL, 0, 1719860610, NULL, 504, 'Gateway Timeout.'),
(57, 3, NULL, 1, 1719860610, NULL, 200, 'Success'),
(58, 4, NULL, 0, 1719860610, NULL, 500, 'Failure'),
(59, 1, NULL, 1, 1719860670, NULL, 200, 'OK'),
(60, 5, NULL, 0, 1719860670, NULL, 504, 'Gateway Timeout.'),
(61, 3, NULL, 1, 1719860670, NULL, 200, 'Success'),
(62, 4, NULL, 0, 1719860670, NULL, 500, 'Failure');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `web_server`
--
ALTER TABLE `web_server`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `web_server_work`
--
ALTER TABLE `web_server_work`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_server_id` (`web_server_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `web_server`
--
ALTER TABLE `web_server`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `web_server_work`
--
ALTER TABLE `web_server_work`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `web_server_work`
--
ALTER TABLE `web_server_work`
  ADD CONSTRAINT `web_server_work_ibfk_1` FOREIGN KEY (`web_server_id`) REFERENCES `web_server` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
