SELECT a.id, a.name, i.path
FROM article a
         INNER JOIN articleimage i on a.id = i.article_id
WHERE a.name LIKE ?
  AND a.visible
  AND i.isThumbnail
LIMIT 6;;