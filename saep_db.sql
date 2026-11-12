CREATE DATABASE saep_db;

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha_hash VARCHAR(255)
);

CREATE TABLE tutores (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100),
    cpf_criptografado VARCHAR(255)
);

CREATE TABLE pets (
    id SERIAL PRIMARY KEY,
    tutor_id INT REFERENCES tutores(id) ON DELETE CASCADE,
    nome VARCHAR(100)
);

CREATE TABLE agendamentos (
    id SERIAL PRIMARY KEY,
    tutor_id INT REFERENCES tutores(id) ON DELETE CASCADE,
    pet_id INT REFERENCES pets(id) ON DELETE CASCADE,
    data_agendamento DATE
);

-- Inserindo 3 Usuários (A senha para todos é '123', usando hash Bcrypt)
INSERT INTO usuarios (nome, email, senha_hash) VALUES
('Admin', 'admin@vetidi.com', '$2y$12$YeD1ULRkSZZrViru6zV.j.JdwtzoaNNEJcxKQl.Vng42oIvamFU.C'),
('João Vet', 'joao@vetidi.com', '$2y$12$WFtW3SKHv2N8hm24GwsmM.8F5TXK0/2ShC8u1Y2hKaB/ZSbhdkrZS'),
('Maria Rec', 'maria@vetidi.com', '$2y$12$RMvLb16raNDq4i5uoZWdHeR1wSJtcrS4nX4BSF1fpRfhQ2bBXFy.e');

-- Inserindo 3 Tutores (CPF criptografado em Base64 para simplificação no teste prático)
INSERT INTO tutores (nome, cpf_criptografado) VALUES
('Carlos Silva', 'MTIzNDU2Nzg5MDE='),
('Ana Souza', 'OTg3NjU0MzIxMDk='),
('Pedro Lima', 'NDU2Nzg5MTIzMDA=');

-- Inserindo 3 Pets
INSERT INTO pets (tutor_id, nome) VALUES
(1, 'Rex'), (2, 'Miau'), (3, 'Bidu');

-- Inserindo 3 Agendamentos
INSERT INTO agendamentos (tutor_id, pet_id, data_agendamento) VALUES
(1, 1, '2026-11-20'), (2, 2, '2026-11-21'), (3, 3, '2026-11-22');