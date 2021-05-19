SELECT value,
       s.nameD AS specification_name_d,
       s.nameE AS specification_name_e,
       s.nameF AS specification_name_f,

       c.nameD AS category_name_d,
       c.nameE AS category_name_e,
       c.nameF AS category_name_f,
       c.id    AS category_id
FROM articlespecification
         INNER JOIN specification s on articlespecification.specification_id = s.id
         INNER JOIN category c on s.category_id = c.id
WHERE article_id = ?
ORDER BY c.id, s.id;