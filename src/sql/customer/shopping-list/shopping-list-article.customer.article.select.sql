SELECT shoppingcartarticle_id         AS shoppingcartarticle_id,
       customer_id                    AS customer_id,
       SUM(amount)                    AS amount,
       a.price                        AS price,
       a.price * amount               AS price_total,
       shoppingcartarticle.article_id AS article_id,
       path                           AS image_path,
       a.name                         AS article_name
FROM shoppingcartarticle
         INNER JOIN article a on shoppingcartarticle.article_id = a.id
         INNER JOIN articleimage a2 on a.id = a2.article_id
WHERE customer_id = ?
  AND a.id = ?
  AND isThumbnail
GROUP BY article_id;