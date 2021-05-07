CREATE TABLE `gigacam`.`street`
(
    `id`          INT          NOT NULL AUTO_INCREMENT,
    `township_id` INT          NOT NULL,
    `name`        VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;