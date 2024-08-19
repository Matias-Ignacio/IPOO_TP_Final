--
-- Base de datos: `bdviajes`
--

CREATE DATABASE bdviajes; 

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE persona(
    pdocumento int(11) NOT NULL,
    pnombre varchar(150) NOT NULL, 
    papellido varchar(150) NOT NULL, 
	ptelefono int(15) 
    )ENGINE=InnoDB DEFAULT CHARSET=utf8; 

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE empresa(
    idempresa int(8) AUTO_INCREMENT,
    enombre varchar(150) NOT NULL,
    edireccion varchar(150) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsable`
--

CREATE TABLE responsable (
    pdocumento int(11) NOT NULL,
    rnumeroempleado int(8) NOT NULL,
    rnumerolicencia int(8) NOT NULL
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viaje`
--

CREATE TABLE viaje (
    idviaje int(8) AUTO_INCREMENT, /*codigo de viaje*/
	vdestino varchar(150) NOT NULL,
    vcantmaxpasajeros int(2) NOT NULL,
	idempresa int(8) NOT NULL,
    pdocumento int(11) NOT NULL,
    vimporte float NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasajero`
--

CREATE TABLE pasajero (
    pdocumento int(11) NOT NULL,
	idviaje int(8) NOT NULL
    )ENGINE=InnoDB DEFAULT CHARSET=utf8; 

-- --------------------------------------------------------
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `persona`
--
ALTER TABLE persona
  ADD PRIMARY KEY (pdocumento);

--
-- Indices de la tabla `responsable`
--
ALTER TABLE responsable
  ADD PRIMARY KEY (pdocumento);

--
-- Indices de la tabla 'pasajero'
--
ALTER TABLE pasajero
  ADD PRIMARY KEY (pdocumento);
--
-- Indices de la tabla `empresa`
--
ALTER TABLE empresa
  ADD PRIMARY KEY (idempresa);

--
-- Indices de la tabla 'viaje'
--
ALTER TABLE viaje
 PRIMARY KEY (idviaje),

--
-- Filtros para la tabla 'responsable'
--
ALTER TABLE responsable
  ADD CONSTRAINT responsable_ibfk_1 FOREIGN KEY (pdocumento) REFERENCES persona (pdocumento);


--
-- Filtros para la tabla 'pasajero'
--
ALTER TABLE pasajero
  ADD CONSTRAINT pasajero_ibfk_1 FOREIGN KEY (pdocumento) REFERENCES persona (pdocumento);


--
-- Filtros para la tabla 'pasajero'
--
ALTER TABLE pasajero
  ADD CONSTRAINT pasajero_ibfk_2 FOREIGN KEY (idviaje) REFERENCES viaje (idviaje);
		
--
-- Filtros para la tabla 'viaje'
--   
ALTER TABLE viaje
    ADD CONSTRAINT viaje_ibfk_1 FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	ADD CONSTRAINT viaje_ibfk_2 FOREIGN KEY (pdocumento) REFERENCES responsable (pdocumento),
    ON UPDATE CASCADE
    ON DELETE CASCADE
COMMIT;

--
-- Volcado de datos para la tabla `persona`
--        
INSERT INTO persona (pdocumento, pnombre, papellido, ptelefono) VALUES 
    (123123123, 'Lolo', 'Garcia', 299111444),
    (123456466, 'Lola', 'Garcia', 299444555),
    (123111222, 'Esmeralda', 'Lopez', 299444555),
    (11444555, 'Paolo', 'Reswur', 299555444),   
    (44555888, 'Paola', 'Reswur', 299555333),
    (33444111, 'Martin', 'Palermo', 298777888);

--
-- Volcado de datos para la tabla 'responsable'
--  
INSERT INTO responsable (pdocumento, rnumeroempleado, rnumerolicencia) VALUES 
    (123123123, 1 , 2356),
    (123456466, 2 , 898),
    (123111222, 3 , 1248);

--
-- Volcado de datos para la tabla 'empresa'
--      
INSERT INTO empresa (enombre, edireccion) VALUES 
    ('Transporter Roco', 'Las Palmas 234'),
    ('Viaje bien', 'Ezeiza 234'),
    ('Via Loquero', 'Veranito 234');

--
-- Volcado de datos para la tabla 'viaje'
--  
INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, pdocumento, vimporte) VALUES
    ('Bahia Blanca', 5, 1, 123123123, 3000),
    ('Bahia Negra', 5, 3, 123123123, 3000),
    ('San MArtin', 5, 1, 123123123, 3000),
    ('Mendoza', 5, 1, 123456466, 3000),
    ('Buenos Aires', 5, 2, 123456466, 3000);


