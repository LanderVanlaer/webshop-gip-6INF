SELECT path
FROM articleimage
WHERE article_id = ?
ORDER BY isThumbnail DESC;