CREATE TABLE IF NOT EXISTS `login_attempts` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ip_address` varchar(255) NOT NULL,
	`attempts_left` tinyint(1) NOT NULL DEFAULT '5',
	`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `login_attempts` ADD UNIQUE KEY `ip_address` (`ip_address`);