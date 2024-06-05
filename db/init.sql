CREATE TABLE Asesor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    identificacion VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    zona CHAR(1) NOT NULL
);

CREATE TABLE Cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    identificacion VARCHAR(20) NOT NULL,
    zona CHAR(1) NOT NULL
);

CREATE TABLE Turno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(4) NOT NULL,
    asesor_id INT,
    cliente_id INT,
    zona CHAR(1) NOT NULL,
    FOREIGN KEY (asesor_id) REFERENCES Asesor(id),
    FOREIGN KEY (cliente_id) REFERENCES Cliente(id)
);
