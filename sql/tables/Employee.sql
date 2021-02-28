CREATE TABLE `gigacam`.`employee`
(
    `id`        INT          NOT NULL AUTO_INCREMENT,
    `password`  VARCHAR(255) NOT NULL,
    `email`     VARCHAR(127) NOT NULL,
    `firstname` VARCHAR(63)  NULL,
    `lastname`  VARCHAR(63)  NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;