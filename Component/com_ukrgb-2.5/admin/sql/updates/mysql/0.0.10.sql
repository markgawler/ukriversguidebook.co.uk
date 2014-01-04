DROP TABLE IF EXISTS `#__ukrgb_riverguides`;
CREATE TABLE `#__ukrgb_riverguides` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`article_id` INT(11) NOT NULL,
`map_id` INT(11),
`putin_geo` POINT,
`takeout_geo` POINT 
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;