
ALTER TABLE `#__ukrgb_riverguides` ADD `river_name` varchar(20);
ALTER TABLE `#__ukrgb_riverguides` ADD `river_section` varchar(100);
ALTER TABLE `#__ukrgb_riverguides` ADD `short_description` varchar(255);
ALTER TABLE `#__ukrgb_riverguides` ADD `gauge_url` varchar(60);
ALTER TABLE `#__ukrgb_riverguides` ADD `gauge_name` varchar(60);
ALTER TABLE `#__ukrgb_riverguides` ADD `guage_calibration` varchar(200);		/* JSON */

ALTER TABLE `#__ukrgb_map_point` DROP COLUMN `description`;
ALTER TABLE `#__ukrgb_map_point` ADD `description` varchar(255);
