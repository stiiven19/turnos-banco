CREATE TABLE Zona (
    letra CHAR(1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE Asesor (
    identificacion VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    zona CHAR(1) NOT NULL,
    FOREIGN KEY (zona) REFERENCES Zona(letra)

);

CREATE TABLE Cliente (
    identificacion VARCHAR(20) PRIMARY KEY, -- Usar la identificación como clave primaria
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE Turno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(4) NOT NULL,
    cliente_identificacion VARCHAR(20) NOT NULL,
    zona CHAR(1) NOT NULL,
    estado ENUM('en_espera', 'atendiendo', 'finalizado') NOT NULL DEFAULT 'en_espera',
    asesor_identificacion VARCHAR(20),
    FOREIGN KEY (cliente_identificacion) REFERENCES Cliente(identificacion),
    FOREIGN KEY (asesor_identificacion) REFERENCES Asesor(identificacion),
    FOREIGN KEY (zona) REFERENCES Zona(letra)
);

CREATE TABLE Asesoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asesor_identificacion VARCHAR(20) NOT NULL,
    cliente_identificacion VARCHAR(20) NOT NULL,
    turno_id INT NOT NULL,
    fecha_atencion DATETIME NOT NULL, -- Fecha de atención del turno
    duracion TIME DEFAULT NULL, -- Duración de la asesoría
    FOREIGN KEY (asesor_identificacion) REFERENCES Asesor(identificacion),
    FOREIGN KEY (cliente_identificacion) REFERENCES Cliente(identificacion),
    FOREIGN KEY (turno_id) REFERENCES Turno(id)
);
INSERT INTO Zona (letra, nombre)
VALUES 
    ('A', 'Tramites Generales'),
    ('B', 'Solicitar Documentos'),
    ('C', 'Transacciones en Caja'),
    ('D', 'Asesorias');

-- Población de la tabla Cliente
INSERT INTO Cliente (identificacion, nombre)
VALUES 
    ('1100000001', 'Juan Perez'),
    ('1100000002', 'María García'),
    ('1100000003', 'Pedro Martínez'),
    ('1100000004', 'Ana Rodríguez'),
    ('1100000005', 'Carlos López'),
    ('1100000006', 'Laura Sánchez'),
    ('1100000007', 'José Ramírez'),
    ('1100000008', 'Sofía Morales'),
    ('2200000001', 'Luisa González'),
    ('2200000002', 'Miguel Torres'),
    ('2200000003', 'Elena Gómez'),
    ('2200000004', 'Daniel Vargas'),
    ('2200000005', 'Isabel Castro'),
    ('2200000006', 'Javier Herrera'),
    ('2200000007', 'Paula Ruiz'),
    ('2200000008', 'Martín Díaz'),
    ('3300000001', 'Gabriela Fernández'),
    ('3300000002', 'Andrés Silva'),
    ('3300000003', 'Valeria Moreno'),
    ('3300000004', 'Roberto Peña'),
    ('3300000005', 'Verónica Jiménez'),
    ('3300000006', 'Ricardo Núñez'),
    ('3300000007', 'Carmen Bravo'),
    ('3300000008', 'Diego Ríos'),
    ('4400000001', 'Carolina Ortega'),
    ('4400000002', 'Fernando Aguilar'),
    ('4400000003', 'Silvia Medina'),
    ('4400000004', 'Martina Herrera'),
    ('4400000005', 'Sebastián Castro'),
    ('4400000006', 'Lucía Flores'),
    ('4400000007', 'Emilio Santos'),
    ('4400000008', 'Alicia Torres');

-- Población de la tabla Turno
INSERT INTO Turno (numero, cliente_identificacion, zona, estado)
VALUES 
    ('A01', '1100000001', 'A', 'en_espera'),
    ('A02', '1100000002', 'A', 'en_espera'),
    ('A03', '1100000003', 'A', 'en_espera'),
    ('A04', '1100000004', 'A', 'en_espera'),
    ('A05', '1100000005', 'A', 'en_espera'),
    ('A06', '1100000006', 'A', 'en_espera'),
    ('A07', '1100000007', 'A', 'en_espera'),
    ('A08', '1100000008', 'A', 'en_espera'),
    ('B01', '2200000001', 'B', 'en_espera'),
    ('B02', '2200000002', 'B', 'en_espera'),
    ('B03', '2200000003', 'B', 'en_espera'),
    ('B04', '2200000004', 'B', 'en_espera'),
    ('B05', '2200000005', 'B', 'en_espera'),
    ('B06', '2200000006', 'B', 'en_espera'),
    ('B07', '2200000007', 'B', 'en_espera'),
    ('B08', '2200000008', 'B', 'en_espera'),
    ('C01', '3300000001', 'C', 'en_espera'),
    ('C02', '3300000002', 'C', 'en_espera'),
    ('C03', '3300000003', 'C', 'en_espera'),
    ('C04', '3300000004', 'C', 'en_espera'),
    ('C05', '3300000005', 'C', 'en_espera'),
    ('C06', '3300000006', 'C', 'en_espera'),
    ('C07', '3300000007', 'C', 'en_espera'),
    ('C08', '3300000008', 'C', 'en_espera'),
    ('D01', '4400000001', 'D', 'en_espera'),
    ('D02', '4400000002', 'D', 'en_espera'),
    ('D03', '4400000003', 'D', 'en_espera'),
    ('D04', '4400000004', 'D', 'en_espera'),
    ('D05', '4400000005', 'D', 'en_espera'),
    ('D06', '4400000006', 'D', 'en_espera'),
    ('D07', '4400000007', 'D', 'en_espera'),
    ('D08', '4400000008', 'D', 'en_espera');

