-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 31, 2022 at 05:27 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forums_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `banned_user`
--

CREATE TABLE `banned_user` (
  `user_id` varchar(50) NOT NULL,
  `reason` varchar(200) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `description`, `date_created`) VALUES
(1, 'Programming', 'Programming is the process of creating a set of instructions that tell a computer how to perform a task. Programming can be done using a variety of computer programming languages, such as JavaScript, Python, and C++.', '2022-08-18 18:16:01'),
(3, 'Science', 'Science is a systematic enterprise that builds and organizes knowledge in the form of testable explanations and predictions about the universe. Science may be as old as the human species, and some of the earliest archeological evidence for scientific reasoning is tens of thousands of years old', '2022-08-18 18:13:04'),
(4, 'Math', 'Mathematics is an area of knowledge that includes such topics as numbers, formulas and related structures, shapes and the spaces in which they are contained, and quantities and their changes.', '2022-08-18 16:42:43');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` varchar(50) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `thread_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `content`, `thread_id`, `user_id`, `date_created`) VALUES
('630f6e3a0bc441661955642', '<p>Yes. I really really love programming.</p><p><br></p><p>You can check the link if you want to learn about it.</p><p><a href=\"https://www.w3schools.com/\" target=\"_blank\">W3school.com</a></p>', '630f6ca9d84a71661955241', '630f3977253c61661942135', '2022-08-31 22:20:42'),
('630f70a1938291661956257', '<p>Yes of course!</p>', '630f6ca9d84a71661955241', '630d80ae8dc191661829294', '2022-08-31 22:30:57'),
('630f74d4e14501661957332', '<p>English is English, not science.</p>', '630f718a1cde21661956490', '62ff89e2e977b1660914146', '2022-08-31 22:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `reply_id` varchar(50) NOT NULL,
  `content` mediumtext NOT NULL,
  `post_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`reply_id`, `content`, `post_id`, `user_id`, `date_created`) VALUES
('630f6fe0e8cf21661956064', '<p>Wow, Thank youu!</p>', '630f6e3a0bc441661955642', '62ff89e2e977b1660914146', '2022-08-31 22:27:44'),
('630f70433b38d1661956163', '<p>You\'re welcome Admin..</p>', '630f6e3a0bc441661955642', '630f3977253c61661942135', '2022-08-31 22:29:23'),
('630f70e1913e51661956321', '<p>Let\'s go</p>', '630f6e3a0bc441661955642', '630d80ae8dc191661829294', '2022-08-31 22:32:01');

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `thread_id` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(400) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`thread_id`, `category_id`, `title`, `description`, `user_id`, `date_created`) VALUES
('630f6ca9d84a71661955241', 1, 'Programming?', '<p>Do you love Programming?</p><p><br></p><p>#lovelove</p>', '62ff89e2e977b1660914146', '2022-08-31 22:14:01'),
('630f6fa47c5f71661956004', 4, 'Happy kaba sa math?', '<p>You can leave post!</p>', '630f3977253c61661942135', '2022-08-31 22:26:44'),
('630f718a1cde21661956490', 3, 'English is a foundation of Science', '<p>agree??</p><p>HAHAHAHA</p><p><br></p><p>#dwow</p>', '630d80ae8dc191661829294', '2022-08-31 22:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_information`
--

CREATE TABLE `user_information` (
  `user_id` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_profilepath` varchar(100) DEFAULT NULL,
  `user_role_id` int(11) DEFAULT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_information`
--

INSERT INTO `user_information` (`user_id`, `username`, `password`, `firstname`, `middlename`, `lastname`, `sex`, `birthdate`, `email`, `user_profilepath`, `user_role_id`, `date_created`) VALUES
('62ff89e2e977b1660914146', 'administrator', '8f38430f9d60b28e01fd3248dc025b2d', 'Dick', 'Soriano', 'Lomibao', 'Male', '2001-12-09', 'sorianokid771@gmail.com', 'images/profilepicture/62ff89e2e8c6a-1660914146.jpg', 2, '2022-08-19'),
('630d80ae8dc191661829294', 'mariane1234', '8fcf0d6f40792d425b82528bbf19f7ec', 'Mariane', 'Soriano', 'Lomibao', 'Female', '2009-07-20', 'asdasd@gmail.com', 'images/profilepicture/630d80ae8d1dc-1661829294.jpg', 1, '2022-08-30'),
('630f3977253c61661942135', 'demark123', '1d1909a6fd285a4c51c1d00c8c7ddd3b', 'Demark', 'Soriano', 'Lomibao', 'Female', '2022-08-09', 'sorianokid771@gmail.com', 'images/profilepicture/630f397724ae0-1661942135.jpg', 1, '2022-08-31');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `user_role_id` int(11) NOT NULL,
  `role_description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_role_id`, `role_description`) VALUES
(1, 'User'),
(2, 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banned_user`
--
ALTER TABLE `banned_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `topic_id` (`thread_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`thread_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_information`
--
ALTER TABLE `user_information`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_information_ibfk_1` (`user_role_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banned_user`
--
ALTER TABLE `banned_user`
  ADD CONSTRAINT `banned_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_information` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`thread_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_information` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_information` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `threads_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_information` (`user_id`);

--
-- Constraints for table `user_information`
--
ALTER TABLE `user_information`
  ADD CONSTRAINT `user_information_ibfk_1` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`user_role_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
