-- Create Database
CREATE DATABASE IF NOT EXISTS book_club;
USE book_club;

-- Books Table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    badge VARCHAR(50),
    description TEXT
);

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    reset_token VARCHAR(255) NULL,
    reset_token_expiry DATETIME NULL
);

-- Newsletter Subscribers Table
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Books
INSERT INTO books (name, price, image, badge, description) VALUES
('Como Convencer Alguém em 90 Segundos', 33.17, 'como_convencer.jpg', 'Mais vendido', 'Um guia prático para comunicação persuasiva'),
('Gatilhos Mentais', 60.70, 'gatilhos_mentais.jpg', 'Oferta', 'Descubra os segredos da psicologia de vendas'),
('A Arte Da Guerra', 49.90, 'arte_da_guerra.webp', 'Clássico', 'Estratégias de guerra aplicadas à vida e negócios'),
('Mindset', 39.90, 'mindset.jpg', 'Novo', 'Como a mentalidade pode transformar sua vida');
