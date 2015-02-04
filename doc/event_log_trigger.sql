DROP TRIGGER IF EXISTS tgr_event_log;
DELIMITER $$

CREATE TRIGGER tgr_event_log
BEFORE INSERT ON event_log
FOR EACH ROW
BEGIN
DECLARE upc1 VARCHAR(48);
select max(upc) INTO upc1 from tags_mappings where tag=NEW.tag;
SET NEW.upc=upc1;
update tags_mappings set deleted_at=if(((NEW.antenna_in="1" and NEW.antenna_out="2") or (NEW.antenna_in="3" and NEW.antenna_out="4")),CURRENT_TIMESTAMP,null) where tag=NEW.tag and upc=NEW.upc;

END $$
DELIMITER ;