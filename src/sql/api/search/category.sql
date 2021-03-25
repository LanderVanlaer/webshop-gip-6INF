SELECT c.id,
       c.nameD,
       c.nameF,
       c.nameE
FROM category c
WHERE c.nameE LIKE ?
   OR c.nameF LIKE ?
   OR c.nameD LIKE ?
LIMIT 6;