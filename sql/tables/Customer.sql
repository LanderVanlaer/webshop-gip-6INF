CREATE TABLE `gigacam`.`customer`
(
    `id`                INT          NOT NULL AUTO_INCREMENT,
    `firstname`         VARCHAR(63)  NOT NULL,
    `lastname`          VARCHAR(63)  NOT NULL,
    `email`             VARCHAR(127) NOT NULL,
    `password`          VARCHAR(255) NOT NULL,
    `registration_code` VARCHAR(255) NULL,
    `active`            BOOLEAN      NULL,
    `address_id`        INT          NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;