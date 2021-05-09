SELECT id, name, township_id
FROM street
WHERE name = ?
  AND township_id = ?;