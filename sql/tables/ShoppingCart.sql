CREATE TABLE `gigacam`.`shoppingcart`
(
    `id`           INT       NOT NULL AUTO_INCREMENT,
    `customers_id` INT       NOT NULL,
    `date`         TIMESTAMP NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;