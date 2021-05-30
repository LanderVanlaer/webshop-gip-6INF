SELECT articlespecification.id AS articlespecification_id,
       value,
       nameD,
       nameF,
       nameE
FROM articlespecification
         INNER JOIN specification s on articlespecification.specification_id = s.id
WHERE article_id = ?
ORDER BY s.category_id, s.id;