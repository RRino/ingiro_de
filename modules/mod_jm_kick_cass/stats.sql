CREATE TABLE IF NOT EXISTS `#__jn_kick_cass_stats` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`jn_date` date NOT NULL,
	`jn_stats` int(10),
	`jn_module_id` int(10),
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
