CREATE TABLE `mod_codekind` (
  `codekind_id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `codekind_name` varchar(20) DEFAULT NULL,
  `codekind_key` varchar(20) DEFAULT NULL,
  `codekind_desc` varchar(20) DEFAULT NULL,
  `codekind_value1` varchar(20) DEFAULT NULL,
  `codekind_value2` varchar(20) DEFAULT NULL,
  `codekind_value3` varchar(20) DEFAULT NULL,
  `modi_time` datetime DEFAULT NULL,
  `lang_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `mod_code` (
  `code_id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `codekind_key` varchar(20) DEFAULT NULL,
  `code_name` varchar(20) DEFAULT NULL,
  `code_key` varchar(20) DEFAULT NULL,
  `code_value1` varchar(20) DEFAULT NULL,
  `code_value2` varchar(20) DEFAULT NULL,
  `code_value3` varchar(20) DEFAULT NULL,
  `parent_id` bigint(10) DEFAULT '-1',
  `modi_time` datetime DEFAULT NULL,
  `lang_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;