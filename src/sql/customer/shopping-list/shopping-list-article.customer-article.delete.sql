DELETE
FROM shoppingcartarticle
WHERE customer_id = ?
  AND article_id = ?;