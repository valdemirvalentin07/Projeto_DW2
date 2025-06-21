CREATE DATABASE Thander;
USE Thander;

CREATE TABLE ordem_servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    endereco VARCHAR(150),
    aparelho_marca VARCHAR(100),
    descricao TEXT,
    status VARCHAR(50) DEFAULT 'Aberta',
    data_entrada DATE NOT NULL,
    data_retirada DATE DEFAULT NULL,
    orcamento_descricao TEXT DEFAULT NULL,
    orcamento_valor DECIMAL(10,2) DEFAULT NULL
);



