SELECT number AS "number",
       s.name AS "street_name",
       postcode AS "postcode",
       c.name AS "country_name"
FROM address
         INNER JOIN street s on address.street_id = s.id
         INNER JOIN township t on s.township_id = t.id
         INNER JOIN country c on t.country_id = c.id
WHERE address.id = ?;