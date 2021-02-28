CREATE TABLE `gigacam`.`province`
(
    `id`         INT         NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(31) NOT NULL,
    `country_id` INT         NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;