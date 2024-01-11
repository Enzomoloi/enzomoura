CREATE DATABASE cadastro;

USE cadastro;

CREATE TABLE clientes
(
	codigo INT NOT NULL AUTO_INCREMENT,
	nome VARCHAR(64) NOT NULL,
	endereco VARCHAR(64) NOT NULL,
	PRIMARY KEY(codigo)
);