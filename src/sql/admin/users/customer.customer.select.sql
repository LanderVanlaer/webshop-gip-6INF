SELECT customer.firstname         AS firstname,
       customer.lastname          AS lastname,
       customer.email             AS email,
       customer.registration_code AS registration_code,
       customer.active            AS active,
       CONCAT(
               s.name, ' ',
               a.number, ', ',
               t.postcode, ' ',
               c.name
           )                      AS address
FROM customer
         INNER JOIN address a
                    on customer.address_id = a.id
         INNER JOIN street s on a.street_id = s.id
         INNER JOIN township t on s.township_id = t.id
         INNER JOIN country c on t.country_id = c.id
WHERE customer.id = ?;