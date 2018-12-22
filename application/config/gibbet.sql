-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 22 дек 2018 в 12:58
-- Версия на сървъра: 10.0.37-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sstoyano_gibbet`
--

-- --------------------------------------------------------

--
-- Структура на таблица `animals`
--

CREATE TABLE `animals` (
  `id` int(11) NOT NULL,
  `word` text CHARACTER SET utf8 NOT NULL,
  `discrip` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Схема на данните от таблица `animals`
--

INSERT INTO `animals` (`id`, `word`, `discrip`) VALUES
(1, 'Шимпанзе', 'човекоподобна маймуна'),
(2, 'Морско конче', 'насекомо'),
(3, 'Хипопотам', 'агресивен бозайник'),
(4, 'Анаконда', 'тропическо влечуго'),
(5, 'Леопард', 'голяма котка'),
(6, 'Бързолет', 'птица'),
(7, 'Дива котка', 'хищник'),
(8, 'Тарантула', 'вид паяк'),
(9, 'Баракуда', 'морска риба'),
(10, 'Кротушка', 'речна риба'),
(11, 'Стършел', 'жилещо насекомо'),
(12, 'Нилски крокодил', 'земноводно влечуго'),
(13, 'Електрическа змиорка', 'вид морска риба'),
(14, 'Кондор', 'голяма птица'),
(15, 'Костенурка', 'вид влечуго'),
(16, 'Пеликан', 'мигрираща птица'),
(17, 'Зелена мамба', 'вид змия'),
(18, 'Папагал', 'птица'),
(19, 'Летяща хлебарка', 'вид насекомо'),
(20, 'Носорог', 'тревопасно животно'),
(21, 'Прилеп', 'летящ бозайник'),
(22, 'Дървеница', 'пълзящо насекомо'),
(23, 'Усойница', 'вид змия'),
(24, 'Мравояд', 'бозайник'),
(25, 'Гъсеница', 'стадий на насекомо');

-- --------------------------------------------------------

--
-- Структура на таблица `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `word` text COLLATE utf8_unicode_ci NOT NULL,
  `discrip` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Схема на данните от таблица `cities`
--

INSERT INTO `cities` (`id`, `word`, `discrip`) VALUES
(1, 'Пазарджик', 'град в България'),
(2, 'Павликени', 'град в България'),
(3, 'Константинопол', 'древен град'),
(4, 'Таргу Муреш', 'град в Румъния'),
(5, 'Крушевац', 'град в Сърбия'),
(6, 'Крагуевац', 'град в Сърбия'),
(7, 'Сремска Митровица', 'град в Сърбия'),
(8, 'Йоанина', 'град в Гърция'),
(9, 'Каламата', 'град в Гърция'),
(10, 'Амбракия', 'град в Гърция'),
(11, 'Пескара', 'град в Италия'),
(12, 'Бергамо', 'град в Италия'),
(13, 'Катания', 'град в Италия'),
(14, 'Монпелие', 'град във Франция'),
(15, 'Гренобъл', 'град във Франция'),
(16, 'Тулуза', 'град във Франция'),
(17, 'Сарагоса', 'град в Испания'),
(18, 'Валядолид', 'град в Испания'),
(19, 'Севиля', 'град в Испания'),
(20, 'Малага', 'град в Испания'),
(21, 'Велико Търново', 'град в България'),
(22, 'Стара Загора', 'град в България'),
(23, 'Долна Митрополия', 'град в България'),
(24, 'Горна Оряховица', 'град в България'),
(25, 'Санкт Петербург', 'град в Русия'),
(26, 'Хановер', 'град в Германия'),
(27, 'Брауншвайг', 'град в Германия'),
(28, 'Вюрцбург', 'град в Германия'),
(29, 'Манхайм', 'град в Германия'),
(30, 'Шчечин', 'град в Полша');

-- --------------------------------------------------------

--
-- Структура на таблица `plants`
--

CREATE TABLE `plants` (
  `id` int(11) NOT NULL,
  `word` text CHARACTER SET utf8 NOT NULL,
  `discrip` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Схема на данните от таблица `plants`
--

INSERT INTO `plants` (`id`, `word`, `discrip`) VALUES
(1, 'Райграс', 'трева'),
(2, 'Хризантема', 'цвете'),
(3, 'Момина сълза', 'цвете'),
(4, 'Кокиче', 'цвете'),
(5, 'Минзухар', 'цвете'),
(6, 'Кърпи кожух', 'цвете'),
(7, 'Мащерка', 'билка'),
(8, 'Смокиня', 'плодно дърво'),
(9, 'Краставица', 'зеленчук'),
(10, 'Червена боровинка', 'плодно растение'),
(11, 'Дива мента', 'тревисто растение'),
(12, 'Царевица', 'зърнена култура'),
(13, 'Пшеница', 'зърнена култура'),
(14, 'Дива ягода', 'плодно растение'),
(15, 'Пъпеш', 'плодно растение'),
(16, 'Цикория', 'кореноплодно растение'),
(17, 'Морков', 'кореноплодно растение'),
(18, 'Ананас', 'плоден храст'),
(19, 'Финикова палма', 'плодно дърво'),
(20, 'Дива ябълка', 'плодно дърво'),
(21, 'Кокосова палма', 'плодно дърво'),
(22, 'Бяла череша', 'плодно дърво'),
(23, 'Кайсия', 'плодно дърво'),
(24, 'Праскова нектарина', 'плодно дърво'),
(25, 'Мушмула', 'плоден храст'),
(26, 'Спанак', 'зеленчуково растение'),
(27, 'Тиквичка', 'зеленчуково растение'),
(28, 'Котешка стъпка', 'билка'),
(29, 'Бабини зъби', 'билка'),
(30, 'Магарешки трън', 'тревисто растение'),
(31, 'Стаен клен', 'декоративно растение'),
(32, 'Лаврово дърво', 'дърво'),
(33, 'Китайски фенерчета', 'цвете'),
(34, 'Венерини коси', 'цвете');

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `enterpass` text COLLATE utf8_unicode_ci NOT NULL,
  `games_total` int(11) NOT NULL,
  `games_won` int(11) NOT NULL,
  `games_lost` int(11) NOT NULL,
  `guesses` int(11) NOT NULL,
  `instant_words` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Схема на данните от таблица `users`
--

INSERT INTO `users` (`id`, `name`, `enterpass`, `games_total`, `games_won`, `games_lost`, `guesses`, `instant_words`) VALUES
(1, 'Иванчо', '$2y$10$3RX9/yw7TAW6I3LDhLdyAeQosITq2ajURIAd.SDv4V3FU4sy8frb.', 20, 1, 19, 32, 1),
(2, 'Стоянчо', '$2y$10$u5N1xn1r9R/Lt0ilqPoSy.Bi20W6rNr90.dJW/TZwCw3FceOlxXHy', 3, 1, 2, 18, 0),
(3, 'Драганчо', '$2y$10$U4W93qG58eGHIoaqq5RNG.aQkXpzuIDpGVTdTz5oSv7w6ovpRo7bu', 0, 0, 0, 0, 0),
(4, 'Петканчо', '$2y$10$qcn8WSGhYw.3cFdBY5yzoe/thbwLL8RySgy/4.srY0S4tEwVE82wu', 0, 0, 0, 0, 0),
(5, 'Петърчо', '$2y$10$4nXNEm7Ees3cfYK9CiGq9eL1KHvrQkoZ11TZPPGTcsvNsM2KL5jSa', 0, 0, 0, 0, 0),
(6, 'Златанчо', '$2y$10$QQuZYmKDD3MuRmoG/nmVreZhkFAqgthYiaTNodf0MjhB2fCY9t89u', 0, 0, 0, 0, 0),
(23, 'светла', '$2y$10$lTbOgyz4.PNJkKQgy0aiUOjyuGiKofFDPo4cNfatl.SfjhscwO4My', 8, 5, 3, 33, 2),
(24, 'Светла', '$2y$10$k3rIVPmYZuu3NMTTUwXiMOk9a1yHPYOfBCZIkY3HUBj/SFWQd8RAG', 8, 5, 3, 33, 2),
(7, 'Муфчо ', '$2y$10$JXYYlo8UNdEdRHCVG2ziDupVZ1Gam0tkz27APGyiItjPhn2tO13.m', 3, 1, 2, 2, 1),
(25, 'Вероника', '$2y$10$sqiPYu0XuaGgQ12l9DT.6unax871fQssThPvDNfiSX4CByXIhQtzm', 3, 2, 1, 19, 1),
(26, 'янчо', '$2y$10$0GJmpd4OQazWjbJDVP/Wc.hcQ20rCrxueLdxhPUJTrNVINY4pool2', 1, 1, 0, 4, 0),
(27, 'Мелон', '$2y$10$CWJ7X52/o9SQAiQTM0tETummXz7OB.2oepdkaZ4DAvynvExMIK1WW', 4, 4, 0, 39, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plants`
--
ALTER TABLE `plants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `plants`
--
ALTER TABLE `plants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
