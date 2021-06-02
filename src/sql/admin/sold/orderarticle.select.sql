SELECT oa.order_id    AS order_id,
       a.id           AS article_id,
       a.name         AS name,
       a.price        AS price_unit,
       oa.amount      AS amount,
       amount * price AS price_total,
       o.date         AS date
FROM orderarticle oa
         INNER JOIN article a on oa.article_id = a.id
         INNER JOIN `order` o on oa.order_id = o.id
ORDER BY date DESC, oa.order_id DESC;
