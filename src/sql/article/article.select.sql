SELECT a.name,
       a.descriptiond,
       a.descriptionf,
       a.descriptione,
       a.price,
       b.name brand_name,
       b.logo
FROM article AS a
         INNER JOIN brand b on a.brand_id = b.id
WHERE a.id = ?
  AND a.visible = true;