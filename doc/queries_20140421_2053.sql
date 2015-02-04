SELECT * FROM products;
SELECT * FROM tags_mappings;
SELECT * FROM event_log;
TRUNCATE TABLE event_log;
SELECT * FROM users;

SELECT 
	products.product_name, 
	SUM(IF((event_log.antenna_in = 1 AND event_log.antenna_out = 2), 1, 0)) AS salidas, 
	SUM(IF((event_log.antenna_in = 2 AND event_log.antenna_out = 1), 1, 0)) AS entradas,
	(SUM(IF((event_log.antenna_in = 1 AND event_log.antenna_out = 2), -1, 0)) - SUM(IF((event_log.antenna_in = 2 AND event_log.antenna_out = 1), -1, 0))) AS diff
FROM event_log 
INNER JOIN tags_mappings ON event_log.tag = tags_mappings.tag
INNER JOIN products ON tags_mappings.upc = products.upc
#WHERE event_log.antenna_in = 1 AND event_log.antenna_out = 2
GROUP BY products.id;

SELECT * FROM tags_mappings WHERE tag IN ('30342848A80ABE4000000002', '30342848A80ABEC000000003');

SELECT * FROM tags_mappings WHERE upc IN ('0004', '0006');
SELECT upc, COUNT(*) AS cnt FROM tags_mappings GROUP BY upc;