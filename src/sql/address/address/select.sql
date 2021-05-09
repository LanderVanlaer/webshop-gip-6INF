SELECT id, street_id, number
FROM address
WHERE number = ?
  AND street_id = ?;