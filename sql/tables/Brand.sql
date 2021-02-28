CREATE TABLE `gigacam`.`brand`
(
    `id`   INT         NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(31) NOT NULL,
    `logo` VARCHAR(127) NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;