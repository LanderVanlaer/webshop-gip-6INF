SELECT a.id    AS article_id,
       a.name  AS article_name,
       a2.path AS img_path
FROM `like`
         INNER JOIN article a on `like`.article_id = a.id
         INNER JOIN articleimage a2 on a.id = a2.article_id
WHERE customers_id = ?
  AND a2.isThumbnail;