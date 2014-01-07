DROP TABLE IF EXISTS `#__ukrgb_events`;
CREATE TABLE `#__ukrgb_events` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` varchar(100),
`eventdate` varchar(11),
`description` mediumtext,
`parameters` varchar(1024)
);