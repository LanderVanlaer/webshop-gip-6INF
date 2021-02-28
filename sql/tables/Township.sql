CREATE TABLE `gigacam`.`township`
(
    `id`          INT         NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(63) NOT NULL,
    `postcode`    VARCHAR(7)  NOT NULL,
    `province_id` INT         NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;