CREATE TABLE `gigacam`.`articleimage`
(
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `path`       VARCHAR(255) NOT NULL,
    `article_id` INT          NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;