DROP TABLE IF EXISTS `#__ukrgb_doantion`;
 
CREATE TABLE `#__ukrgb_doantion` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `value` DECIMAL(5,2),
  `name` VARCHAR(25) NOT NULL,
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO `#__ukrgb_doantion` (`value`,`name`) VALUES
        (1.00, 'Fred Blogs'),
        (1.51, 'John Snith');
