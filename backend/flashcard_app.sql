-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2024 at 03:08 AM
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
-- Database: `flashcard_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE `card` (
  `deck_id` int(11) NOT NULL,
  `card_number` int(11) NOT NULL,
  `term` text DEFAULT NULL,
  `definition` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`deck_id`, `card_number`, `term`, `definition`) VALUES
(1, 1, 'entropy', 'Measure of disorder for a system'),
(1, 2, 'Second Law of Thermodynamics', 'Systems move towawrds greater entropy'),
(1, 3, 'theromodynamic work', 'Integral of pressure with respect to volume'),
(1, 4, 'adiabatic index', 'ratio of speciic heat capacities at constant pressure and volume'),
(1, 5, 'Carnot cycle', 'closed and reversible cycle with 2 adiabatic and 2 isothermal processes'),
(2, 1, 'the Lagrangian', 'kinetic minus potential energy'),
(2, 2, 'the Hamiltonian', 'sum of kinetic and potential energies'),
(2, 3, 'action', 'the time integral of the lagrangian');

-- --------------------------------------------------------

--
-- Table structure for table `deck`
--

CREATE TABLE `deck` (
  `deck_id` int(11) NOT NULL,
  `title` varchar(70) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `mastery_score` int(11) DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `username` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deck`
--

INSERT INTO `deck` (`deck_id`, `title`, `description`, `mastery_score`, `creation_date`, `size`, `username`) VALUES
(1, 'Thermodynamics', 'Thermodynamics terms and definitions for midterm 1', 15, '2024-03-24', 5, 'alice'),
(2, 'Classical Mechanics', 'Terms and definitions for my mechanics class', 0, '2024-03-24', 3, 'alice');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `q_number` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `q_prompt` text DEFAULT NULL,
  `q_answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`q_number`, `quiz_id`, `q_prompt`, `q_answer`) VALUES
(1, 1, 'Systems move towawrds greater entropy', 'Second Law of Thermodynamics'),
(1, 2, 'kinetic minus potential energy', 'the Lagrangian'),
(2, 1, 'ratio of speciic heat capacities at constant pressure and volume', 'adiabatic index'),
(2, 2, 'sum of kinetic and potential energies', 'the Hamiltonian'),
(3, 1, '2 isothermal and 2 adiabatic processes', 'Carnot cycle'),
(3, 2, 'the time integral of the lagrangian', 'action'),
(4, 2, 'a system has high sensitivity to initial conditions', 'chaos');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `quiz_id` int(11) NOT NULL,
  `quiz_date` date DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `score` float DEFAULT NULL,
  `username` varchar(40) DEFAULT NULL
) ;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `quiz_date`, `length`, `score`, `username`) VALUES
(1, '2024-03-24', 3, 80, 'alice'),
(2, '2024-03-24', 4, 80, 'alice');

--
-- Triggers `quiz`
--
DELIMITER $$
CREATE TRIGGER `scoreCheck` BEFORE UPDATE ON `quiz` FOR EACH ROW BEGIN
	IF new.score > 100 THEN
    	SET new.score = 100;
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `recipient` varchar(40) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `username` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `recipient`, `message`, `request_date`, `username`) VALUES
(2, 'charles', 'I want to be your friend', '2024-03-24', 'alice'),
(4, 'daniel', 'You seem cool!', '2024-03-24', 'charles');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `quiz_id` int(11) NOT NULL,
  `deck_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`quiz_id`, `deck_id`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_friend_names`
--

CREATE TABLE `user_friend_names` (
  `username` varchar(40) NOT NULL,
  `friend_name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_friend_names`
--

INSERT INTO `user_friend_names` (`username`, `friend_name`) VALUES
('alice', 'charles'),
('bob', 'daniel'),
('charles', 'alice'),
('daniel', 'bob');

-- --------------------------------------------------------

--
-- Table structure for table `user_main`
--

CREATE TABLE `user_main` (
  `username` varchar(40) NOT NULL,
  `user_password` varchar(40) DEFAULT NULL,
  `quiz_rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_main`
--

INSERT INTO `user_main` (`username`, `user_password`, `quiz_rating`) VALUES
('alice', 'Alice123!', 10),
('bob', 'Bob456!', 0),
('charles', 'Charles789!', 0),
('daniel', 'Daniel123!', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_masters`
--

CREATE TABLE `user_masters` (
  `deck_id` int(11) NOT NULL,
  `username` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_masters`
--

INSERT INTO `user_masters` (`deck_id`, `username`) VALUES
(2, 'alice');

-- --------------------------------------------------------

--
-- Table structure for table `user_nicknames`
--

CREATE TABLE `user_nicknames` (
  `username` varchar(40) NOT NULL,
  `nickname` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_nicknames`
--

INSERT INTO `user_nicknames` (`username`, `nickname`) VALUES
('alice', 'The Quiz Master'),
('bob', 'Master Carder'),
('daniel', 'Danny'),
('daniel', 'Exam Legend'),
('daniel', 'The Crammer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`deck_id`,`card_number`);

--
-- Indexes for table `deck`
--
ALTER TABLE `deck`
  ADD PRIMARY KEY (`deck_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`q_number`,`quiz_id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `user_friend_names`
--
ALTER TABLE `user_friend_names`
  ADD PRIMARY KEY (`username`,`friend_name`);

--
-- Indexes for table `user_main`
--
ALTER TABLE `user_main`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `user_masters`
--
ALTER TABLE `user_masters`
  ADD PRIMARY KEY (`deck_id`);

--
-- Indexes for table `user_nicknames`
--
ALTER TABLE `user_nicknames`
  ADD PRIMARY KEY (`username`,`nickname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deck`
--
ALTER TABLE `deck`
  MODIFY `deck_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deck`
--
ALTER TABLE `deck`
  ADD CONSTRAINT `deck_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user_main` (`username`);

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user_main` (`username`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user_main` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
