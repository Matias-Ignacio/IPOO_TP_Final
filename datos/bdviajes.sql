CREATE DATABASE bdviajes; 

CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO empresa (enombre, edireccion) VALUES 
    ('Transporter Roco', 'Las Palmas 234'),
    ('Viaje bien', 'Ezeiza 234'),
    ('Via Loquero', 'Veranito 234');

CREATE TABLE responsable (
    rnumeroempleado bigint AUTO_INCREMENT,
    rnumerolicencia bigint,
	rnombre varchar(150), 
    rapellido  varchar(150), 
    PRIMARY KEY (rnumeroempleado)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) VALUES 
    (123456, 'Juancito', 'Messi'),
    (123457, 'Pedrito', 'Mossi'),
    (123458, 'Pepito', 'Massi');
	
CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT, /*codigo de viaje*/
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;

INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) VALUES
    ('Bahia Blanca', 3, 0, 2, 3000),
    ('Bahia Negra', 7, 0, 1, 3000),
    ('San MArtin', 10, 1, 1, 3000),
    ('Mendoza', 5, 1, 0, 3000),
    ('Buenos Aires', 5, 2, 0, 3000);
	
CREATE TABLE pasajero (
    pdocumento varchar(15),
    pnombre varchar(150), 
    papellido varchar(150), 
	ptelefono int, 
	idviaje bigint,
    PRIMARY KEY (pdocumento),
	FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)	
    )ENGINE=InnoDB DEFAULT CHARSET=utf8; 

INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) VALUES
    ('32159156', 'Lolo', 'Garcia', 299111444, 0),
    ('22333444', 'Lola', 'Garcia', 299444555, 0),
    ('11222333', 'Esmeralda', 'Lopez', 299444555, 0),
    ('11444555', 'Paolo', 'Reswur', 299555444, 1),   
    ('44555888', 'Paola', 'Reswur', 299555333, 1),
    ('33444111', 'Martin', 'Palermo', 298777888, 1);

 
  
