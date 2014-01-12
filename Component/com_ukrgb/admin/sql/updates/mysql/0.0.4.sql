DROP TABLE IF EXISTS `#__ukrgb_events`;
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
