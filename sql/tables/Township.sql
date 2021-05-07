CREATE TABLE `gigacam`.`township`
(
    `id`         INT        NOT NULL AUTO_INCREMENT,
    `postcode`   VARCHAR(7) NOT NULL,
    `country_id` INT        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;