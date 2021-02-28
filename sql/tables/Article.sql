CREATE TABLE `gigacam`.`article`
(
    `id`           INT           NOT NULL AUTO_INCREMENT,
    `brand_id`     INT           NOT NULL,
    `name`         VARCHAR(32)   NOT NULL,
    `descriptionD` VARCHAR(1023) NULL,
    `descriptionF` VARCHAR(1023) NULL,
    `descriptionE` VARCHAR(1023) NULL,
    `price`        FLOAT(7, 2)   NOT NULL,
    `visible`      BOOLEAN       NULL,
    `create_date`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;