-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2021 at 04:30 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `km_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` int(11) NOT NULL DEFAULT 0,
  `created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `is_admin`, `created`) VALUES
(1, 'admin', '$2y$10$yJtZX3s2meqar.GtEkvZSueXxdaPVlA3BORMkjBLql990/62/hO0K', 1, 1636769570),
(2, 'Anders', '$2y$10$n0NmK6zR4B8ksL69h.bYbOL15aazSmpsX1VuySX9TIV26Pw2RS2GO', 0, 1636926564),
(3, 'Højben', '$2y$10$MycgC/go21g0KDPeCQnzIefkusSUWEpXw826vLdw/C6c.pS10Uaha', 0, 1637193506),
(4, 'Gearløs', '$2y$10$wqI.GqGiAbbHMdxP9gfy9eu7dJ.Dx/.XH.Fe9YJUN3NzxJAUf9q9m', 0, 1637196702),
(5, 'Andersine', '$2y$10$5bznKZxYTlZuUs6mzKYwEubCE6wEZngi/bk3HJdxJRXGAeK3XqvzC', 0, 1637196752),
(6, 'splorp', '$2y$10$/TPnYVI4sk2F208HG5sbWOROdV4d5YtFJY16Las3/4uVy6hxnkvAS', 0, 1637197553);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
