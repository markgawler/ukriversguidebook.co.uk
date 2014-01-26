DROP TABLE IF EXISTS `#__ukrgb_maps`;
CREATE TABLE `#__ukrgb_maps` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`articleid` INT(11),
`sw_corner` POINT NOT NULL,
`ne_corner` POINT NOT NULL,
`map_type` INT( 11 )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__ukrgb_maps` (sw_corner, ne_corner, map_type) 
VALUES( GeomFromText( 'POINT(-7.8 49.95)' ), GeomFromText( 'POINT(1.9 59.3)' ), 0);
/* Map Type:
 * 0 - Everything
 * 10 - Retailers 
 */


DROP TABLE IF EXISTS `#__ukrgb_map_point`;
CREATE TABLE `#__ukrgb_map_point` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`riverguide` INT(11),  /* Id of the river guide */
`point` POINT NOT NULL,
`type` TINYINT NOT NULL,  /* 1 = putin, 2 = takeout, 3 = alternate */
`description` varchar(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ukrgb_events` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`created_by` int(11), 
`calendar` tinyint(3), /* The event calendar the event is displayed in */
`location` varchar(60),
`title` varchar(100),
`start_date` datetime,
`end_date` datetime,
`all_day` boolean, /* tru is the event is an all day event*/
`course_provider` varchar(60),
`summary` text,
`description` text,
`attributes` varchar(1024)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  
CREATE TABLE IF NOT EXISTS `#__ukrgb_cal_events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT ,
  `catid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '' ,
  `description` text NOT NULL ,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '' ,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0' ,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' ,
  `xreference` varchar(50) NOT NULL ,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL DEFAULT '1',
  
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
    

) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  


DROP TABLE IF EXISTS `#__ukrgb_event_mapping`;
CREATE TABLE  `#__ukrgb_event_mapping` (
  `event_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #_joompro_subscriptions.id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__users.id',
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`event_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
