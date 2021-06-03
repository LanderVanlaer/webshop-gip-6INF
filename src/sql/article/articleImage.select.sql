SELECT path, id
FROM articleimage
WHERE article_id = ?
ORDER BY isThumbnail DESC;