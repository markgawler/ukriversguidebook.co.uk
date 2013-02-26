
DROP TABLE IF EXISTS `#__ukrgb_configuration`;
CREATE TABLE `#__ukrgb_configuration` (
`name` varchar(30) NOT NULL PRIMARY KEY,
`value` varchar(100)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `#__ukrgb_doantion`;
CREATE TABLE `#__ukrgb_doantion` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tx` varchar (20),
  `user_id`	INT(11),
  `phpBB_user_id` INT(11),
  `topic_id` mediumint(8),
  `post_id` mediumint(8) ,
  `mc_gross` DECIMAL(5,2),
  `protection_eligibility` varchar(40),
  `payer_id` varchar(20) ,
  `tax` DECIMAL(5,2),
  `payment_date` varchar(28),  
  `payment_status` varchar (20), 
  `first_name` varchar(40),
  `receipt_reference_number` varchar (20),
  `mc_fee` DECIMAL(5,2),
  `custom`  varchar (20),
  `payer_status` varchar (20),
  `business` varchar(40),
  `quantity`  mediumint(8),
  `payer_email` varchar(40),
  `txn_id` varchar(30),
  `payment_type` varchar (20),
  `last_name` varchar(40),
  `receiver_email` varchar(40),
  `store_id` varchar(20),
  `payment_fee` DECIMAL(5,2),
  `receiver_id` varchar(20),
  `pos_transaction_type` varchar(20),
  `txn_type` varchar(20),
  `item_name` varchar(30),
  `num_offers` varchar(20),
  `mc_currency` varchar(4),
  `item_number` mediumint(8),
  `residence_country` varchar(4),
  `handling_amount`  DECIMAL(5,2),
  `transaction_subject` varchar(40),
  `payment_gross` DECIMAL(5,2),
  `shipping` DECIMAL(5,2),

  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__ukrgb_maps`;
CREATE TABLE `#__ukrgb_maps` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`sw_corner` POINT NOT NULL,
`ne_corner` POINT NOT NULL,
`map_type` INT( 11 )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
/* UK Map */
INSERT INTO `#__ukrgb_maps` (sw_corner, ne_corner, map_type) 
VALUES( GeomFromText( 'POINT(49.95 -7.8)' ), GeomFromText( 'POINT(59.3 1.9)' ), 0);
INSERT INTO `#__ukrgb_maps` (sw_corner, ne_corner, map_type) 
VALUES( GeomFromText( 'POINT(49.95 1.0)' ), GeomFromText( 'POINT(50.0 1.9)' ), 0);
INSERT INTO `#__ukrgb_maps` (sw_corner, ne_corner, map_type) 
VALUES( GeomFromText( 'POINT(49.95 -7.8)' ), GeomFromText( 'POINT(59.3 1.9)' ), 10);
INSERT INTO `#__ukrgb_maps` (sw_corner, ne_corner, map_type) 
VALUES( GeomFromText( 'POINT(50.3 -4.5)' ), GeomFromText( 'POINT(50.7 -3.5)' ), 0);
/* Map Type:
 * 0 - Everything
 * 10 - Retailers 
 */


DROP TABLE IF EXISTS `#__ukrgb_riverguides`;
CREATE TABLE `#__ukrgb_riverguides` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`article_id` INT(11) NOT NULL,
`map_id` INT(11),
`grade` TINYINT NOT NULL
`river_name` varchar(20),
`river_section` varchar(100),
`short_description` varchar(255),
`gauge_url` varchar(255), /* http://www.environment-agency.gov.uk/homeandleisure/floods/riverlevels/Controls/RiverLevels/StationId=3212&RegionId=5&AreaId=12&CatchmentId=59*/
`gauge_name` varchar(60),
`guage_calibration` varchar(200),		/* JSON */  
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `#__ukrgb_map_point`;
CREATE TABLE `#__ukrgb_map_point` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`riverguide` INT(11),  /* Id of the river guide */
`point` POINT NOT NULL,
`type` TINYINT NOT NULL,  /* 1 = putin, 2 = takeout, 3 = alternate */
`description` varchar(255)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
