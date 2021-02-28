CREATE TABLE `gigacam`.`specification`
(
    `id`          INT         NOT NULL AUTO_INCREMENT,
    `category_id` INT         NOT NULL,
    `nameD`       VARCHAR(31) NOT NULL,
    `nameF`       VARCHAR(31) NOT NULL,
    `nameE`       VARCHAR(31) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;