

DROP TABLE IF EXISTS `#__ukrgb_maps`;
CREATE TABLE `#__ukrgb_maps` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`sw_corner` POINT NOT NULL,
`ne_corner` POINT NOT NULL,
`map_type` `id` INT( 11 )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO `#__ukrgb_maps` (sw_corner, ne_corner, map_type) 
VALUES( GeomFromText( 'POINT(49.95 -7.8)' ), GeomFromText( 'POINT(59.3, 1.9)' ), 0);
INSERT INTO `#__ukrgb_maps` (sw_corner, ne_corner, map_type) 
VALUES( GeomFromText( 'POINT(49.95 -7.8)' ), GeomFromText( 'POINT(59.3, 1.9)' ), 10);
