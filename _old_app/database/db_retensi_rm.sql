-- phpMyAdmin SQL Dump
-- https://www.phpmyadmin.net/
--
-- Database: `db_retensi_rm`
--

CREATE DATABASE IF NOT EXISTS `db_retensi_rm`;
USE `db_retensi_rm`;

-- --------------------------------------------------------
-- Table strktur untuk table `users`
--

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    role ENUM('admin','petugas') DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE berkas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    no_rm VARCHAR(20) NOT NULL,
    nama_pasien VARCHAR(100) NOT NULL,
    tgl_lahir DATE,
    nama_berkas VARCHAR(100),
    file_pdf VARCHAR(255), -- path file PDF
    status ENUM('Aktif','Inaktif','Musnah') DEFAULT 'pending',
    tgl_retensi DATE,
    keterangan TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);


-- Dumping data untuk table `users`
INSERT INTO users (username, password, nama_lengkap, role) 
VALUES ('admin', '$2y$10$YourHashHere', 'Administrator', 'admin');