CREATE TABLE `users` (
  `deviceId` varchar(20) NOT NULL,
  `nickname` varchar(256) NOT NULL,
  `loc_lat` text NOT NULL,
  `loc_lon` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users`
  ADD PRIMARY KEY (`deviceId`),
  ADD UNIQUE KEY `nickname_2` (`nickname`),
  ADD UNIQUE KEY `id` (`deviceId`),
  ADD KEY `nickname` (`nickname`);
