SELECT a.name,
       a.descriptiond,
       a.descriptionf,
       a.descriptione,
       a.price,
       b.name AS brand_name,
       b.id   AS brand_id,
       b.logo
FROM article AS a
         INNER JOIN brand b on a.brand_id = b.id
WHERE a.id = ?;
