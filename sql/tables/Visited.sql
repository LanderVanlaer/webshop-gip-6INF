CREATE TABLE `gigacam`.`visited`
(
    `id`          INT       NOT NULL AUTO_INCREMENT,
    `date`        TIMESTAMP NOT NULL,
    `customer_id` INT       NOT NULL,
    `article_id`  INT       NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;