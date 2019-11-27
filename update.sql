-- Create table report_period
CREATE TABLE IF NOT EXISTS `report_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_period` date DEFAULT NULL,
  `end_period` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create table report_period_price
CREATE TABLE IF NOT EXISTS `report_period_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `period_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create view vw_part_period_price
CREATE OR REPLACE VIEW vw_part_period_price AS select `rpp`.`id` AS `id`,`rpp`.`item_id` AS `item_id`,`item`.`item_code` AS `item_code`,`item`.`item_name` AS `item_name`,`item`.`factory_style` AS `factory_style`,`item`.`buyer_style` AS `buyer_style`,`rpp`.`part_id` AS `part_id`,`part`.`part_number` AS `part_number`,`part`.`part_name` AS `part_name`,`rpp`.`price` AS `price`,`part`.`qty` AS `qty`,`rpp`.`period_id` AS `period_id` from ((`report_period_price` `rpp` left join `item` on((`rpp`.`item_id` = `item`.`id`))) left join `part` on((`rpp`.`part_id` = `part`.`id`)));