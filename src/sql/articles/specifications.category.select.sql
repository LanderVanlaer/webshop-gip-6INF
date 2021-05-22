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
WHERE category_id = ?
ORDER BY specification_id;