CREATE TABLE `gigacam`.`shoppingcartarticle`
(
    `shoppingcart_id` INT NOT NULL,
    `article_id`      INT NOT NULL,
    `amount`          INT(2) NOT NULL
) ENGINE = InnoDB;