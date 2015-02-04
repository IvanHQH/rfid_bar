DROP TABLE IF EXISTS `traffic_rules`;

CREATE TABLE `traffic_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `antenna_in` int(11) NOT NULL,
  `antenna_out` int(11) NOT NULL,
  `action` varchar(45) NOT NULL,
  `message` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


INSERT INTO `traffic_rules` VALUES 
	(1,1,1,'--','No salió'),
	(2,1,2,'Salida','Salida'),
	(3,1,4,'Salida','Salida'),
	(4,2,1,'Entrada','Entrada'),
	(5,2,2,'--','No Salió :: Acción Indebida'),
	(6,2,4,'Salida','Salida :: Acción Indebida'),
	(7,4,1,'Entrada','Entrada'),
	(8,4,2,'Entrada','Entrada :: Accion Indebida'),
	(9,4,4,'--','No regresó');
