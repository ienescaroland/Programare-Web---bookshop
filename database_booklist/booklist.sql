-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2017 at 04:30 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booklist`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `price`, `stock`) VALUES
(1, '1Q84', 'Haruki Murakami', 18, 23),
(2, 'Anna Karenina', 'Lev Tolstoi', 11, 23),
(3, 'Climates', 'André Maurois', 8, 23),
(4, 'Kafka on the Shore', 'Haruki Murakami', 18, 23),
(5, 'Norwegian Wood', 'Haruki Murakami', 12, 3),
(7, 'The Book Thief', 'Markus Zusak', 18, 23),
(8, 'The Collector', 'John Fowles', 9, 0),
(9, 'The Danish Girl', 'David Ebershoff', 17, 0),
(10, 'A Clash of Kings', 'George R.R. Martin', 22, 5),
(11, 'A Dance with Dragons', 'George R.R. Martin', 41, 0),
(12, 'A Feast for Crows', 'George R.R. Martin', 20, 0),
(13, 'A Game of Thrones', 'George R.R. Martin', 23, 0),
(14, 'A Storm of Swords', 'George R.R. Martin', 30, 0),
(15, 'Time of Contempt', 'Andrzej Sapkowski', 22, 0),
(16, 'The Tower of the Swallow', 'Andrzej Sapkowski', 23, 0),
(17, 'The Last Wish', 'Andrzej Sapkowski', 22, 0),
(18, 'The Lady of the Lake', 'Andrzej Sapkowski', 29, 0),
(19, 'Baptism of Fire', 'Andrzej Sapkowski', 21, 0),
(20, 'Blood of Elves', 'Andrzej Sapkowski', 23, 0),
(21, 'Sword of Destiny', 'Andrzej Sapkowski', 25, 0),
(22, 'The Winds of Winter', 'George R.R. Martin', 124, 0),
(23, 'Season of Storms', 'Andrzej Sapkowski', 139, 0),
(24, 'Misery', 'Stephen King', 20, 0),
(25, 'Blaze', 'Stephen King', 20, 0),
(26, 'Gerald\'s Game', 'Stephen King', 20, 0),
(27, 'Carrie', 'Stephen King', 20, 8);

-- --------------------------------------------------------

--
-- Table structure for table `book_request`
--

CREATE TABLE `book_request` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `book_request`
--

INSERT INTO `book_request` (`id`, `title`, `author`, `username`, `approved`) VALUES
(13, 'Misery', 'Stephen King', 'roli', 1),
(14, 'Blaze', 'Stephen King', 'roli', 1),
(15, 'Carrie', 'Stephen King', 'roli', 0),
(16, 'Gerald\'s Game', 'Stephen King', 'roli', 1),
(21, 'Carrie', 'Stephen King', 'ienesca', 1),
(22, 'sf', 'asd', 'roli', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `book_id`, `message`) VALUES
(4, 5, 10, 'Great! Great!'),
(5, 5, 10, 'Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.Good.'),
(8, 5, 5, 'wow wow\r\nwow'),
(9, 5, 10, 'This book is great.\r\nCan\'t wait to read the next one!'),
(10, 4, 5, 'hmm'),
(11, 4, 27, 'hmmm'),
(12, 4, 7, 'so good!'),
(13, 5, 8, 'yay'),
(14, 3, 1, 'good book'),
(15, 3, 21, 'aaa');

-- --------------------------------------------------------

--
-- Table structure for table `pbooks`
--

CREATE TABLE `pbooks` (
  `id` int(11) NOT NULL,
  `id_book` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pbooks`
--

INSERT INTO `pbooks` (`id`, `id_book`, `id_user`) VALUES
(1, 1, 3),
(2, 2, 3),
(3, 3, 3),
(4, 4, 3),
(5, 5, 3),
(6, 7, 3),
(7, 11, 3),
(8, 10, 3),
(9, 5, 4),
(10, 3, 4),
(12, 23, 4),
(13, 7, 4),
(15, 13, 3),
(18, 16, 3),
(19, 22, 3),
(20, 24, 3),
(21, 5, 5),
(22, 27, 5),
(23, 27, 4),
(24, 8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`) VALUES
(3, 'roli', 'roli@myemail.com', '$2y$13$t/9RCTQOWpbgi5cZud6yw.jZTGOIkMwDryOeamuT9ZwhyYgs4mE..'),
(4, 'ienesca', 'ienesca@email.com', '$2y$13$2p2O7CjSc9G6YDTQRGzceumEujE.q5GoKx9gqA4Jc.DfEK5PtmKw.'),
(5, 'roland', 'roland@email.com', '$2y$13$FOhLXjsnZm7JbLS3xxyhRuhoxaM4lQUWU2xOnWt///pA0nu3uCL5e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_request`
--
ALTER TABLE `book_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pbooks`
--
ALTER TABLE `pbooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `book_request`
--
ALTER TABLE `book_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pbooks`
--
ALTER TABLE `pbooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
