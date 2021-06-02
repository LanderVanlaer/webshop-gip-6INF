SELECT o.date                        AS date,
       o.id                          AS order_id,

       CONCAT(
               strt_deli.name, ' ',
               addr_deli.number, ', ',
               town_deli.postcode, ' ',
               ctry_deli.name
           )                         AS delivery_address,
       CONCAT(
               strt_purc.name, ' ',
               addr_purc.number, ', ',
               town_purc.postcode, ' ',
               ctry_purc.name
           )                         AS purchase_address,

       c.id                          AS customer_id,
       c.firstname                   AS firstname,
       c.lastname                    AS lastname,

       oa.amount                     AS amount,

       a.id                          AS article_id,
       a.name                        AS name,
       a.price                       AS price_unit,

       ROUND(oa.amount * a.price, 2) AS price_total
FROM `order` o
         INNER JOIN orderarticle oa on o.id = oa.order_id
         INNER JOIN article a on oa.article_id = a.id
         INNER JOIN customer c on o.customer_id = c.id

         INNER JOIN address addr_deli on o.delivery_address_id = addr_deli.id
         INNER JOIN street strt_deli on addr_deli.street_id = strt_deli.id
         INNER JOIN township town_deli on strt_deli.township_id = town_deli.id
         INNER JOIN country ctry_deli on town_deli.country_id = ctry_deli.id

         INNER JOIN address addr_purc on o.purchase_address_id = addr_purc.id
         INNER JOIN street strt_purc on addr_purc.street_id = strt_purc.id
         INNER JOIN township town_purc on strt_purc.township_id = town_purc.id
         INNER JOIN country ctry_purc on town_purc.country_id = ctry_purc.id

WHERE o.id = ?
ORDER BY article_id;
