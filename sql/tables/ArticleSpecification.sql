CREATE TABLE `gigacam`.`articlespecification`
(
    `id`               INT         NOT NULL AUTO_INCREMENT,
    `article_id`       INT         NOT NULL,
    `specification_id` INT         NOT NULL,
    `value`            VARCHAR(31) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;