CREATE TABLE `gigacam`.`address`
(
    `id`          INT          NOT NULL AUTO_INCREMENT,
    `township_id` INT          NOT NULL,
    `street`      VARCHAR(255) NOT NULL,
    `number`      INT(4) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;