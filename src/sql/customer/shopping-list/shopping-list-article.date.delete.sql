DELETE
FROM shoppingcartarticle
WHERE UNIX_TIMESTAMP(date) < ?; /* unix timestamp in seconds : 1621850738 */