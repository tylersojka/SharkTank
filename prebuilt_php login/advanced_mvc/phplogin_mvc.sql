CREATE DATABASE IF NOT EXISTS `phplogin_mvc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `phplogin_mvc`;

CREATE TABLE IF NOT EXISTS `accounts` (
`id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(50) NOT NULL DEFAULT '',
  `rememberme` varchar(255) NOT NULL DEFAULT '',
  `role` enum('Member','Admin') NOT NULL DEFAULT 'Member',
  `reset` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `activation_code`, `rememberme`, `role`, `reset`) VALUES
(1, 'admin', '$2y$10$LuPPKrZi5szger2yZW37VuNS6CT1S4fgAeYkn6QXcCJ9NwUM3dRoi', 'admin@example.com', '', '$2y$10$T3yRx06CY5E/kT2nMIflW.wUdhRuhFbgD1plcKwWQBV.qzeyCAvoK', 'Admin', ''),
(2, 'member', '$2y$10$7vKi0TjZimZyp/S5aCtK0eLsGagyIJVfpzGSFgRSqDGkJMxqoIYV.', 'member@example.com', '', '', 'Member', '');

CREATE TABLE IF NOT EXISTS `login_attempts` (
`id` int(11) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `attempts_left` tinyint(1) NOT NULL DEFAULT '5',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `accounts` ADD PRIMARY KEY (`id`);

ALTER TABLE `login_attempts` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `ip_address` (`ip_address`);

ALTER TABLE `accounts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `login_attempts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
