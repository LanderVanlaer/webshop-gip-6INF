SELECT a.id      as article_id,
       a.name    AS article_name,
       aimg.path AS img_path,
       MAX(date) AS max_date
FROM visited
         INNER JOIN article a on visited.article_id = a.id
         INNER JOIN articleimage aimg on a.id = aimg.article_id
WHERE customer_id = ?
  AND aimg.isThumbnail
  AND date BETWEEN
    DATE_SUB(NOW(), INTERVAL 30 DAY)
    AND NOW()
GROUP BY article_id
ORDER BY max_date DESC;