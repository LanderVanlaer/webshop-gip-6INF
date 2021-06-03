SELECT article.id          AS id,
       article.name        AS name,
       article.price       AS price,
       article.visible     AS visible,
       article.create_date AS create_date,
       article.brand_id    AS brand_id,
       b.name              AS brand_name
FROM article
         INNER JOIN brand b on article.brand_id = b.id
ORDER BY article.id DESC;