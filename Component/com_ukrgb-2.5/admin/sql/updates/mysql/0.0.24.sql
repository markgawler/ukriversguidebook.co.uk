

DROP TABLE IF EXISTS `#__ukrgb_maps`;
CREATE TABLE `#__ukrgb_maps` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`articleid` INT(11),
`sw_corner` POINT NOT NULL,
`ne_corner` POINT NOT NULL,
`map_type` INT( 11 )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO `#__ukrgb_maps` (sw_corner, ne_corner, map_type, articleid) 
VALUES( GeomFromText( 'POINT(-7.8 49.95)' ), GeomFromText( 'POINT(1.9 59.3)' ), 0, 0);
/* Map Type:
 * 0 - Everything
 * 10 - Retailers 
 */