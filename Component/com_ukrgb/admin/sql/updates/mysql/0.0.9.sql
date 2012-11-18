
DROP TABLE IF EXISTS `#__ukrgb_maps`;
CREATE TABLE `#__ukrgb_maps` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`centre_point` POINT NOT NULL,
`zoom` TINYINT NOT NULL 
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO `#__ukrgb_maps` (centre_point, zoom) VALUES( GeomFromText( 'POINT(51.514 -0.1167)' ), 12);
