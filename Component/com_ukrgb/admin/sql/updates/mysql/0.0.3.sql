DROP TABLE IF EXISTS `#__ukrgb_events`;
CREATE TABLE `#__ukrgb_events` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`calendar` tinyint(3), /* The event calendar the event is displayed in */
`title` varchar(100), 
`location` varchar(60),
`event_date` datetime,
`duration` tinyint(3),
`all_day` boolean, /* tru is the event is an all day event*/
`start_time` time,
`end_time` time,
`course_provider` varchar(60),
`summary` text,
`description` text,
`attributes` varchar(1024)
);
