-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS clinica;
USE clinica;

-- -----------------------------
-- Tabela: pacientes
-- -----------------------------
CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) UNIQUE,
    telefone VARCHAR(20)
);

-- -----------------------------
-- Tabela: medicos
-- -----------------------------
CREATE TABLE medicos (
    id_medico INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especialidade VARCHAR(80) NOT NULL
);

-- -----------------------------
-- Tabela: consultas
-- -----------------------------
CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_medico INT NOT NULL,
    data_consulta DATE NOT NULL,
    hora_consulta TIME NOT NULL,

    -- Relacionamentos
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
        ON DELETE CASCADE,
    FOREIGN KEY (id_medico) REFERENCES medicos(id_medico)
        ON DELETE CASCADE
);

-- Opcional: Inserir alguns dados iniciais
INSERT INTO medicos (nome, especialidade) VALUES
('Dr. João Almeida', 'Cardiologia'),
('Dra. Maria Ferraz', 'Dermatologia'),
('Dr. Carlos Ribeiro', 'Ortopedia');

INSERT INTO pacientes (nome, email, telefone) VALUES
('Ana Silva', 'ana@gmail.com', '11987654321'),
('Pedro Santos', 'pedro@gmail.com', '11999887766');
