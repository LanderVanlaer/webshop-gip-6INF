SELECT o.date                        AS date,
       a.id                          AS article_id,
       a.name                        AS article_name,
       a.price                       AS price_unit,
       oa.amount                     AS amount,
       ROUND(oa.amount * a.price, 2) AS price_total
FROM `order` o
         INNER JOIN orderarticle oa on o.id = oa.order_id
         INNER JOIN article a on oa.article_id = a.id
WHERE customer_id = ?
ORDER BY date DESC;