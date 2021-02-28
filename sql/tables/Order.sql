CREATE TABLE `gigacam`.`order`
(
    `id`                  INT       NOT NULL AUTO_INCREMENT,
    `date`                TIMESTAMP NOT NULL,
    `purchase_address_id` INT       NOT NULL,
    `delivery_address_id` INT       NOT NULL,
    `customer_id`         INT       NOT NULl,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;