-- database/wallet_db.sql
CREATE DATABASE IF NOT EXISTS wallet_db;
USE wallet_db;

-- Table des wallets
CREATE TABLE wallets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(20) UNIQUE NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    solde DECIMAL(15,2) DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_telephone (telephone),
    INDEX idx_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des transactions
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    montant DECIMAL(15,2) NOT NULL,
    date_heure DATETIME DEFAULT CURRENT_TIMESTAMP,
    type ENUM('Dépôt', 'Retrait') NOT NULL,
    wallet_id INT NOT NULL,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    INDEX idx_code (code),
    INDEX idx_date (date_heure)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Données de test (optionnelles)
INSERT INTO wallets (nom, prenom, telephone, code, solde) VALUES
('Dupont', 'Jean', '771234567', 'WALLET001', 10000),
('Martin', 'Marie', '781234568', 'WALLET002', 2500),
('Diallo', 'Oumar', '701234569', 'WALLET003', 5000);