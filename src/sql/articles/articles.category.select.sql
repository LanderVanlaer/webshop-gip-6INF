SELECT art.id,
       art.name,
       art.price,
       art.descriptionD,
       art.descriptionE,
       art.descriptionF,
       brand.name brand_name,
       brand.logo brand_logo,
       img.path
FROM article art
         INNER JOIN articlecategory c on art.id = c.article_id
         INNER JOIN brand on art.brand_id = brand.id
         INNER JOIN articleimage img on art.id = img.article_id
WHERE c.category_id = ?
  AND art.visible
  AND img.isThumbnail;