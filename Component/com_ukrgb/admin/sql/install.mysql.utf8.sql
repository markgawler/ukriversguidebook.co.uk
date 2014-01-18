DROP TABLE IF EXISTS `#__ukrgb_maps`;
CREATE TABLE `#__ukrgb_maps` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`articleid` INT(11),
`sw_corner` POINT NOT NULL,
`ne_corner` POINT NOT NULL,
`map_type` INT( 11 )
);

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
);

CREATE TABLE `#__ukrgb_events` (
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
);


CREATE TABLE IF NOT EXISTS `#__ukrgb_cal_events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Automatic incrementing key field',
  `catid` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__categories table',
  `title` varchar(250) NOT NULL DEFAULT '' COMMENT 'Title of Subscription',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'Alias value, used for SEF URLs',
  `description` text NOT NULL COMMENT 'Description (will be edited using editor)',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__groups table',
  `duration` int(11) NOT NULL DEFAULT '0' COMMENT 'Number for days that subscription lasts',
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Published state (1=published, 0=unpublished, -2=trashed)',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) NOT NULL DEFAULT '1' COMMENT 'Used to control access to subscriptions',
  `params` text NOT NULL COMMENT 'For possible future use to add item-level parameters (JSON string format)',
  `language` char(7) NOT NULL DEFAULT '' COMMENT 'For possible future use to add language switching',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__users table for user who created this item',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__users table for user who modified this item',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date to start publishing this item',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date to stop publishing this item',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_published` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ukrgb_event_mapping` (
  `event_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #_joompro_subscriptions.id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__users.id',
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`event_id`, `user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
