CREATE TABLE IF NOT EXISTS `#__calevent_notes` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`asset_id` bigint NOT NULL DEFAULT 0,
	`title` varchar(255) NOT NULL DEFAULT '',
	`alias` varchar(300) NOT NULL DEFAULT '',
	`description` text,
	`published` tinyint(1) NOT NULL DEFAULT 0,
	`checked_out` int unsigned NOT NULL DEFAULT 0,
	`checked_out_time` datetime,
	`ordering` int NOT NULL DEFAULT 0,
	`access` int unsigned NOT NULL DEFAULT 0,
	`language` varchar(7) NOT NULL DEFAULT '*',
	`created` datetime NOT NULL,
	`created_by` int unsigned NOT NULL DEFAULT 0,
	`modified` datetime NOT NULL,
	`modified_by` int unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


--{{inject: install_table}}

CREATE TABLE IF NOT EXISTS `#__calevent_eventos` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`asset_id` bigint NOT NULL DEFAULT 0,
	`title` varchar(255) NOT NULL DEFAULT '',
	`id_link` int NOT NULL DEFAULT 0,
	`data_inizio` datetime NOT NULL,
	`data_fine` datetime NOT NULL,
	`anno` int NOT NULL DEFAULT 0,
	`colore` varchar(50) NOT NULL DEFAULT '',
    `mese` int NOT NULL DEFAULT 0,
	`alias` varchar(300) NOT NULL DEFAULT '',
	`sigla` varchar(30) NOT NULL DEFAULT '',
	`description` text,
	`published` tinyint(1) NOT NULL DEFAULT 0,
	`checked_out` int unsigned NOT NULL DEFAULT 0,
	`checked_out_time` datetime,
	`ordering` int NOT NULL DEFAULT 0,
	`access` int unsigned NOT NULL DEFAULT 0,
	`language` varchar(7) NOT NULL DEFAULT '*',
	`created` datetime NOT NULL,
	`created_by` int unsigned NOT NULL DEFAULT 0,
	`modified` datetime NOT NULL,
	`modified_by` int unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
