generic = ("""
CREATE TABLE `admins` (
  `name` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
""","""CREATE TABLE `blue_markers` (
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(10,8) NOT NULL,
  `uid_user` int(10) UNSIGNED DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
""", """CREATE TABLE `grey_markers` (
  `latitude` decimal(12,10) NOT NULL,
  `longitude` decimal(12,10) NOT NULL,
  `uid_volunteer` int(10) UNSIGNED DEFAULT NULL,
  `time_of_delete` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
""", """CREATE TABLE `new_volunteers` (
  `uid` int(10) UNSIGNED NOT NULL,
  `phone` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `blocked` varchar(10) DEFAULT NULL,
  `confirmations` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
""", """CREATE TABLE `red_markers` (
  `latitude` decimal(12,10) NOT NULL,
  `longitude` decimal(12,10) NOT NULL,
  `uid_volunteer` int(10) UNSIGNED DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL,
  `confirmations` int(11) DEFAULT '0',
  `radius` int(11) DEFAULT '50'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
""", """CREATE TABLE `users` (
  `uid` int(10) UNSIGNED NOT NULL,
  `mac_user` varchar(30) DEFAULT NULL,
  `blocked` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
""", """CREATE TABLE `volunteers` (
  `uid` int(10) UNSIGNED NOT NULL,
  `phone` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `blocked` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
""", """CREATE TABLE `yellow_markers` (
  `latitude` decimal(12,10) NOT NULL,
  `longitude` decimal(12,10) NOT NULL,
  `uid_volunteer` int(10) UNSIGNED DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
""", """ALTER TABLE `admins`
  ADD PRIMARY KEY (`name`);
""", """ALTER TABLE `blue_markers`
  ADD PRIMARY KEY (`latitude`,`longitude`),
  ADD KEY `uid_user` (`uid_user`);
""", """ALTER TABLE `grey_markers`
  ADD PRIMARY KEY (`latitude`,`longitude`);
""", """ALTER TABLE `new_volunteers`
  ADD PRIMARY KEY (`uid`);

""", """ALTER TABLE `red_markers`
  ADD PRIMARY KEY (`latitude`,`longitude`),
  ADD KEY `uid_volunteer` (`uid_volunteer`);
""","""ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);
""","""ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`uid`);
""","""
ALTER TABLE `yellow_markers`
  ADD PRIMARY KEY (`latitude`,`longitude`),
  ADD KEY `uid_volunteer` (`uid_volunteer`);
""","""ALTER TABLE `new_volunteers`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
""","""ALTER TABLE `users`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
""","""ALTER TABLE `volunteers`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
""","""ALTER TABLE `blue_markers`
  ADD CONSTRAINT `blue_markers_ibfk_1` FOREIGN KEY (`uid_user`) REFERENCES `users` (`uid`);
""","""ALTER TABLE `red_markers`
  ADD CONSTRAINT `red_markers_ibfk_1` FOREIGN KEY (`uid_volunteer`) REFERENCES `volunteers` (`uid`);
""","""ALTER TABLE `yellow_markers`
  ADD CONSTRAINT `yellow_markers_ibfk_1` FOREIGN KEY (`uid_volunteer`) REFERENCES `new_volunteers` (`uid`);
""")
#-------------------------------------------------------------------------------------------------------------------
#raw export:--------------------------------------------------------------------------------------------------------
'''
--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `name` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blue_markers`
--

CREATE TABLE `blue_markers` (
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(10,8) NOT NULL,
  `uid_user` int(10) UNSIGNED DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grey_markers`
--

CREATE TABLE `grey_markers` (
  `latitude` decimal(12,10) NOT NULL,
  `longitude` decimal(12,10) NOT NULL,
  `uid_volunteer` int(10) UNSIGNED DEFAULT NULL,
  `time_of_delete` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `new_volunteers`
--

CREATE TABLE `new_volunteers` (
  `uid` int(10) UNSIGNED NOT NULL,
  `phone` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `blocked` varchar(10) DEFAULT NULL,
  `confirmations` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `red_markers`
--

CREATE TABLE `red_markers` (
  `latitude` decimal(12,10) NOT NULL,
  `longitude` decimal(12,10) NOT NULL,
  `uid_volunteer` int(10) UNSIGNED DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL,
  `confirmations` int(11) DEFAULT '0',
  `radius` int(11) DEFAULT '50'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(10) UNSIGNED NOT NULL,
  `mac_user` varchar(30) DEFAULT NULL,
  `blocked` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `uid` int(10) UNSIGNED NOT NULL,
  `phone` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `blocked` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yellow_markers`
--

CREATE TABLE `yellow_markers` (
  `latitude` decimal(12,10) NOT NULL,
  `longitude` decimal(12,10) NOT NULL,
  `uid_volunteer` int(10) UNSIGNED DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `blue_markers`
--
ALTER TABLE `blue_markers`
  ADD PRIMARY KEY (`latitude`,`longitude`),
  ADD KEY `uid_user` (`uid_user`);

--
-- Indexes for table `grey_markers`
--
ALTER TABLE `grey_markers`
  ADD PRIMARY KEY (`latitude`,`longitude`);

--
-- Indexes for table `new_volunteers`
--
ALTER TABLE `new_volunteers`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `red_markers`
--
ALTER TABLE `red_markers`
  ADD PRIMARY KEY (`latitude`,`longitude`),
  ADD KEY `uid_volunteer` (`uid_volunteer`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `yellow_markers`
--
ALTER TABLE `yellow_markers`
  ADD PRIMARY KEY (`latitude`,`longitude`),
  ADD KEY `uid_volunteer` (`uid_volunteer`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `new_volunteers`
--
ALTER TABLE `new_volunteers`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `blue_markers`
--
ALTER TABLE `blue_markers`
  ADD CONSTRAINT `blue_markers_ibfk_1` FOREIGN KEY (`uid_user`) REFERENCES `users` (`uid`);

--
-- Constraints for table `red_markers`
--
ALTER TABLE `red_markers`
  ADD CONSTRAINT `red_markers_ibfk_1` FOREIGN KEY (`uid_volunteer`) REFERENCES `volunteers` (`uid`);

--
-- Constraints for table `yellow_markers`
--
ALTER TABLE `yellow_markers`
  ADD CONSTRAINT `yellow_markers_ibfk_1` FOREIGN KEY (`uid_volunteer`) REFERENCES `new_volunteers` (`uid`);
 '''