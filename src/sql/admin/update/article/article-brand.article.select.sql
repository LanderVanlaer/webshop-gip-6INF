SELECT a.id,
       a.name,
       a.descriptionD,
       a.descriptionE,
       a.descriptionF,
       a.price,
       a.visible,
       b.id   AS brand_id,
       b.name AS brand_name
FROM article a
         INNER JOIN brand b on a.brand_id = b.id
WHERE a.id = ?;