
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- product_comparison
-- ---------------------------------------------------------------------

INSERT INTO `product_comparison`
(
    `id`,
    `feature_id`,
    `template_id`,
    `position`
)
    SELECT `id`, `feature_id`, `template_id`, `position`
    FROM `feature_template`;


# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
