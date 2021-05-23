SELECT DISTINCT a.value,
                specification_id as specification_id,
                c.nameD          as c_nameD,
                c.nameE          as c_nameE,
                c.nameF          as c_nameF,
                s.nameD          as s_nameD,
                s.nameE          as s_nameE,
                s.nameF          as s_nameF
FROM category c
         INNER JOIN specification s on c.id = s.category_id
         INNER JOIN articlespecification a on s.id = a.specification_id
         INNER JOIN article a2 on a.article_id = a2.id
WHERE category_id = ?
  AND a2.visible
ORDER BY specification_id;