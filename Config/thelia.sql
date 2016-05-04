
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- product_comparison
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `product_comparison`;

CREATE TABLE `product_comparison`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `feature_id` INTEGER NOT NULL,
    `template_id` INTEGER NOT NULL,
    `position` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `idx_product_comparison_id` (`feature_id`),
    INDEX `fk_product_comparison_idx` (`template_id`),
    INDEX `idx_product_comparison_template_id_position` (`template_id`, `position`),
    CONSTRAINT `fk_product_comparison_id`
        FOREIGN KEY (`feature_id`)
        REFERENCES `feature` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_product_comparison`
        FOREIGN KEY (`template_id`)
        REFERENCES `template` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
