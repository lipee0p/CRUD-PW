-- Criação do banco de dados caso não exista
CREATE DATABASE IF NOT EXISTS style_barber DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE style_barber;

-- Tabela de Usuários do Sistema
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de Cortes de Cabelo
CREATE TABLE IF NOT EXISTS cortes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_corte VARCHAR(100) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    adicional_freestyle DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB;

-- Tabela de Funcionários
CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    funcao VARCHAR(100) NOT NULL,
    data_admissao DATE NOT NULL,
    data_nascimento DATE NOT NULL,
    salario DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

-- Tabela de Clientes/Agendamentos
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    horario_agendamento DATETIME NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    corte_id INT NULL,
    FOREIGN KEY (corte_id) REFERENCES cortes(id) ON DELETE SET NULL
) ENGINE=InnoDB;
