SELECT c.id    cid,
       c.nameD cnameD,
       c.nameF cnameF,
       c.nameE cnameE,
       s.id    sid,
       s.nameD snameD,
       s.nameF snameF,
       s.nameE snameE
FROM specification s
         INNER JOIN category c on s.category_id = c.id
WHERE c.nameE LIKE ?
   OR c.nameF LIKE ?
   OR c.nameD LIKE ?
ORDER BY c.id