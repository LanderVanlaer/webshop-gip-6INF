SELECT specification_id, value
FROM articlespecification
         INNER JOIN specification s on articlespecification.specification_id = s.id
         INNER JOIN category c on s.category_id = c.id
WHERE article_id = ?
  AND category_id = ?;