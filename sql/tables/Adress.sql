CREATE TABLE `gigacam`.`address`
(
    `id`        INT    NOT NULL AUTO_INCREMENT,
    `street_id` INT    NOT NULL,
    `number`    INT(4) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;