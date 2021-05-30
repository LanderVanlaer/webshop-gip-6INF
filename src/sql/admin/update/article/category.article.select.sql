SELECT category.id,
       category.nameD,
       category.nameE,
       category.nameF
FROM category
         INNER JOIN articlecategory a on category.id = a.category_id
WHERE a.article_id = ?;