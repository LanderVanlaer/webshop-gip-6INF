CREATE TABLE `gigacam`.`orderarticle`
(
    `order_id`   INT NOT NULL AUTO_INCREMENT,
    `article_id` INT NOT NULL,
    `amount`     INT(2) NOT NULL,
    PRIMARY KEY (`order_id`)
) ENGINE = InnoDB;