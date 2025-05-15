CREATE DATABASE IF NOT EXISTS taller_mecanico;
USE taller_mecanico;




CREATE TABLE  Clientes
(
cliente_DNI VARCHAR(10) NOT NULL,
 cliente_contrasena VARCHAR(255) NOT NULL,
 cliente_nombre VARCHAR(50) NOT NULL,
 cliente_direccion VARCHAR(50) NULL,
 cliente_localidad VARCHAR(15) NULL,
 cliente_telefono VARCHAR(15) NULL,
  PRIMARY KEY (cliente_DNI)
);

CREATE TABLE  Empleados
(
 empleado_DNI VARCHAR(10) NOT NULL,
 empleado_contrasena VARCHAR(255) NOT NULL,
 empleado_nombre VARCHAR(50) NOT NULL,
 empleado_roll VARCHAR(255) NOT NULL,
  PRIMARY KEY (empleado_DNI)
);

CREATE TABLE Vehiculos
(
 vehiculo_patente VARCHAR(10) NOT NULL,
 cliente_DNI VARCHAR(10) NOT NULL,
 vehiculo_marca VARCHAR(10) NOT NULL,
 vehiculo_modelo VARCHAR(10) NOT NULL,
 vehiculo_ano VARCHAR(4) NULL,
 vehiculo_color VARCHAR(10) NULL,
 vehiculo_motor VARCHAR(10) NULL,
 PRIMARY KEY (vehiculo_patente),
 FOREIGN KEY (cliente_DNI) REFERENCES Clientes (cliente_DNI) ON UPDATE CASCADE
 );

CREATE TABLE Servicios
(
 servicio_codigo VARCHAR(5) NOT NULL,
 servicio_nombre VARCHAR(35) NOT NULL,
 servicio_descripcion  VARCHAR(100) NOT NULL,
 servicio_costo  decimal(8,2) NOT NULL,
 PRIMARY KEY (servicio_codigo)
 );

CREATE TABLE Ordenes
(
 orden_numero int NOT NULL,
 orden_fecha VARCHAR(255) NOT NULL,
 vehiculo_patente VARCHAR(10) NOT NULL,
 orden_costo decimal(8,2),
 PRIMARY KEY (orden_numero),
 FOREIGN KEY (vehiculo_patente) REFERENCES  Vehiculos (vehiculo_patente) ON UPDATE CASCADE
);
 
CREATE TABLE Orden_trabajo
(
 orden_numero int NOT NULL,
 servicio_codigo VARCHAR(5) NOT NULL,
 complejidad int NOT NULL,
 costo_ajustado decimal(8,2),
 orden_kilometros VARCHAR(10) NOT NULL,
 PRIMARY KEY (orden_numero, servicio_codigo),
 FOREIGN KEY (servicio_codigo) REFERENCES  Servicios (servicio_codigo),
 FOREIGN KEY (orden_numero) REFERENCES  Ordenes (orden_numero)
);

CREATE VIEW Historico AS 
SELECT
 o.orden_fecha,
 o.vehiculo_patente,
 ot.orden_numero,
 ot.orden_kilometros,
 s.servicio_descripcion 
 FROM
	Ordenes o
	JOIN Orden_trabajo ot ON o.orden_numero = ot.orden_numero
	JOIN Servicios s ON ot.servicio_codigo = s.servicio_codigo
ORDER BY
	o.vehiculo_patente
;





INSERT INTO Clientes VALUES ('28090318','Miguel123','Miguel Martinez','Pedro Lozano 452','Caba','1145269854');
INSERT INTO Clientes VALUES ('19786413','Maria123','María García','Ascasubi 1342','Buenos Aires','1167439855');
INSERT INTO Clientes VALUES ('32489632','Juan123','Juan Pérez','San Martin 514','Caba','1167349281');
INSERT INTO Clientes VALUES ('41298533','Carlos123','Carlos Rodríguez',NULL,'Mendoza',NULL);
INSERT INTO Clientes VALUES ('18762965','Laura 123','Laura Martínez',NULL,NULL,'3515555678');
INSERT INTO Clientes VALUES ('27552991','Ale123','Alejandro López',NULL,NULL,NULL);
INSERT INTO Clientes VALUES ('43796532','Sergio123','Sergio Benitez','Av.Saenz 708','Caba','1147552201');
INSERT INTO Clientes VALUES ('30164750','Maria123','Maria Sotelo','Tandil 6940','Caba','1122083320');
INSERT INTO Clientes VALUES ('22870111','Juan123','Juan Villalba','Monroe 87','Lanus','1137081077');
INSERT INTO Vehiculos VALUES ('AB307CI','18762965','Volkswagen','Gol trend','2017','Negro', 'HTV 1.6');
INSERT INTO Vehiculos VALUES ('GHI410','32489632','Citroen','Xsara','2007','Rojo', 'L416V 1.8');
INSERT INTO Vehiculos VALUES ('AE489AB','28090318','Peugeot','208','2020',NULL, NULL);
INSERT INTO Vehiculos VALUES ('NJK038','30164750','Chevrolet','Corsa','2011','Azul', NULL);
INSERT INTO Vehiculos VALUES ('CDE091','19786413','Renault','Clio','2003',NULL, 'BLUE DCI');
INSERT INTO Vehiculos VALUES ('JKM733','32489632','Susuki','Fun',NULL,'Gris', NULL);
INSERT INTO Vehiculos VALUES ('AA459FT','30164750','Toyota','Corolla','2016','Blanco', 'VVTI 1.8');
INSERT INTO Vehiculos VALUES ('FOM132','41298533','Fiat','Palio','2012','Negro', 'Fire 1.6');
INSERT INTO Vehiculos VALUES ('EOZ386','43796532','Ford','Fiesta','2004','Rojo', 'ZETECK 1.6');
INSERT INTO Vehiculos VALUES ('POD166','22870111','Dodge','Journey','2015','Verde', 'DOHC Pentastar 3.6');
INSERT INTO Servicios VALUES ('S001','SERVICE','CAMBIO DE FILTROS –ACEITE, AIREMOTOR, AIRE HABITACULO, COMBUSTIBLE- CAMBIO DE FLUIDOS',8500);
INSERT INTO Servicios VALUES ('S002','SERVICE DISTRIBUCION','CORREA O CADENA DE DISTRIBUCION, TENSORES, CORREA DE ACCESORIOS',22000);
INSERT INTO Servicios VALUES ('D001','DIAGNOSTICO COMPUTARIZADO','TOMA DE DIAGNOSTICO',5000);
INSERT INTO Servicios VALUES ('FR001','FRENOS DELANTEROS', 'REPARACION O CAMBIO DE DISCO, CAMBIO DE PASTILLAS.',12000);
INSERT INTO Servicios VALUES ('FR002','FRENOS TRASERO','REPARACION O CAMBIO DE ZAPATAS, REPARACION O CAMBIO DE CAMPANAS.',12600);	
INSERT INTO Servicios VALUES ('FR003','SISTEMA DE FRENOS','BOMBA, ABS, CALIBRQACION DE FRENOS',11400);
INSERT INTO Servicios VALUES ('SD001','SUSPENSIÓN DELANTERA BÁSICA','CAMBIO DE ROTULAS, BIELETA,BUJES DE PARRILLA, BUJES BARRA ESTABILIZADORA',18200);	
INSERT INTO Servicios VALUES ('SD002','SUSPENSIÓN DELANTERA COMPLETA','SUPENCION DELANTERA BASICA + AMORTIGUADORES, CASOLETAS, ESPIRALES, PARRILLAS',27400);
INSERT INTO Servicios VALUES ('SDI001','SISTEMA DE DIRECCION','CREMALLERA, EXTREMOS, PRECAP, COLUMNA DE DIRECCION',13300);
INSERT INTO Servicios VALUES ('STR001','SISTEMA TRACCION','PALIERES, TRICETAS,  HOMOCINETICAS, CARDAN, DIFERENCIAL',17600);
INSERT INTO Servicios VALUES ('ST001','SUSPENSIÓN TRASERA BÁSICA','BUJES DE PARRILLA SUPERIOR E INFERIOR, BUJES PUENTE TRASERO O BRAZO OSCILANTE',22100);
INSERT INTO Servicios VALUES ('ST002','SUSPENSIÓN TRASERA COMPLETA','SUSPENSIÓN TRASERA BASICA + AMORTIGUADORES, ESPIRALES, PUENTE TRASERO, PARRILLAS INFERIOR Y SUPERIOR',38200);
INSERT INTO Servicios VALUES ('RR001','RULEMANES DE RUEDA DELANTEROS','CAMBIO DE RULEMANES DE RUEDA',7800);
INSERT INTO Servicios VALUES ('RR002','RULEMANES DE RUEDA TRASEROS','CAMBIO DE RULEMANES DE RUEDA',8100);
INSERT INTO Servicios VALUES ('LIM001','LIMPIEZA DE INYECTORES','LIMPIEZA Y PUESTA A PUNTO DE INYECTORES',19700);
INSERT INTO Servicios VALUES ('SRF001','SISTEMA DE REFRIGERACION','CAMBIO DE MANGUERAS, RADIADOR, TERMOSTATO, BULBO DE TEMPERATURA,  BIDON DE REFRIGERANTE, BOMBE DE AGUA',14600);
INSERT INTO Servicios VALUES ('SE001','SISTEMA DE ENCENDIDO','BOBINA, CABLES, BUJIAS, PRECALENTADORES, DISTRIBUIDOR',10400);
INSERT INTO Servicios VALUES ('CLA001','CAMBIO DE LAMPARAS','REVICION Y CAMBIO DE LAMPARAS',7200);
INSERT INTO Servicios VALUES ('SEL001','SISTEMA ELECTRICO','BATERIA, ALTERNADOR, ARRANQUE',9800);
INSERT INTO Servicios VALUES ('RE001','REVISION ECU','REVISION, AJUSTE PROGRAMACION DE ECU',19600);
INSERT INTO Servicios VALUES ('SA001','SISTEMA DE ADMSION','LIMPIEZA, REGULACION Y PUESTA A PUNTO DE CARBURADOR O CUERPO MARIPOSA',18700);
INSERT INTO Servicios VALUES ('TP001','TAPA DE CILINDRO','REPARACION DE TAPA DE CILINDROS, CAMBIO DE JUNTAS, RETENES Y BULONES',48500);
INSERT INTO Servicios VALUES ('MT001','½ MOTOR','CAMBIO DE AROS, METALES Y RETENES',98400);
INSERT INTO Servicios VALUES ('MT002','MOTOR COMPLETO','DESARME Y REPARACION COMPLETA DE MOTOR',122400);
INSERT INTO Servicios VALUES ('EB001','EMBRAGUE','CAMBIO O REPARACION DE EMBRAGUE, BOMBA, BOMBIN, RULEMAN DE EMPUJE',59800);
INSERT INTO Servicios VALUES ('CV001','CAJA DE VELOCIDADES','REPARACION DE COMPETA DE CAJA DE VELOCIDADES CAMBIO DE RETENES',72600);
INSERT INTO Servicios VALUES ('SES001','SISTEMA DE ESCAPE ','REPARACION DE MULTIPLE DE ESCAPE, CAÑO DE ESCAPE, SILENCIADOR, CAMBIO DE JUNTAS.',39500);
INSERT INTO Servicios VALUES ('OT001','OTROS','TRABAJOS NO CONTEMPLADOS.',55600);
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000001,'2019-07-12','AB307CI');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000002,'2019-08-15','NJK038');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000003,'2019-08-19','JKM733');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000004,'2019-09-01','POD166');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000005,'2019-09-15','AA459FT');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000006,'2019-09-22','EOZ386');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000007,'2019-09-28','FOM132');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000008,'2019-10-11','CDE091');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000009,'2019-12-19','GHI410');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000010,'2020-03-25','AA459FT');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000011,'2020-06-04','POD166');
INSERT INTO Ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (00000012,'2020-08-06','AB307CI');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000001,'S001','2', '35014');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000001,'STR001','1', '35014');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000002,'SA001','3', '120324');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000002,'FR003','1', '120324');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000002,'CLA001','1', '120324');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000003,'MT002','2', '250341');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000004,'CV001','2', '60724');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000004,'EB001','2', '60724');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000005,'EB001','1', '71543');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000005,'CV001','1', '71543');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000006,'SD002','1', '47980');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000006,'ST002','2', '47980');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000007,'FR001','3', '56782');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000007,'FR002','3', '56782');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000008,'SES001','2', '25619');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000009,'SEL001','2', '94723');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000010,'SDI001','1', '119832');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000011,'D001','1', '43909');
INSERT INTO Orden_trabajo (orden_numero, servicio_codigo, complejidad, orden_kilometros) VALUES (00000012,'S002','1', '67413');
INSERT INTO Empleados VALUES ('30700247','Chris123','Christian Caprarulo','mecanico');
INSERT INTO Empleados VALUES ('44671150','Sofia123','Sofia Duarte','recepcionista');
INSERT INTO Empleados VALUES ('24874723','Stella123','Stella Brzostowski','recepcionista');
INSERT INTO Empleados VALUES ('47651867','Martin123','Martin Caprarulo','mecanico');


DELIMITER $$

CREATE TRIGGER before_insert_orden_trabajo
BEFORE INSERT ON Orden_trabajo
FOR EACH ROW
BEGIN
  DECLARE base_costo DECIMAL(8,2) DEFAULT 0;

  -- Obtener el costo base del servicio
  SELECT servicio_costo INTO base_costo
  FROM Servicios
  WHERE servicio_codigo = NEW.servicio_codigo;

  -- Calcular el costo ajustado según la complejidad
  SET NEW.costo_ajustado = base_costo *
    CASE NEW.complejidad
      WHEN 1 THEN 1.1
      WHEN 2 THEN 1.2
      WHEN 3 THEN 1.3
      ELSE 1
    END;
END$$

DELIMITER ;
