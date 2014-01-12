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

