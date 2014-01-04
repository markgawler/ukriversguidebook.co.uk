
ALTER TABLE `#__ukrgb_riverguides` DROP COLUMN `putin_geo`;
ALTER TABLE `#__ukrgb_riverguides` DROP COLUMN `takeout_geo`;

DROP TABLE IF EXISTS `#__ukrgb_map_point`;
CREATE TABLE `#__ukrgb_map_point` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`riverguide` INT(11),
`point` POINT NOT NULL,
`type` TINYINT NOT NULL,
`description` varchar(20)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;