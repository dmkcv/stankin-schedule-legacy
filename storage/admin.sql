CREATE TABLE IF NOT EXISTS `schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `revision` varchar(50) DEFAULT NULL,
  `added` varchar(20) DEFAULT NULL,
  `active` int(1) DEFAULT '0',
  `status` int(1) DEFAULT '0',
  `date_from` int(20) DEFAULT NULL,
  `date_to` int(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `mtime` varchar(15) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_name` char(50) NOT NULL,
  `setting_value` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` varchar(15) NOT NULL,
  `result` varchar(3) NOT NULL,
  `error` varchar(400) DEFAULT NULL,
  `success` int(1) NOT NULL,
  `revupdated` int(1) NOT NULL,
  `filemtime` varchar(15) DEFAULT NULL,
  `newrev` varchar(32) DEFAULT NULL,
  `oldrev` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `login`, `password`) VALUES
	(1, 'admin', '$2y$10$fahYFJHNuWwYZJfUGVev7uwl9mvfFXN2mWjF6i2VFAIfpl.29gejG');