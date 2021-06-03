SELECT o.id           AS id,
       o.date         AS date,
       SUM(o2.amount) AS amount
FROM customer
         INNER JOIN `order` o on customer.id = o.customer_id
         INNER JOIN orderarticle o2 on o.id = o2.order_id
WHERE customer.id = ?
GROUP BY o.date, o.id
ORDER BY o.date DESC;